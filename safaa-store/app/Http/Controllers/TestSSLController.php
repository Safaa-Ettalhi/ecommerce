<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TestSSLController extends Controller
{
    /**
     * Test de connexion SSL à PayPal
     */
    public function testPayPalSSL()
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
        
        // Vérifier les paramètres SSL dans PHP
        $results['php_ssl_info'] = [
            'openssl_version' => OPENSSL_VERSION_TEXT,
            'curl_version' => curl_version(),
            'curl_cainfo' => ini_get('curl.cainfo'),
            'openssl_cafile' => ini_get('openssl.cafile'),
            'allow_url_fopen' => ini_get('allow_url_fopen'),
            'stream_default_context' => stream_context_get_options(stream_context_get_default())
        ];
        
        // Journaliser les résultats
        Log::info('Test SSL PayPal', $results);
        
        return response()->json($results);
    }
}

