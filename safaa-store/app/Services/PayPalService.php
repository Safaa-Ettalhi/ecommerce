<?php

namespace App\Services;

use App\Models\Order;
use Exception;
use Illuminate\Support\Facades\Log;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;

class PayPalService
{
  protected $client;
  
  public function __construct()
  {
      $clientId = config('services.paypal.client_id');
      $clientSecret = config('services.paypal.secret');
      $mode = config('services.paypal.mode', 'sandbox');
      
      // Vérifier si les identifiants sont configurés
      if (empty($clientId) || empty($clientSecret)) {
          Log::error('PayPal n\'est pas correctement configuré. Veuillez vérifier vos identifiants.');
          return;
      }
      
      // Créer l'environnement approprié
      $environment = $mode === 'production'
          ? new ProductionEnvironment($clientId, $clientSecret)
          : new SandboxEnvironment($clientId, $clientSecret);
          
      // Utiliser notre client personnalisé si nous sommes en développement
      if (config('app.env') !== 'production') {
          $this->client = new CustomPayPalHttpClient($environment);
          $this->disableSSLVerification();
      } else {
          // Utiliser le client standard en production
          $this->client = new PayPalHttpClient($environment);
      }
  }
  
  /**
   * Désactive la vérification SSL pour le développement
   */
  private function disableSSLVerification()
  {
      // Configuration globale pour stream_context_set_default
      stream_context_set_default([
          'ssl' => [
              'verify_peer' => false,
              'verify_peer_name' => false,
          ],
      ]);
      
      // Définir les variables d'environnement pour cURL
      putenv('CURLOPT_SSL_VERIFYPEER=0');
      putenv('CURLOPT_SSL_VERIFYHOST=0');
      
      // Nous ne pouvons pas utiliser rename_function car il nécessite l'extension runkit
      // Au lieu de cela, nous allons utiliser une approche différente
  }
  
  /**
   * Créer une commande PayPal
   */
  public function createOrder(Order $order)
  {
      try {
          // Vérifier si le client est initialisé
          if (!$this->client) {
              return [
                  'success' => false,
                  'message' => 'PayPal n\'est pas correctement configuré. Veuillez vérifier vos identifiants.',
              ];
          }
          
          Log::info('Création de commande PayPal', [
              'order_id' => $order->id,
              'total_amount' => $order->total_amount
          ]);
          
          $request = new OrdersCreateRequest();
          $request->prefer('return=representation');
          
          // Calculer les montants avec précision
          $subtotal = $order->total_amount - $order->shipping_cost - $order->tax;
          
          // Formater les montants avec 2 décimales
          $totalAmount = number_format($order->total_amount, 2, '.', '');
          $subtotalAmount = number_format($subtotal, 2, '.', '');
          $shippingAmount = number_format($order->shipping_cost, 2, '.', '');
          $taxAmount = number_format($order->tax, 2, '.', '');
          
          // Construire la commande PayPal
          $request->body = [
              'intent' => 'CAPTURE',
              'purchase_units' => [
                  [
                      'reference_id' => (string) $order->order_number,
                      'description' => 'Commande #' . $order->order_number,
                      'amount' => [
                          'currency_code' => 'EUR',
                          'value' => $totalAmount,
                          'breakdown' => [
                              'item_total' => [
                                  'currency_code' => 'EUR',
                                  'value' => $subtotalAmount,
                              ],
                              'shipping' => [
                                  'currency_code' => 'EUR',
                                  'value' => $shippingAmount,
                              ],
                              'tax_total' => [
                                  'currency_code' => 'EUR',
                                  'value' => $taxAmount,
                              ],
                          ],
                      ],
                      'custom_id' => (string) $order->id,
                  ],
              ],
              'application_context' => [
                  'brand_name' => config('app.name'),
                  'landing_page' => 'BILLING',
                  'user_action' => 'PAY_NOW',
                  'return_url' => route('checkout.success', ['order' => $order->id, 'payment_method' => 'paypal']),
                  'cancel_url' => route('checkout.cancel', ['order' => $order->id]),
              ],
          ];
          
          // Exécuter la requête
          $response = $this->client->execute($request);
          
          Log::info('Réponse PayPal', [
              'status' => $response->statusCode,
              'order_id' => $response->result->id
          ]);
          
          // Trouver l'URL d'approbation
          $approvalUrl = null;
          foreach ($response->result->links as $link) {
              if ($link->rel === 'approve') {
                  $approvalUrl = $link->href;
                  break;
              }
          }
          
          if (!$approvalUrl) {
              throw new Exception('URL d\'approbation PayPal non trouvée');
          }
          
          return [
              'success' => true,
              'order_id' => $response->result->id,
              'approval_url' => $approvalUrl,
          ];
      } catch (Exception $e) {
          Log::error('Erreur PayPal', [
              'message' => $e->getMessage(),
              'trace' => $e->getTraceAsString()
          ]);
          
          return [
              'success' => false,
              'message' => $e->getMessage(),
          ];
      }
  }
  
  /**
   * Capturer une commande PayPal
   */
  public function captureOrder($paypalOrderId)
  {
      try {
          // Vérifier si le client est initialisé
          if (!$this->client) {
              return [
                  'success' => false,
                  'message' => 'PayPal n\'est pas correctement configuré. Veuillez vérifier vos identifiants.',
              ];
          }
          
          Log::info('Capture de commande PayPal', [
              'paypal_order_id' => $paypalOrderId
          ]);
          
          $request = new OrdersCaptureRequest($paypalOrderId);
          $response = $this->client->execute($request);
          
          Log::info('Réponse de capture PayPal', [
              'status' => $response->statusCode,
              'result' => $response->result->status
          ]);
          
          if ($response->result->status === 'COMPLETED') {
              // Récupérer l'ID de la commande depuis custom_id
              $customId = null;
              foreach ($response->result->purchase_units as $purchaseUnit) {
                  if (isset($purchaseUnit->custom_id)) {
                      $customId = $purchaseUnit->custom_id;
                      break;
                  }
              }
              
              return [
                  'success' => true,
                  'payment_id' => $response->result->id,
                  'status' => $response->result->status,
                  'order_id' => $customId,
              ];
          } else {
              return [
                  'success' => false,
                  'message' => 'Le paiement n\'a pas été complété. Statut: ' . $response->result->status,
              ];
          }
      } catch (Exception $e) {
          Log::error('Erreur lors de la capture PayPal', [
              'message' => $e->getMessage(),
              'trace' => $e->getTraceAsString()
          ]);
          
          return [
              'success' => false,
              'message' => $e->getMessage(),
          ];
      }
  }
}

