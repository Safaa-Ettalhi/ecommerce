<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\SimplePaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SimpleCheckoutController extends Controller
{
    protected $paymentService;

    public function __construct(SimplePaymentService $paymentService)
    {
        
        $this->paymentService = $paymentService;
    }

    public function index()
    {
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Votre panier est vide.');
        }
        
        $cartItems = [];
        $total = 0;
        
        foreach ($cart as $id => $details) {
            $product = Product::find($id);
            if ($product) {
                $cartItems[] = [
                    'product' => $product,
                    'quantity' => $details['quantity'],
                    'price' => $product->price,
                    'total' => $product->price * $details['quantity'],
                ];
                
                $total += $product->price * $details['quantity'];
            }
        }
        
        // Calcul des frais de livraison et taxes
        $shippingCost = 5.00; // Frais de livraison fixes (à adapter selon votre logique)
        $tax = $total * 0.20; // TVA à 20% (à adapter selon votre logique)
        $grandTotal = $total + $shippingCost + $tax;
        
        return view('checkout.index', compact('cartItems', 'total', 'shippingCost', 'tax', 'grandTotal'));
    }

    public function process(Request $request)
    {
        // Débogage
        Log::info('Début du processus de paiement', [
            'payment_method' => $request->payment_method,
            'all_data' => $request->all()
        ]);
        
        $request->validate([
            'payment_method' => 'required|in:stripe',
            'shipping_address' => 'required|string',
            'billing_address' => 'required|string',
        ]);
        
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Votre panier est vide.');
        }
        
        try {
            DB::beginTransaction();
            
            // Créer la commande
            $order = $this->createOrder($request);
            
            // Créer les éléments de la commande
            $this->createOrderItems($order, $cart);
            
            // Mettre à jour les stocks
            $this->updateProductStock($cart);
            
            DB::commit();
            
            // Traiter le paiement avec Stripe uniquement
            if ($request->payment_method === 'stripe') {
                $result = $this->paymentService->createStripeSession($order);
                
                if ($result['success']) {
                    return view('checkout.stripe', [
                        'sessionId' => $result['session_id'],
                        'stripeKey' => $result['public_key'],
                    ]);
                } else {
                    return redirect()->route('checkout.index')->with('error', 'Erreur lors de la création de la session de paiement: ' . $result['message']);
                }
            }
            
            return redirect()->route('checkout.index')->with('error', 'Seul le paiement par Stripe est pris en charge pour le moment.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors du traitement de la commande', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('checkout.index')->with('error', 'Une erreur est survenue lors du traitement de votre commande: ' . $e->getMessage());
        }
    }

    public function success(Request $request, Order $order)
    {
        // Mettre à jour la commande
        $order->payment_status = 'paid';
        $order->status = 'processing';
        $order->payment_method = 'stripe';
        $order->payment_id = $request->session_id;
        $order->save();
        
        // Vider le panier
        session()->forget('cart');
        
        return view('checkout.success', compact('order'));
    }

    public function cancel(Order $order)
    {
        // Marquer la commande comme annulée
        $order->status = 'cancelled';
        $order->save();
        
        return view('checkout.cancel', compact('order'));
    }

    protected function createOrder(Request $request)
    {
        $cart = session()->get('cart', []);
        $total = 0;
        
        foreach ($cart as $id => $details) {
            $product = Product::find($id);
            if ($product) {
                $total += $product->price * $details['quantity'];
            }
        }
        
        // Calcul des frais de livraison et taxes
        $shippingCost = 5.00; // Frais de livraison fixes (à adapter selon votre logique)
        $tax = $total * 0.20; // TVA à 20% (à adapter selon votre logique)
        $grandTotal = $total + $shippingCost + $tax;
        
        return Order::create([
            'user_id' => Auth::id(),
            'order_number' => 'ORD-' . strtoupper(Str::random(10)),
            'total_amount' => $grandTotal,
            'status' => 'pending',
            'payment_status' => 'pending',
            'payment_method' => $request->payment_method,
            'shipping_address' => $request->shipping_address,
            'billing_address' => $request->billing_address,
            'shipping_method' => 'standard',
            'shipping_cost' => $shippingCost,
            'tax' => $tax,
            'notes' => $request->notes ?? null,
        ]);
    }

    protected function createOrderItems(Order $order, array $cart)
    {
        foreach ($cart as $id => $details) {
            $product = Product::find($id);
            if ($product) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $details['quantity'],
                    'price' => $product->price,
                    'total' => $product->price * $details['quantity'],
                ]);
            }
        }
    }

    protected function updateProductStock(array $cart)
    {
        foreach ($cart as $id => $details) {
            $product = Product::find($id);
            if ($product) {
                $product->quantity -= $details['quantity'];
                $product->save();
            }
        }
    }
}

