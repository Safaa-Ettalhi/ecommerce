<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
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
        $request->validate([
            'payment_method' => 'required|in:stripe,paypal,cod',
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
            
            // Traiter le paiement selon la méthode choisie
            if ($request->payment_method === 'stripe') {
                $result = $this->paymentService->createStripeSession($order);
                
                if ($result['success']) {
                    return view('checkout.stripe', [
                        'sessionId' => $result['session_id'],
                        'stripeKey' => $result['public_key'],
                        'order' => $order,
                    ]);
                } else {
                    return redirect()->route('checkout.index')->with('error', 'Erreur lors de la création de la session de paiement: ' . $result['message']);
                }
            } elseif ($request->payment_method === 'paypal') {
                $result = $this->paymentService->createPayPalOrder($order);
                
                if ($result['success'] && isset($result['approval_url'])) {
                    return redirect()->away($result['approval_url']);
                } else {
                    return redirect()->route('checkout.index')->with('error', 'Erreur lors de la création de la commande PayPal: ' . ($result['message'] ?? 'Erreur inconnue'));
                }
            } elseif ($request->payment_method === 'cod') {
                // Mettre à jour le statut de la commande pour le paiement à la livraison
                $this->updateOrderAfterCodPayment($order);
                
                // Vider le panier
                session()->forget('cart');
                
                // Rediriger vers la page de confirmation pour le paiement à la livraison
                return redirect()->route('checkout.cod-success', ['order' => $order->id]);
            }
            
            return redirect()->route('checkout.index')->with('error', 'Méthode de paiement non prise en charge.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors du traitement de la commande: ' . $e->getMessage());
            return redirect()->route('checkout.index')->with('error', 'Une erreur est survenue lors du traitement de votre commande: ' . $e->getMessage());
        }
    }

    public function success(Request $request, Order $order)
    {
        // Récupérer la méthode de paiement depuis l'ordre plutôt que de la requête
        $paymentMethod = $order->payment_method;
        
        // Vérifier que l'utilisateur est bien le propriétaire de la commande
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
        
        if ($paymentMethod === 'stripe') {
            $sessionId = $request->query('session_id');
            if (!$sessionId) {
                // Si l'ID de session n'est pas présent, vérifier si le paiement a déjà été traité
                if ($order->payment_status === 'paid') {
                    session()->forget('cart');
                    return view('checkout.success', compact('order'));
                }
                
                // Sinon, rediriger vers la page d'accueil avec un message d'erreur
                return redirect()->route('checkout.index')->with('error', 'ID de session manquant');
            }
            
            $result = $this->paymentService->verifyStripePayment($sessionId);
            
            if ($result['success']) {
                $this->updateOrderAfterPayment($order, 'stripe', $result['payment_id']);
                session()->forget('cart');
                return view('checkout.success', compact('order'));
            } else {
                return redirect()->route('checkout.index')->with('error', 'Erreur lors de la vérification du paiement: ' . $result['message']);
            }
        } elseif ($paymentMethod === 'paypal') {
            $paypalOrderId = $request->query('token');
            if (!$paypalOrderId) {
                // Si le token PayPal n'est pas présent, vérifier si le paiement a déjà été traité
                if ($order->payment_status === 'paid') {
                    session()->forget('cart');
                    return view('checkout.success', compact('order'));
                }
                
                // Sinon, rediriger vers la page d'accueil avec un message d'erreur
                return redirect()->route('checkout.index')->with('error', 'Token PayPal manquant');
            }
            
            $result = $this->paymentService->capturePayPalPayment($paypalOrderId);
            
            if ($result['success']) {
                $this->updateOrderAfterPayment($order, 'paypal', $result['payment_id']);
                session()->forget('cart');
                return view('checkout.success', compact('order'));
            } else {
                return redirect()->route('checkout.index')->with('error', 'Erreur lors de la capture du paiement: ' . $result['message']);
            }
        } elseif ($paymentMethod === 'cod') {
            // Rediriger vers la page de confirmation pour le paiement à la livraison
            return redirect()->route('checkout.cod-success', ['order' => $order->id]);
        }
        
        // Si la méthode de paiement n'est pas reconnue, journaliser l'erreur
        Log::error('Méthode de paiement non reconnue', [
            'order_id' => $order->id,
            'payment_method' => $paymentMethod
        ]);
        
        return redirect()->route('checkout.index')->with('error', 'Méthode de paiement non prise en charge.');
    }

    public function codSuccess(Order $order)
    {
        // Vérifier que l'utilisateur est bien le propriétaire de la commande
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
        
        // Vérifier que la méthode de paiement est bien "cod"
        if ($order->payment_method !== 'cod') {
            return redirect()->route('checkout.index')->with('error', 'Méthode de paiement incorrecte');
        }
        
        return view('checkout.cod-success', compact('order'));
    }

    public function cancel(Order $order)
    {
        // Vérifier que l'utilisateur est bien le propriétaire de la commande
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
        
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

    protected function updateOrderAfterPayment(Order $order, string $paymentMethod, string $paymentId)
    {
        $order->payment_status = 'paid';
        $order->status = 'processing';
        $order->payment_method = $paymentMethod;
        $order->payment_id = $paymentId;
        $order->save();
    }

    protected function updateOrderAfterCodPayment(Order $order)
    {
        $order->payment_status = 'pending';
        $order->status = 'processing';
        $order->payment_method = 'cod';
        $order->save();
    }
}

