<?php

namespace App\Services;

use PayPalHttp\HttpClient;
use PayPalHttp\Environment;
use PayPalHttp\HttpRequest;

class CustomPayPalHttpClient extends HttpClient
{
    public function __construct(Environment $environment)
    {
        parent::__construct($environment);
    }

    /**
     * Exécute une requête HTTP avec la vérification SSL désactivée en développement
     */
    public function execute($httpRequest)
    {
        // Si nous sommes en développement, désactiver la vérification SSL
        if (config('app.env') !== 'production') {
            $this->disableSSLVerification($httpRequest);
        }
        
        // Exécuter la requête normalement
        return parent::execute($httpRequest);
    }
    
    /**
     * Désactive la vérification SSL pour une requête HTTP
     */
    private function disableSSLVerification($httpRequest)
    {
        // Ajouter des options cURL pour désactiver la vérification SSL
        if (!isset($httpRequest->options)) {
            $httpRequest->options = [];
        }
        
        $httpRequest->options[CURLOPT_SSL_VERIFYHOST] = 0;
        $httpRequest->options[CURLOPT_SSL_VERIFYPEER] = false;
    }
}

