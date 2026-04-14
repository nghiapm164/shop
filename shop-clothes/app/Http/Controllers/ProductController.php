<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Schema;
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
        // Load product relationships needed for detail page
        $product->load([
            'category',
            'brand',
            'images',
            'variants.color',
            'variants.size',
            'reviews.user',
        ])->loadCount('reviews');
        
        // Increment view count for popularity tracking
        if ($product->exists && Schema::hasColumn('products', 'view_count')) {
            $product->increment('view_count');
        }

        // Get related products from same category (excluding current product)
        $related = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->with(['category', 'images', 'variants.color'])
            ->withCount('reviews')
            ->limit(4)
            ->get();

        return view('products.show', [
            'product' => $product,
            'related' => $related,
        ]);
    }
}
