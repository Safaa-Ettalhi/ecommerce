<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    
    public function index()
    {
        $reviews = Review::with(['user', 'product'])->orderBy('created_at', 'desc')->paginate(10);
        
        return view('admin.reviews.index', compact('reviews'));
    }
    
    public function show(Review $review)
    {
        $review->load(['user', 'product']);
        
        return view('admin.reviews.show', compact('review'));
    }
    
    public function approve(Review $review)
    {
        $review->is_approved = true;
        $review->save();
        
        return redirect()->route('admin.reviews.index')->with('success', 'Avis approuvé avec succès.');
    }
    
    public function destroy(Review $review)
    {
        $review->delete();
        
        return redirect()->route('admin.reviews.index')->with('success', 'Avis supprimé avec succès.');
    }
}

