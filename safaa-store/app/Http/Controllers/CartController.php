<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        $cart = $this->cartService->getCart();
        $total = $this->cartService->getCartTotal();
        $shipping = 5.00;

        return view('cart.index', compact('cart', 'total','shipping'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $this->cartService->addToCart($request->product_id, $request->quantity);

        return redirect()->back()->with('success', 'Product added to cart successfully.');
    }

    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:0',
        ]);

        $this->cartService->updateCart($request->product_id, $request->quantity);

        return redirect()->back()->with('success', 'Cart updated successfully.');
    }

    public function remove($productId)
    {
        $this->cartService->removeFromCart($productId);

        return redirect()->back()->with('success', 'Product removed from cart successfully.');
    }

    public function clear()
    {
        $this->cartService->clearCart();

        return redirect()->back()->with('success', 'Cart cleared successfully.');
    }
}
