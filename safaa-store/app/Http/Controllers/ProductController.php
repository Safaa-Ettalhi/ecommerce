<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::where('is_active', true);

        // Apply category filter
        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }

        // Apply price filter
        if ($request->has('min_price') && $request->has('max_price')) {
            $query->whereBetween('price', [$request->min_price, $request->max_price]);
        }

        // Apply sorting
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $products = $query->paginate(12);
        $categories = Category::where('is_active', true)->get();
        $featuredProducts = Product::where('featured', true)
            ->latest()
            ->limit(4);

        return view('products.index', compact('products', 'categories','featuredProducts'));
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->take(4)
            ->get();

        $reviews = Review::where('product_id', $product->id)
            ->where('is_approved', true)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('products.show', compact('product', 'relatedProducts', 'reviews'));
    }

    public function storeReview(Request $request, $productId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $review = new Review([
            'user_id' => Auth::id(),
            'product_id' => $productId,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_approved' => false,
        ]);

        $review->save();

        return redirect()->back()->with('success', 'Your review has been submitted and is pending approval.');
    }
    /**
     * Affiche la page de gestion des images du produit.
     */
    public function manageImages(Product $product)
    {
        $product->load('images');
        return view('admin.products.images', compact('product'));
    }

    /**
     * Met à jour l'image principale du produit.
     */
    public function updateMainImage(Request $request, Product $product)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            // Supprimer l'ancienne image
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            $imagePath = $request->file('image')->store('products', 'public');
            $product->image = $imagePath;
            $product->save();

            return redirect()->route('admin.products.images', $product)->with('success', 'Image principale mise à jour avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue: ' . $e->getMessage());
        }
    }

    /**
     * Ajoute des images supplémentaires au produit.
     */
    public function addImages(Request $request, Product $product)
    {
        $request->validate([
            'additional_images' => 'required|array',
            'additional_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            foreach ($request->file('additional_images') as $image) {
                $imagePath = $image->store('products', 'public');

                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $imagePath,
                    'is_primary' => false,
                ]);
            }

            return redirect()->route('admin.products.images', $product)->with('success', 'Images ajoutées avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue: ' . $e->getMessage());
        }
    }
}
