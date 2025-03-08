<?php

namespace App\Services;

use App\Models\Order;
use Exception;
use Illuminate\Support\Facades\Log;

class DirectPayPalService
{
    protected $clientId;
    protected $clientSecret;
    protected $mode;
    protected $baseUrl;
    protected $accessToken;
    
    public function __construct()
    {
        $this->clientId = config('services.paypal.client_id');
        $this->clientSecret = config('services.paypal.secret');
        $this->mode = config('services.paypal.mode', 'sandbox');
        
        // Définir l'URL de base en fonction du mode
        $this->baseUrl = $this->mode === 'production'
            ? 'https://api-m.paypal.com'
            : 'https://api-m.sandbox.paypal.com';
            
        // Vérifier si les identifiants sont configurés
        if (empty($this->clientId) || empty($this->clientSecret)) {
            Log::error('PayPal n\'est pas correctement configuré. Veuillez vérifier vos identifiants.');
        }
    }
    
    /**
     * Obtenir un token d'accès PayPal
     */
    public function getAccessToken()
    {
        if ($this->accessToken) {
            return $this->accessToken;
        }
        
        $ch = curl_init($this->baseUrl . '/v1/oauth2/token');
        
        // Désactiver la vérification SSL pour le développement
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, $this->clientId . ":" . $this->clientSecret);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json', 'Accept-Language: en_US']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
        
        $response = curl_exec($ch);
        $error = curl_error($ch);
        
        if ($error) {
            Log::error('Erreur lors de l\'obtention du token PayPal', ['error' => $error]);
            throw new Exception('Erreur lors de l\'obtention du token PayPal: ' . $error);
        }
        
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode !== 200) {
            Log::error('Erreur HTTP lors de l\'obtention du token PayPal', [
                'http_code' => $httpCode,
                'response' => $response
            ]);
            throw new Exception('Erreur HTTP lors de l\'obtention du token PayPal: ' . $httpCode);
        }
        
        $data = json_decode($response, true);
        
        if (!isset($data['access_token'])) {
            Log::error('Token d\'accès PayPal non trouvé dans la réponse', ['response' => $data]);
            throw new Exception('Token d\'accès PayPal non trouvé dans la réponse');
        }
        
        $this->accessToken = $data['access_token'];
        return $this->accessToken;
    }
    
    /**
     * Créer une commande PayPal
     */
    public function createOrder(Order $order)
    {
        try {
            // Obtenir un token d'accès
            $accessToken = $this->getAccessToken();
            
            Log::info('Création de commande PayPal', [
                'order_id' => $order->id,
                'total_amount' => $order->total_amount
            ]);
            
            // Calculer les montants avec précision
            $subtotal = $order->total_amount - $order->shipping_cost - $order->tax;
            
            // Formater les montants avec 2 décimales
            $totalAmount = number_format($order->total_amount, 2, '.', '');
            $subtotalAmount = number_format($subtotal, 2, '.', '');
            $shippingAmount = number_format($order->shipping_cost, 2, '.', '');
            $taxAmount = number_format($order->tax, 2, '.', '');
            
            // Construire la commande PayPal
            $payload = [
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
            
            // Créer la commande PayPal
            $ch = curl_init($this->baseUrl . '/v2/checkout/orders');
            
            // Désactiver la vérification SSL pour le développement
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $accessToken,
                'Prefer: return=representation'
            ]);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
            
            $response = curl_exec($ch);
            $error = curl_error($ch);
            
            if ($error) {
                Log::error('Erreur lors de la création de la commande PayPal', ['error' => $error]);
                throw new Exception('Erreur lors de la création de la commande PayPal: ' . $error);
            }
            
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($httpCode < 200 || $httpCode >= 300) {
                Log::error('Erreur HTTP lors de la création de la commande PayPal', [
                    'http_code' => $httpCode,
                    'response' => $response
                ]);
                throw new Exception('Erreur HTTP lors de la création de la commande PayPal: ' . $httpCode . ' - ' . $response);
            }
            
            $data = json_decode($response, true);
            
            if (!isset($data['id'])) {
                Log::error('ID de commande PayPal non trouvé dans la réponse', ['response' => $data]);
                throw new Exception('ID de commande PayPal non trouvé dans la réponse');
            }
            
            // Trouver l'URL d'approbation
            $approvalUrl = null;
            foreach ($data['links'] as $link) {
                if ($link['rel'] === 'approve') {
                    $approvalUrl = $link['href'];
                    break;
                }
            }
            
            if (!$approvalUrl) {
                throw new Exception('URL d\'approbation PayPal non trouvée');
            }
            
            return [
                'success' => true,
                'order_id' => $data['id'],
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
            // Obtenir un token d'accès
            $accessToken = $this->getAccessToken();
            
            Log::info('Capture de commande PayPal', [
                'paypal_order_id' => $paypalOrderId
            ]);
            
            // Capturer la commande PayPal
            $ch = curl_init($this->baseUrl . '/v2/checkout/orders/' . $paypalOrderId . '/capture');
            
            // Désactiver la vérification SSL pour le développement
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $accessToken,
                'Prefer: return=representation'
            ]);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, '{}');
            
            $response = curl_exec($ch);
            $error = curl_error($ch);
            
            if ($error) {
                Log::error('Erreur lors de la capture de la commande PayPal', ['error' => $error]);
                throw new Exception('Erreur lors de la capture de la commande PayPal: ' . $error);
            }
            
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($httpCode < 200 || $httpCode >= 300) {
                Log::error('Erreur HTTP lors de la capture de la commande PayPal', [
                    'http_code' => $httpCode,
                    'response' => $response
                ]);
                throw new Exception('Erreur HTTP lors de la capture de la commande PayPal: ' . $httpCode . ' - ' . $response);
            }
            
            $data = json_decode($response, true);
            
            if (!isset($data['status']) || $data['status'] !== 'COMPLETED') {
                Log::error('La capture de la commande PayPal n\'a pas été complétée', ['response' => $data]);
                throw new Exception('La capture de la commande PayPal n\'a pas été complétée: ' . ($data['status'] ?? 'Statut inconnu'));
            }
            
            // Récupérer l'ID de la commande depuis custom_id
            $customId = null;
            foreach ($data['purchase_units'] as $purchaseUnit) {
                if (isset($purchaseUnit['custom_id'])) {
                    $customId = $purchaseUnit['custom_id'];
                    break;
                }
            }
            
            return [
                'success' => true,
                'payment_id' => $data['id'],
                'status' => $data['status'],
                'order_id' => $customId,
            ];
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

