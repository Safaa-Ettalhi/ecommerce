<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$paypalClientId = config('services.paypal.client_id');
$paypalSecret = config('services.paypal.secret');
$paypalMode = config('services.paypal.mode', 'sandbox');

echo "Client ID PayPal: " . $paypalClientId . "\n";
echo "Secret PayPal: " . $paypalSecret . "\n";
echo "Mode PayPal: " . $paypalMode . "\n";

// Vérifier si les identifiants sont configurés
if (empty($paypalClientId) || empty($paypalSecret) || $paypalClientId === 'votre_client_id_paypal' || $paypalSecret === 'votre_secret_paypal') {
    echo "ERREUR: PayPal n'est pas correctement configuré. Veuillez vérifier vos identifiants.\n";
    exit(1);
}

try {
    // Créer l'environnement approprié
    $environment = $paypalMode === 'production'
        ? new \PayPalCheckoutSdk\Core\ProductionEnvironment($paypalClientId, $paypalSecret)
        : new \PayPalCheckoutSdk\Core\SandboxEnvironment($paypalClientId, $paypalSecret);
        
    // Créer le client PayPal
    $client = new \PayPalCheckoutSdk\Core\PayPalHttpClient($environment);
    
    // Tester une requête simple
    $request = new \PayPalCheckoutSdk\Orders\OrdersCreateRequest();
    $request->prefer('return=representation');
    $request->body = [
        'intent' => 'CAPTURE',
        'purchase_units' => [
            [
                'reference_id' => 'test_ref_' . uniqid(),
                'description' => 'Test PayPal',
                'amount' => [
                    'currency_code' => 'EUR',
                    'value' => '1.00',
                ],
            ],
        ],
        'application_context' => [
            'return_url' => 'https://example.com/success',
            'cancel_url' => 'https://example.com/cancel',
        ],
    ];
    
    $response = $client->execute($request);
    
    echo "Connexion à PayPal réussie! ID de commande: " . $response->result->id . "\n";
    
    // Afficher l'URL d'approbation
    foreach ($response->result->links as $link) {
        if ($link->rel === 'approve') {
            echo "URL d'approbation: " . $link->href . "\n";
            break;
        }
    }
} catch (\Exception $e) {
    echo "Erreur de connexion à PayPal: " . $e->getMessage() . "\n";
    exit(1);
}

echo "Test PayPal terminé avec succès!\n";