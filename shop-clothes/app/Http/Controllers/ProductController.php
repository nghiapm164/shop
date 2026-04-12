<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display the products listing page with Livewire filtering
     */
    public function index(): View
    {
        // All filtering is handled by Livewire ProductFilter component
        // This controller just returns the page
        return view('products.index');
    }

    /**
     * Display a single product with details and related products
     */
    public function show(Product $product): View
    {
        // Load product relationships
        $product->load(['category', 'colors', 'sizes', 'reviews.user', 'images']);
        
        // Increment view count for popularity tracking
        if ($product->exists) {
            $product->increment('view_count');
        }

        // Get related products from same category (excluding current product)
        $related = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->with('colors', 'reviews')
            ->limit(4)
            ->get();

        return view('products.show', [
            'product' => $product,
            'related' => $related,
        ]);
    }
}
