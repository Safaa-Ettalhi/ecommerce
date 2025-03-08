<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{

    public function index()
    {
        $products = Product::with('category')->paginate(10);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();

        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'additional_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Créer le produit avec le slug explicitement défini
        $product = new Product();
        $product->name = $request->name;
        $product->slug = Str::slug($request->name); // Génération explicite du slug
        $product->description = $request->description;
        $product->price = $request->price;
        $product->quantity = $request->quantity;
        $product->category_id = $request->category_id;
        $product->is_active = $request->has('is_active') ? 1 : 0;

        if ($request->hasFile('image')) {
            $product->image = $request->file('image')->store('products', 'public');
        }

        $product->save();

        // Gérer les images supplémentaires
        if ($request->hasFile('additional_images')) {
            foreach ($request->file('additional_images') as $image) {
                $imagePath = $image->store('products', 'public');

                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $imagePath,
                    'is_primary' => false,
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Produit créé avec succès.');
    }

    public function edit(Product $product)
    {
        $product->load('images');
        $categories = Category::all();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        // Journaliser la requête pour le débogage
        Log::info('Mise à jour du produit', [
            'product_id' => $product->id,
            'request_data' => $request->all()
        ]);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'additional_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            // Modification ici: utiliser fill() au lieu d'assigner chaque champ
            $product->fill([
                'name' => $request->input('name'),
                'slug' => Str::slug($request->input('name')),
                'description' => $request->input('description'),
                'price' => $request->input('price'),
                'quantity' => $request->input('quantity'),
                'category_id' => $request->input('category_id'),
                'is_active' => $request->has('is_active') ? 1 : 0,
            ]);

            if ($request->hasFile('image')) {
                // Supprimer l'ancienne image
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }

                $imagePath = $request->file('image')->store('products', 'public');
                $product->image = $imagePath;
            }

            $product->save();

            if ($request->hasFile('additional_images')) {
                foreach ($request->file('additional_images') as $image) {
                    $imagePath = $image->store('products', 'public');

                    ProductImage::create([
                        'product_id' => $product->id,
                        'image' => $imagePath,
                        'is_primary' => false,
                    ]);
                }
            }

            Log::info('Produit mis à jour avec succès', ['product_id' => $product->id]);

            return redirect()->route('admin.products.index')->with('success', 'Produit mis à jour avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour du produit', [
                'product_id' => $product->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'Une erreur est survenue lors de la mise à jour du produit: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Product $product)
    {
        // Supprimer les images du produit
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image);
            $image->delete();
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Produit supprimé avec succès.');
    }
}

