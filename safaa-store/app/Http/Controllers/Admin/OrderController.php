<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    
    public function index()
    {
        $orders = Order::with('user')->orderBy('created_at', 'desc')->paginate(10);
        
        return view('admin.orders.index', compact('orders'));
    }
    
    public function show(Order $order)
    {
        $order->load(['user', 'items.product']);
        
        return view('admin.orders.show', compact('order'));
    }
    
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
            'payment_status' => 'required|in:pending,paid,failed,refunded',
        ]);
        
        $order->status = $request->status;
        $order->payment_status = $request->payment_status;
        
        // Si le paiement est à la livraison et que le statut est "delivered", marquer le paiement comme "paid"
        if ($order->payment_method === 'cod' && $request->status === 'delivered' && $order->payment_status === 'pending') {
            $order->payment_status = 'paid';
        }
        
        $order->save();
        
        return redirect()->route('admin.orders.show', $order)->with('success', 'Commande mise à jour avec succès.');
    }
    
    public function generateInvoice(Order $order)
    {
        $order->load(['user', 'items.product']);
        
        $pdf = PDF::loadView('admin.orders.invoice', compact('order'));
        
        return $pdf->download('facture-' . $order->order_number . '.pdf');
    }
}

