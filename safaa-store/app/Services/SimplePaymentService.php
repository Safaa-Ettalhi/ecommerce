<?php

namespace App\Services;

use App\Models\Order;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use Exception;
use Illuminate\Support\Facades\Log;

class SimplePaymentService
{
    protected $stripeKey;
    protected $stripeSecret;

    public function __construct()
    {
        $this->stripeKey = config('services.stripe.key');
        $this->stripeSecret = config('services.stripe.secret');
    }

    /**
     * Créer une session de paiement Stripe
     */
    public function createStripeSession(Order $order)
    {
        try {
            Log::info('Début de création de session Stripe', [
                'order_id' => $order->id,
                'stripe_key' => $this->stripeKey ? 'configuré' : 'non configuré',
                'stripe_secret' => $this->stripeSecret ? 'configuré' : 'non configuré'
            ]);

            Stripe::setApiKey($this->stripeSecret);

            $session = StripeSession::create([
                'payment_method_types' => ['card'],
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => 'eur',
                            'product_data' => [
                                'name' => 'Commande #' . $order->order_number,
                            ],
                            'unit_amount' => (int)($order->total_amount * 100), // Stripe utilise les centimes
                        ],
                        'quantity' => 1,
                    ],
                ],
                'mode' => 'payment',
                'success_url' => route('checkout.success', ['order' => $order->id, 'payment_method' => 'stripe']) . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('checkout.cancel', ['order' => $order->id]),
                'metadata' => [
                    'order_id' => $order->id,
                ],
            ]);

            Log::info('Session Stripe créée avec succès', [
                'session_id' => $session->id
            ]);

            return [
                'success' => true,
                'session_id' => $session->id,
                'public_key' => $this->stripeKey,
            ];
        } catch (Exception $e) {
            Log::error('Erreur lors de la création de la session Stripe', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}

