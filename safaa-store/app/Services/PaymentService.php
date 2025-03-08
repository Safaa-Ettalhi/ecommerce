<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use Exception;

class PaymentService
{
  protected $directPayPalService;
  
  public function __construct(DirectPayPalService $directPayPalService)
  {
      $this->directPayPalService = $directPayPalService;
  }
  
  /**
   * Crée une session de paiement Stripe
   */
  public function createStripeSession(Order $order)
  {
      try {
          Stripe::setApiKey(config('services.stripe.secret'));
          
          $lineItems = [];
          
          // Vérifier si les items de la commande sont chargés
          if ($order->relationLoaded('items')) {
              foreach ($order->items as $item) {
                  $lineItems[] = [
                      'price_data' => [
                          'currency' => 'eur',
                          'product_data' => [
                              'name' => $item->product->name,
                          ],
                          'unit_amount' => round($item->price * 100), // Stripe utilise les centimes
                      ],
                      'quantity' => $item->quantity,
                  ];
              }
          } else {
              // Si les items ne sont pas chargés, créer un seul item pour le total
              $lineItems[] = [
                  'price_data' => [
                      'currency' => 'eur',
                      'product_data' => [
                          'name' => 'Commande #' . $order->order_number,
                      ],
                      'unit_amount' => round(($order->total_amount - $order->shipping_cost - $order->tax) * 100),
                  ],
                  'quantity' => 1,
              ];
          }
          
          // Ajouter les frais de livraison
          if ($order->shipping_cost > 0) {
              $lineItems[] = [
                  'price_data' => [
                      'currency' => 'eur',
                      'product_data' => [
                          'name' => 'Frais de livraison',
                      ],
                      'unit_amount' => round($order->shipping_cost * 100),
                  ],
                  'quantity' => 1,
              ];
          }
          
          // Ajouter la TVA
          if ($order->tax > 0) {
              $lineItems[] = [
                  'price_data' => [
                      'currency' => 'eur',
                      'product_data' => [
                          'name' => 'TVA (20%)',
                      ],
                      'unit_amount' => round($order->tax * 100),
                  ],
                  'quantity' => 1,
              ];
          }
          
          $session = StripeSession::create([
              'payment_method_types' => ['card'],
              'line_items' => $lineItems,
              'mode' => 'payment',
              'success_url' => route('checkout.success', ['order' => $order->id]) . '?session_id={CHECKOUT_SESSION_ID}',
              'cancel_url' => route('checkout.cancel', ['order' => $order->id]),
              'metadata' => [
                  'order_id' => (string) $order->id,
              ],
          ]);
          
          return [
              'success' => true,
              'session_id' => $session->id,
              'public_key' => config('services.stripe.key'),
          ];
      } catch (Exception $e) {
          Log::error('Erreur lors de la création de la session Stripe: ' . $e->getMessage());
          
          return [
              'success' => false,
              'message' => $e->getMessage(),
          ];
      }
  }
  
  /**
   * Vérifie le paiement Stripe
   */
  public function verifyStripePayment($sessionId)
  {
      try {
          Stripe::setApiKey(config('services.stripe.secret'));
          
          $session = StripeSession::retrieve($sessionId);
          
          if ($session->payment_status === 'paid') {
              return [
                  'success' => true,
                  'payment_id' => $session->payment_intent,
              ];
          } else {
              return [
                  'success' => false,
                  'message' => 'Le paiement n\'a pas été effectué.',
              ];
          }
      } catch (Exception $e) {
          Log::error('Erreur lors de la vérification du paiement Stripe: ' . $e->getMessage());
          
          return [
              'success' => false,
              'message' => $e->getMessage(),
          ];
      }
  }
  
  /**
   * Crée une commande PayPal
   */
  public function createPayPalOrder(Order $order)
  {
      try {
          return $this->directPayPalService->createOrder($order);
      } catch (Exception $e) {
          Log::error('Erreur lors de la création de la commande PayPal: ' . $e->getMessage());
          
          return [
              'success' => false,
              'message' => $e->getMessage(),
          ];
      }
  }
  
  /**
   * Capture le paiement PayPal
   */
  public function capturePayPalPayment($paypalOrderId)
  {
      try {
          return $this->directPayPalService->captureOrder($paypalOrderId);
      } catch (Exception $e) {
          Log::error('Erreur lors de la capture du paiement PayPal: ' . $e->getMessage());
          
          return [
              'success' => false,
              'message' => $e->getMessage(),
          ];
      }
  }
}

