<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class PaymentController extends Controller
{
    public function process(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $order = Order::findOrFail($request->order_id);

        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => $order->total * 100, // Stripe utilise les centimes
                'currency' => 'eur',
                'metadata' => [
                    'order_id' => $order->id,
                ],
            ]);

            return view('payment.process', [
                'clientSecret' => $paymentIntent->client_secret,
                'order' => $order,
            ]);
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    public function success(Request $request, Order $order)
    {
        $order->update(['status' => 'paid']);
        return view('payment.success', compact('order'));
    }

    public function cancel(Request $request, Order $order)
    {
        return view('payment.cancel', compact('order'));
    }
}

