<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Event;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;
use Stripe\Stripe;

class WebhookController extends Controller
{
    public function handleStripeWebhook(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));
        $endpoint_secret = config('services.stripe.webhook.secret');

        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');

        try {
            $event = Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch (SignatureVerificationException $e) {
            Log::error('Webhook signature verification failed: ' . $e->getMessage());
            return response()->json(['error' => 'Webhook signature verification failed.'], 400);
        }

        // Handle the event
        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object;
                $this->handleCheckoutSessionCompleted($session);
                break;
            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object;
                $this->handlePaymentIntentSucceeded($paymentIntent);
                break;
            case 'payment_intent.payment_failed':
                $paymentIntent = $event->data->object;
                $this->handlePaymentIntentFailed($paymentIntent);
                break;
            default:
                Log::info('Unhandled event type: ' . $event->type);
        }

        return response()->json(['status' => 'success']);
    }

    protected function handleCheckoutSessionCompleted($session)
    {
        if (!isset($session->metadata->order_id)) {
            Log::error('No order_id in session metadata');
            return;
        }

        $order = Order::find($session->metadata->order_id);
        if (!$order) {
            Log::error('Order not found: ' . $session->metadata->order_id);
            return;
        }

        if ($session->payment_status === 'paid') {
            $order->payment_status = 'paid';
            $order->status = 'processing';
            $order->payment_id = $session->payment_intent;
            $order->save();

            Log::info('Order payment completed: ' . $order->id);
        }
    }

    protected function handlePaymentIntentSucceeded($paymentIntent)
    {
        // Vous pouvez ajouter une logique supplémentaire ici si nécessaire
        Log::info('Payment intent succeeded: ' . $paymentIntent->id);
    }

    protected function handlePaymentIntentFailed($paymentIntent)
    {
        // Rechercher la commande associée à ce paiement
        $order = Order::where('payment_id', $paymentIntent->id)->first();
        if ($order) {
            $order->payment_status = 'failed';
            $order->save();

            Log::info('Order payment failed: ' . $order->id);
        }
    }
}

