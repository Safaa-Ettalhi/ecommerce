<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductImageController extends Controller
{

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProductImage  $productImage
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductImage $productImage)
    {
        // Delete the image file
        if ($productImage->image) {
            Storage::disk('public')->delete($productImage->image);
        }

        // Delete the record
        $productImage->delete();

        return redirect()->back()->with('success', 'Image supprimée avec succès.');
    }
}

