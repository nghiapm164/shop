<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Banner;
use App\Models\Brand;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display the homepage with featured products, banners, and testimonials
     */
    public function __invoke(): View
    {
        // Latest products (8 items) - ordered by creation date
        $newProducts = Product::where('is_active', true)
            ->with('category', 'colors', 'reviews')
            ->latest('created_at')
            ->limit(8)
            ->get();

        // Best sellers (8 items) - ordered by sales count
        $bestSellers = Product::where('is_active', true)
            ->withCount('orderItems as orders_count')
            ->orderByDesc('orders_count')
            ->with('category', 'colors', 'reviews')
            ->limit(8)
            ->get();

        // Advertising banner for middle section
        $adBanner = Banner::where('is_active', true)
            ->where('position', 'middle')
            ->orderBy('sort_order')
            ->first();

        // Featured brands (6 items)
        $brands = Brand::where('is_active', true)
            ->limit(6)
            ->orderBy('name')
            ->get();

        // Testimonials from product reviews (4 items)
        $testimonials = $this->getTestimonials();

        return view('home', [
            'newProducts' => $newProducts,
            'bestSellers' => $bestSellers,
            'adBanner' => $adBanner,
            'brands' => $brands,
            'testimonials' => $testimonials,
        ]);
    }

    /**
     * Get testimonials from product reviews
     * Falls back to default testimonials if insufficient reviews exist
     */
    private function getTestimonials(): array
    {
        // Try to get testimonials from product reviews
        $reviews = Product::where('is_active', true)
            ->with('reviews.user')
            ->get()
            ->flatMap(fn($product) => $product->reviews?->flatten() ?? [])
            ->filter(fn($review) => $review->rating >= 4) // Only positive reviews
            ->unique('user_id')
            ->take(4)
            ->map(fn($review) => [
                'id' => $review->id ?? null,
                'name' => $review->user?->name ?? 'Khách hàng',
                'rating' => $review->rating ?? 5,
                'text' => $review->comment ?? 'Sản phẩm tuyệt vời!',
                'avatar' => ['👨', '👩', '🧑'][rand(0, 2)],
            ])
            ->values()
            ->toArray();

        // Return reviews if we have at least 1, otherwise use defaults
        if (count($reviews) > 0) {
            return $reviews;
        }

        // Default testimonials
        return [
            [
                'id' => 1,
                'name' => 'Nguyễn Văn A',
                'rating' => 5,
                'text' => 'Sản phẩm chất lượng tuyệt vời, giao hàng nhanh chóng và đóng gói cẩn thận.',
                'avatar' => '👨',
            ],
            [
                'id' => 2,
                'name' => 'Trần Thị B',
                'rating' => 5,
                'text' => 'Rất hài lòng với chất lượng áo thể thao, thoáng mát và dễ chịu khi mặc.',
                'avatar' => '👩',
            ],
            [
                'id' => 3,
                'name' => 'Lê Văn C',
                'rating' => 4,
                'text' => 'Giá cả hợp lý, chất lượng cũng ổn. Sẽ mua lại lần sau.',
                'avatar' => '🧑',
            ],
            [
                'id' => 4,
                'name' => 'Phạm Thị D',
                'rating' => 5,
                'text' => 'Dịch vụ khách hàng tuyệt vời, tư vấn sản phẩm rất chuyên môn.',
                'avatar' => '👩',
            ],
        ];
    }
}
