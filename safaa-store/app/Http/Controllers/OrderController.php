<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Middleware\AdminMiddleware;

class OrderController extends Controller
{

    
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('orders.index', compact('orders'));
    }
    
    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
            abort(403);
        }
        
        return view('orders.show', compact('order'));
    }
    
    public function generateInvoice(Order $order)
    {
        if ($order->user_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
            abort(403);
        }
        
        $pdf = PDF::loadView('orders.invoice', compact('order'));
        
        return $pdf->download('invoice-' . $order->order_number . '.pdf');
    }
}