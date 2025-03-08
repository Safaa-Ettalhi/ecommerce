<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
require_once __DIR__ . '/ssl-fix.php';
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
}
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
