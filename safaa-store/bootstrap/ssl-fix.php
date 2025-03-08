<?php

// Désactiver la vérification SSL pour le développement uniquement
if (env('APP_ENV') !== 'production') {
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
    
    // Créer une fonction d'encapsulation pour curl_init
    if (!function_exists('curl_init_with_ssl_fix')) {
        function curl_init_with_ssl_fix($url = null) {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            return $ch;
        }
    }
}

