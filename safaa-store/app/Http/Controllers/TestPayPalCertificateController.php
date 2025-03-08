<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TestPayPalCertificateController extends Controller
{
    public function testCertificate()
    {
        $results = [];
        
        // Vérifier si cURL est installé
        $results['curl_installed'] = function_exists('curl_version');
        
        // Vérifier la version de cURL
        if ($results['curl_installed']) {
            $curlVersion = curl_version();
            $results['curl_version'] = $curlVersion['version'];
            $results['ssl_version'] = $curlVersion['ssl_version'];
        }
        
        // Vérifier si OpenSSL est installé
        $results['openssl_installed'] = extension_loaded('openssl');
        
        // Vérifier les paramètres SSL dans php.ini
        $results['curl_cainfo'] = ini_get('curl.cainfo');
        $results['openssl_cafile'] = ini_get('openssl.cafile');
        
        // Tester une connexion à PayPal
        $ch = curl_init('https://api-m.sandbox.paypal.com/v1/oauth2/token');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        
        // Capturer les informations de débogage
        $verbose = fopen('php://temp', 'w+');
        curl_setopt($ch, CURLOPT_STDERR, $verbose);
        
        // Exécuter la requête avec vérification SSL activée
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        $response = curl_exec($ch);
        $error = curl_error($ch);
        
        $results['ssl_verification_enabled'] = [
            'success' => ($response !== false),
            'error' => $error
        ];
        
        // Exécuter la requête avec vérification SSL désactivée
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        $response = curl_exec($ch);
        $error = curl_error($ch);
        
        $results['ssl_verification_disabled'] = [
            'success' => ($response !== false),
            'error' => $error
        ];
        
        // Récupérer les informations de débogage
        rewind($verbose);
        $verboseLog = stream_get_contents($verbose);
        
        curl_close($ch);
        
        $results['verbose_log'] = $verboseLog;
        
        // Vérifier si le certificat CA est accessible
        $certPath = '/etc/ssl/certs/ca-certificates.crt'; // Chemin commun sur Linux
        $results['ca_cert_exists'] = file_exists($certPath);
        
        // Journaliser les résultats
        Log::info('Test des certificats PayPal', $results);
        
        return response()->json($results);
    }
}

