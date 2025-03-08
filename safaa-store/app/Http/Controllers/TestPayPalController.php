<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Services\DirectPayPalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TestPayPalController extends Controller
{
    protected $directPayPalService;
    
    public function __construct(DirectPayPalService $directPayPalService)
    {
        $this->directPayPalService = $directPayPalService;
    }
    
    public function testConnection()
    {
        $results = [];
        
        // Test avec cURL natif
        $ch = curl_init('https://api-m.sandbox.paypal.com/v1/oauth2/token');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        
        // Ajouter les en-têtes d'authentification pour PayPal
        $clientId = config('services.paypal.client_id');
        $clientSecret = config('services.paypal.secret');
        
        if (!empty($clientId) && !empty($clientSecret)) {
            curl_setopt($ch, CURLOPT_USERPWD, $clientId . ":" . $clientSecret);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json', 'Accept-Language: en_US']);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
        }
        
        // Capturer les informations de débogage
        $verbose = fopen('php://temp', 'w+');
        curl_setopt($ch, CURLOPT_STDERR, $verbose);
        
        $response = curl_exec($ch);
        $error = curl_error($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);
        
        // Récupérer les informations de débogage
        rewind($verbose);
        $verboseLog = stream_get_contents($verbose);
        
        $results['curl_test'] = [
            'success' => ($response !== false),
            'error' => $error,
            'info' => $info,
            'response' => $response ? json_decode($response, true) : null,
            'verbose_log' => $verboseLog
        ];
        
        // Test avec le service PayPal direct
        try {
            // Obtenir un token d'accès
            $accessToken = $this->directPayPalService->getAccessToken();
            
            $results['direct_paypal_token_test'] = [
                'success' => true,
                'token' => substr($accessToken, 0, 10) . '...' // Ne pas afficher le token complet
            ];
            
            // Créer une commande de test
            $order = Order::first();
            
            if (!$order) {
                $order = new Order();
                $order->user_id = Auth::id() ?? 1;
                $order->order_number = 'TEST-' . rand(1000, 9999);
                $order->total_amount = 99.99;
                $order->shipping_cost = 5.00;
                $order->tax = 20.00;
                $order->status = 'pending';
                $order->payment_status = 'pending';
                $order->payment_method = 'paypal';
                $order->shipping_address = 'Adresse de test';
                $order->billing_address = 'Adresse de test';
                $order->shipping_method = 'standard';
                $order->save();
            }
            
            $result = $this->directPayPalService->createOrder($order);
            
            $results['direct_paypal_order_test'] = [
                'success' => $result['success'],
                'message' => $result['success'] ? 'Commande PayPal créée avec succès' : $result['message'],
                'data' => $result['success'] ? [
                    'order_id' => $result['order_id'],
                    'approval_url' => $result['approval_url']
                ] : null
            ];
        } catch (\Exception $e) {
            $results['direct_paypal_test'] = [
                'success' => false,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ];
        }
        
        // Journaliser les résultats
        Log::info('Test de connexion PayPal', $results);
        
        return response()->json($results);
    }
}

