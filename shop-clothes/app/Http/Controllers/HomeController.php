<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Banner;
use App\Models\Brand;
use App\Models\Category;
use App\Models\FlashSale;
use Illuminate\View\View;

class HomeController extends Controller
{
    //  LOGIC_TRANG_CHU (Logic lấy dữ liệu hiển thị trên trang chủ)
    /**
     * Hàm này có nhiệm vụ lấy tất cả dữ liệu cần thiết từ Database để hiển thị ra trang chủ (Home)
     */
    public function __invoke(): View
    {
        // 1. Sản phẩm mới nhất (Latest products)
        // Lấy 8 sản phẩm đang được kích hoạt (is_active = true), sắp xếp theo ngày tạo mới nhất giảm dần
        $newProducts = Product::where('is_active', true)
            ->with(['category', 'images', 'variants.color', 'reviews'])
            ->latest('created_at')
            ->limit(8)
            ->get();

        // 2. Sản phẩm bán chạy nhất (Best sellers)
        // Tính tổng số lượng đã bán của từng sản phẩm thông qua bảng order_items, sau đó lấy 8 sản phẩm bán nhiều nhất
        $bestSellers = Product::where('is_active', true)
            ->withSum('orderItems as sold_quantity', 'quantity')
            ->orderByDesc('sold_quantity')
            ->orderByDesc('created_at')
            ->with(['category', 'images', 'variants.color', 'reviews'])
            ->limit(8)
            ->get();

        // 3. Banner quảng cáo giữa trang (Advertising banner)
        // Lấy 1 banner đang hoạt động được cài đặt để hiển thị ở vị trí 'middle'
        $adBanner = Banner::where('is_active', true)
            ->where('position', 'middle')
            ->orderBy('sort_order')
            ->first();

        // 4. Flash Sale (Sản phẩm đang chạy Flash Sale)
        // Tìm các chương trình Flash Sale đang trong thời gian diễn ra (running()) và kèm theo thông tin sản phẩm
        $flashSales = FlashSale::query()
            ->running()
            ->with([
                'product' => function ($query) {
                    $query->where('is_active', true)
                        ->with(['category', 'images', 'variants.color', 'reviews']);
                },
            ])
            ->orderBy('sort_order')
            ->orderBy('start_at')
            ->limit(8)
            ->get()
            ->filter(fn ($item) => $item->product !== null) // Chỉ lấy các mục mà sản phẩm gốc chưa bị xóa/tắt
            ->values();

        // 5. Danh mục nổi bật (Featured categories)
        // Lấy 6 danh mục gốc (không có parent_id), đếm số lượng sản phẩm bên trong (kể cả danh mục con) và sắp xếp lấy nhiều nhất
        $categories = Category::rootsWithProductCount(6);

        // 6. Thương hiệu nổi bật (Featured brands)
        // Lấy 6 thương hiệu ngẫu nhiên đang được kích hoạt, sắp xếp theo tên
        $brands = Brand::where('is_active', true)
            ->limit(6)
            ->orderBy('name')
            ->get();

        // 7. Nhận xét của khách hàng (Testimonials)
        // Lọc ngẫu nhiên các đánh giá 4-5 sao từ người dùng thật
        $testimonials = $this->getTestimonials();

        // Trả tất cả dữ liệu (biến) ở trên vào file giao diện (view) resources/views/home.blade.php
        return view('home', [
            'newProducts' => $newProducts,
            'bestSellers' => $bestSellers,
            'adBanner' => $adBanner,
            'flashSales' => $flashSales,
            'brands' => $brands,
            'categories' => $categories,
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
                'text' => 'Rất hài lòng với chất lượng áo thể thao, thoáng mát và dễ chịu khi mặc OK.',
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
