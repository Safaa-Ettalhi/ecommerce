<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\DirectPayPalService;
use App\Services\PaymentService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Désactiver la vérification SSL pour le développement uniquement
        if ($this->app->environment('local', 'development', 'testing')) {
            $this->disableSSLVerification();
        }
        
        // Enregistrer le service DirectPayPalService
        $this->app->singleton(DirectPayPalService::class, function ($app) {
            return new DirectPayPalService();
        });
        
        // Enregistrer le service PaymentService avec DirectPayPalService
        $this->app->singleton(PaymentService::class, function ($app) {
            return new PaymentService($app->make(DirectPayPalService::class));
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
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
    }
}

