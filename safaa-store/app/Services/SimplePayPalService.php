<?php

namespace App\Services;

use App\Models\Order;
use Exception;
use Illuminate\Support\Facades\Log;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;

class SimplePayPalService
{
    protected $client;
    
    public function __construct()
    {
        $clientId = config('services.paypal.client_id');
        $clientSecret = config('services.paypal.secret');
        $mode = config('services.paypal.mode', 'sandbox');
        
        // Créer l'environnement approprié
        $environment = $mode === 'production'
            ? new ProductionEnvironment($clientId, $clientSecret)
            : new SandboxEnvironment($clientId, $clientSecret);
            
        // Créer le client PayPal
        $this->client = new PayPalHttpClient($environment);
    }
    
    public function createOrder(Order $order)
    {
        try {
            Log::info('Création de commande PayPal', [
                'order_id' => $order->id,
                'client_id_exists' => !empty(config('services.paypal.client_id')),
                'client_secret_exists' => !empty(config('services.paypal.secret'))
            ]);
            
            $request = new OrdersCreateRequest();
            $request->prefer('return=representation');
            
            // Construire la commande PayPal
            $request->body = [
                'intent' => 'CAPTURE',
                'purchase_units' => [
                    [
                        'reference_id' => $order->order_number,
                        'description' => 'Commande #' . $order->order_number,
                        'amount' => [
                            'currency_code' => 'EUR',
                            'value' => number_format($order->total_amount, 2, '.', ''),
                            'breakdown' => [
                                'item_total' => [
                                    'currency_code' => 'EUR',
                                    'value' => number_format($order->total_amount - $order->shipping_cost - $order->tax, 2, '.', ''),
                                ],
                                'shipping' => [
                                    'currency_code' => 'EUR',
                                    'value' => number_format($order->shipping_cost, 2, '.', ''),
                                ],
                                'tax_total' => [
                                    'currency_code' => 'EUR',
                                    'value' => number_format($order->tax, 2, '.', ''),
                                ],
                            ],
                        ],
                        'custom_id' => $order->id,
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
    
    public function captureOrder($paypalOrderId)
    {
        try {
            Log::info('Capture de commande PayPal', [
                'paypal_order_id' => $paypalOrderId
            ]);
            
            $request = new \PayPalCheckoutSdk\Orders\OrdersCaptureRequest($paypalOrderId);
            $response = $this->client->execute($request);
            
            Log::info('Réponse de capture PayPal', [
                'status' => $response->statusCode,
                'result' => $response->result->status
            ]);
            
            if ($response->result->status === 'COMPLETED') {
                return [
                    'success' => true,
                    'transaction_id' => $response->result->id,
                    'status' => $response->result->status,
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