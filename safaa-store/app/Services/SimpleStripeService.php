<?php

namespace App\Services;

use App\Models\Order;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use Exception;
use Illuminate\Support\Facades\Log;

class SimpleStripeService
{
    protected $stripeKey;
    protected $stripeSecret;

    public function __construct()
    {
        $this->stripeKey = config('services.stripe.key');
        $this->stripeSecret = config('services.stripe.secret');
    }

    /**
     * Créer une session de paiement Stripe simplifiée
     */
    public function createCheckoutSession(Order $order)
    {
        try {
            Log::info('Début de création de session Stripe', [
                'order_id' => $order->id,
                'stripe_key' => $this->stripeKey ? 'configuré' : 'non configuré',
                'stripe_secret' => $this->stripeSecret ? 'configuré' : 'non configuré'
            ]);

            Stripe::setApiKey($this->stripeSecret);

            // Créer une session de paiement simplifiée avec un seul élément
            $session = StripeSession::create([
                'payment_method_types' => ['card'],
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => 'eur',
                            'product_data' => [
                                'name' => 'Commande #' . $order->order_number,
                                'description' => 'Paiement pour la commande #' . $order->order_number,
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

    /**
     * Vérifier le statut d'un paiement Stripe
     */
    public function verifyPayment($sessionId)
    {
        try {
            Log::info('Vérification du paiement Stripe', [
                'session_id' => $sessionId
            ]);

            Stripe::setApiKey($this->stripeSecret);
            $session = StripeSession::retrieve($sessionId);

            if ($session->payment_status === 'paid') {
                Log::info('Paiement Stripe vérifié avec succès', [
                    'payment_status' => $session->payment_status,
                    'payment_intent' => $session->payment_intent
                ]);

                return [
                    'success' => true,
                    'payment_id' => $session->payment_intent,
                ];
            }

            Log::warning('Paiement Stripe non complété', [
                'payment_status' => $session->payment_status
            ]);

            return [
                'success' => false,
                'message' => 'Le paiement n\'a pas été effectué.',
            ];
        } catch (Exception $e) {
            Log::error('Erreur lors de la vérification du paiement Stripe', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Créer un remboursement Stripe
     */
    public function createRefund($paymentIntentId, $amount = null)
    {
        try {
            Log::info('Création d\'un remboursement Stripe', [
                'payment_intent_id' => $paymentIntentId,
                'amount' => $amount
            ]);

            Stripe::setApiKey($this->stripeSecret);
            
            $refundParams = [
                'payment_intent' => $paymentIntentId,
            ];
            
            // Si un montant est spécifié, l'ajouter aux paramètres
            if ($amount !== null) {
                $refundParams['amount'] = (int)($amount * 100); // Convertir en centimes
            }
            
            $refund = \Stripe\Refund::create($refundParams);

            Log::info('Remboursement Stripe créé avec succès', [
                'refund_id' => $refund->id,
                'status' => $refund->status
            ]);

            return [
                'success' => true,
                'refund_id' => $refund->id,
                'status' => $refund->status,
            ];
        } catch (Exception $e) {
            Log::error('Erreur lors de la création du remboursement Stripe', [
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