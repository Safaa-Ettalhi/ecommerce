<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();
            
        $categories = Category::where('is_active', true)->get();
        
        return view('home', compact('featuredProducts', 'categories'));
    }
}