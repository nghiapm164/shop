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
        if ($product->exists) {
            $product->increment('view_count');
        }

        // Track recently viewed products in session
        $this->trackRecentlyViewed($product->id);

        // Get related products from same category (excluding current product)
        $related = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->with(['category', 'images', 'variants.color'])
            ->withCount('reviews')
            ->limit(4)
            ->get();

        // Get recently viewed products (excluding current)
        $recentlyViewed = $this->getRecentlyViewed($product->id);

        return view('products.show', [
            'product' => $product,
            'related' => $related,
            'recentlyViewed' => $recentlyViewed,
        ]);
    }

    /**
     * Track a product in the recently viewed session list.
     */
    private function trackRecentlyViewed(int $productId): void
    {
        $recentlyViewed = session()->get('recently_viewed', []);

        // Remove if already exists (to move to front)
        $recentlyViewed = array_filter($recentlyViewed, fn($id) => $id !== $productId);

        // Add to the beginning
        array_unshift($recentlyViewed, $productId);

        // Keep only last 10 items
        $recentlyViewed = array_slice($recentlyViewed, 0, 10);

        session()->put('recently_viewed', $recentlyViewed);
    }

    /**
     * Get recently viewed products, excluding a specific product.
     */
    private function getRecentlyViewed(?int $excludeId = null): \Illuminate\Support\Collection
    {
        $recentlyViewed = session()->get('recently_viewed', []);

        if (empty($recentlyViewed)) {
            return collect([]);
        }

        $query = Product::where('is_active', true)
            ->whereIn('id', $recentlyViewed)
            ->with(['category', 'images', 'variants.color'])
            ->withCount('reviews');

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        $products = $query->get();

        // Sort by the order in session
        return $products->sortBy(fn($product) => array_search($product->id, $recentlyViewed))->values()->take(8);
    }
}
