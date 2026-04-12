@extends('layouts.app')

@section('meta_title', 'Cửa hàng quần áo thể thao nam - SportWear Shop')
@section('meta_description', 'Mua quần áo thể thao nam chất lượng cao, giá cạnh tranh. Giao hàng nhanh toàn quốc.')
@section('meta_keywords', 'quần áo thể thao, áo thun nam, quần shorts, giày thể thao')
@section('og_type', 'website')

@section('content')
<!-- 1. HERO BANNER SLIDER -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <livewire:banner-slider />
</section>

<!-- 2. FEATURED CATEGORIES -->
<section class="bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Danh mục nổi bật</h2>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
            @php
                $categories = [
                    ['name' => 'Áo thun nam', 'icon' => '👕', 'slug' => 'ao-thun'],
                    ['name' => 'Quần shorts', 'icon' => '🩱', 'slug' => 'quan-shorts'],
                    ['name' => 'Quần dài', 'icon' => '👖', 'slug' => 'quan-dai'],
                    ['name' => 'Áo khoác', 'icon' => '🧥', 'slug' => 'ao-khoac'],
                ];
            @endphp

            @foreach($categories as $category)
            <a href="#" class="group bg-white rounded-lg shadow-sm hover:shadow-md transition-all text-center p-6 hover:scale-105 transform">
                <div class="text-5xl mb-3 group-hover:scale-110 transition-transform">{{ $category['icon'] }}</div>
                <h3 class="font-semibold text-gray-900 group-hover:text-red-500 transition-colors text-sm md:text-base">
                    {{ $category['name'] }}
                </h3>
            </a>
            @endforeach
        </div>
    </div>
</section>

<!-- 3. LATEST PRODUCTS -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="flex justify-between items-end mb-8">
        <div>
            <h2 class="text-3xl font-bold text-gray-900">Sản phẩm mới nhất</h2>
            <p class="text-gray-600 mt-2">Những sản phẩm vừa được cập nhật</p>
        </div>
        <a href="{{ route('products.index') }}" class="btn-secondary">
            Xem tất cả →
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @forelse($newProducts ?? [] as $product)
            <x-product-card :product="$product" />
        @empty
            <div class="col-span-full py-12 text-center text-gray-500">
                <p class="text-lg">Chưa có sản phẩm mới</p>
            </div>
        @endforelse
    </div>
</section>

<!-- 4. ADVERTISING BANNER -->
@if($adBanner ?? false)
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="relative h-64 md:h-80 rounded-lg overflow-hidden group">
        <img src="{{ asset($adBanner->image_url ?? 'images/ad-banner.jpg') }}" 
            alt="{{ $adBanner->title ?? 'Advertisement' }}" 
            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
        <div class="absolute inset-0 bg-black/30 group-hover:bg-black/40 transition-all">
            <div class="h-full flex items-center justify-center">
                <div class="text-center text-white">
                    <h3 class="text-2xl md:text-4xl font-bold mb-4">{{ $adBanner->title ?? 'Special Offer' }}</h3>
                    <a href="{{ $adBanner->link ?? '#' }}" class="btn-primary">
                        {{ $adBanner->cta_text ?? 'Mua ngay' }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

<!-- 5. BEST SELLERS -->
<section class="bg-gray-50 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-end mb-8">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">Bán chạy nhất</h2>
                <p class="text-gray-600 mt-2">Những sản phẩm được yêu thích nhất</p>
            </div>
            <a href="{{ route('products.index') }}" class="btn-secondary">
                Xem tất cả →
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($bestSellers ?? [] as $product)
                <x-product-card :product="$product" />
            @empty
                <div class="col-span-full py-12 text-center text-gray-500">
                    <p class="text-lg">Chưa có dữ liệu bán chạy</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- 6. BRANDS -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <h2 class="text-3xl font-bold text-gray-900 mb-12 text-center">Thương hiệu hàng đầu</h2>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
        @forelse($brands ?? [] as $brand)
        <a href="#" class="group bg-white rounded-lg border border-gray-200 p-6 flex items-center justify-center hover:border-red-500 transition-all h-24">
            <img src="{{ asset($brand->logo_url ?? 'images/brand-placeholder.png') }}" 
                alt="{{ $brand->name }}" 
                class="max-w-full max-h-full grayscale group-hover:grayscale-0 transition-all">
        </a>
        @empty
            <div class="col-span-full py-8 text-center text-gray-500">
                <p>Không có thương hiệu nào</p>
            </div>
        @endforelse
    </div>
</section>

<!-- 7. TESTIMONIALS SLIDER -->
<section class="bg-gradient-to-r from-gray-900 to-gray-800 text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-center mb-12">Nhận xét từ khách hàng</h2>

        <div x-data="{ current: 0, reviews: @json($testimonials ?? [
            ['id' => 1, 'name' => 'Nguyễn Văn A', 'rating' => 5, 'text' => 'Sản phẩm chất lượng tốt, giao hàng nhanh. Sẽ tiếp tục mua!', 'avatar' => '👨'],
            ['id' => 2, 'name' => 'Trần Thị B', 'rating' => 4.5, 'text' => 'Rất hài lòng với chất lượng và dịch vụ khách hàng. Giá cạnh tranh.', 'avatar' => '👩'],
            ['id' => 3, 'name' => 'Phạm Văn C', 'rating' => 5, 'text' => 'Quần áo vừa vặn, màu đẹp. Đúng như mô tả. Cảm ơn shop!', 'avatar' => '👨'],
            ['id' => 4, 'name' => 'Lê Thị D', 'rating' => 5, 'text' => 'Chất lượng tốt, giá hợp lý. Rất đáng mua!', 'avatar' => '👩'],
        ]) }" class="relative">
            <!-- Slide Container -->
            <div class="overflow-hidden">
                <div class="flex transition-transform duration-500" :style="`transform: translateX(-${current * 100}%)`">
                    <template x-for="review in reviews" :key="review.id">
                        <div class="w-full flex-shrink-0 px-4">
                            <div class="bg-white/10 rounded-lg p-8 text-center max-w-2xl mx-auto backdrop-blur">
                                <div class="text-5xl mb-4" x-text="review.avatar"></div>
                                <h3 class="text-xl font-semibold mb-2" x-text="review.name"></h3>
                                <div class="flex justify-center mb-4">
                                    <x-star-rating :rating="5" size="sm" />
                                </div>
                                <p class="text-gray-200 text-lg" x-text="review.text"></p>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Navigation -->
            <div class="flex justify-center gap-2 mt-8">
                <template x-for="(review, index) in reviews" :key="index">
                    <button @click="current = index" 
                        :class="current === index ? 'bg-red-500 w-8' : 'bg-white/30 hover:bg-white/50'"
                        class="h-2 rounded-full transition-all"></button>
                </template>
            </div>

            <!-- Auto-rotate (5s) -->
            <script>
                setInterval(() => {
                    let max = document.querySelector('[x-data]')?.__vue__?.reviews?.length || 4;
                    Alpine.evaluate(document.querySelector('[x-data]'), 'current = (current + 1) % max');
                }, 5000);
            </script>
        </div>
    </div>
</section>

<!-- 8. NEWSLETTER SIGNUP -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="bg-red-50 rounded-lg border border-red-200 p-8 md:p-12 text-center">
        <h2 class="text-3xl font-bold text-gray-900 mb-4">Nhận thông tin khuyến mãi</h2>
        <p class="text-gray-600 mb-8 max-w-2xl mx-auto">
            Đăng ký bản tin của chúng tôi để nhận những ưu đãi độc quyền,
            sản phẩm mới và tin tức từ SportWear Shop
        </p>

        <form class="flex flex-col md:flex-row gap-3 max-w-lg mx-auto">
            @csrf
            <input type="email" 
                placeholder="Nhập email của bạn..." 
                required
                class="flex-1 px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-red-500">
            <button type="submit" class="btn-primary">
                Đăng ký
            </button>
        </form>
        <p class="text-sm text-gray-500 mt-4">
            Chúng tôi sẽ không bao giờ chia sẻ email của bạn. Bỏ đăng ký bất cứ lúc nào.
        </p>
    </div>
</section>

<!-- OLD HERO SECTION (Keeping for reference, can be removed) -->
<section class="bg-gradient-to-r from-gray-900 to-gray-800 text-white py-20 md:py-32" style="display: none;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
            <!-- Text Content -->
            <div>
                <h1 class="text-4xl md:text-5xl font-bold mb-4">
                    Quần áo thể thao <span class="text-red-500">chất lượng cao</span>
                </h1>
                <p class="text-xl text-gray-300 mb-6">
                    Khám phá bộ sưu tập quần áo thể thao nam với thiết kế hiện đại, chất liệu thoáng mát và giá cạnh tranh.
                </p>
                <div class="flex gap-4">
                    <a href="#products" class="btn-primary text-lg">
                        Mua sắm ngay
                    </a>
                    <a href="#" class="btn-secondary text-lg">
                        Xem sale
                    </a>
                </div>
            </div>

            <!-- Hero Image -->
            <div class="hidden md:block h-96 bg-gray-700 rounded-lg flex items-center justify-center">
                <span class="text-gray-500">Product Hero Image</span>
            </div>
        </div>
    </div>
</section>

<!-- Alerts Example -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <x-alert type="info" title="🎉 Chào mừng!">
        Bạn nhận được <span class="font-bold">10%</span> giảm giá cho lần mua đầu tiên!
    </x-alert>
</div>

<!-- Featured Products Section -->
<section id="products" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="mb-12">
        <h2 class="text-4xl font-bold text-gray-900 mb-2">Sản phẩm nổi bật</h2>
        <p class="text-gray-600">Những sản phẩm được yêu thích nhất của khách hàng</p>
    </div>

    <!-- Products Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        @forelse($featuredProducts ?? [] as $product)
            <x-product-card :product="$product" />
        @empty
            <div class="col-span-full py-12 text-center text-gray-500">
                <p class="text-lg">Chưa có sản phẩm nào để hiển thị</p>
                <p class="text-sm">Vui lòng quay lại sau</p>
            </div>
        @endforelse
    </div>

    <!-- View More Button -->
    <div class="text-center">
        <a href="#" class="btn-secondary text-lg">
            Xem tất cả sản phẩm →
        </a>
    </div>
</section>

<!-- Categories Section -->
<section class="bg-gray-50 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-gray-900 mb-12 text-center">Danh mục sản phẩm</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @php
                $categories = [
                    ['name' => 'Áo thun nam', 'icon' => '👕', 'count' => 45, 'color' => 'bg-blue-100'],
                    ['name' => 'Quần shorts', 'icon' => '🩱', 'count' => 32, 'color' => 'bg-green-100'],
                    ['name' => 'Quần dài', 'icon' => '👖', 'count' => 28, 'color' => 'bg-purple-100'],
                    ['name' => 'Áo khoác', 'icon' => '🧥', 'count' => 18, 'color' => 'bg-orange-100'],
                    ['name' => 'Giày thể thao', 'icon' => '👟', 'count' => 52, 'color' => 'bg-pink-100'],
                    ['name' => 'Phụ kiện', 'icon' => '🧢', 'count' => 67, 'color' => 'bg-yellow-100'],
                ];
            @endphp

            @foreach($categories as $category)
                <a href="#" class="group bg-white rounded-lg overflow-hidden shadow hover:shadow-lg transition-all">
                    <div class="{{ $category['color'] }} h-40 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <span class="text-6xl">{{ $category['icon'] }}</span>
                    </div>
                    <div class="p-4">
                        <h3 class="font-bold text-gray-900 group-hover:text-red-500 transition-colors">
                            {{ $category['name'] }}
                        </h3>
                        <p class="text-sm text-gray-500">{{ $category['count'] }} sản phẩm</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- Sale Section -->
<section class="bg-red-500 text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl font-bold mb-4">🔥 Flash Sale - Hôm nay</h2>
        <p class="text-xl mb-8 text-red-100">Giảm giá lên đến 50% cho các sản phẩm được chọn</p>
        
        <!-- Countdown Timer -->
        <div class="flex justify-center gap-4 mb-8">
            @php
                $saleEnd = now()->addHours(12);
            @endphp
            <div class="bg-red-600 rounded-lg px-4 py-2">
                <div class="text-3xl font-bold">23</div>
                <div class="text-sm">Giờ</div>
            </div>
            <div class="bg-red-600 rounded-lg px-4 py-2">
                <div class="text-3xl font-bold">45</div>
                <div class="text-sm">Phút</div>
            </div>
            <div class="bg-red-600 rounded-lg px-4 py-2">
                <div class="text-3xl font-bold">32</div>
                <div class="text-sm">Giây</div>
            </div>
        </div>

        <a href="#" class="inline-block bg-white text-red-500 font-bold px-8 py-3 rounded-lg hover:bg-red-50 transition-all">
            Xem các sản phẩm sale →
        </a>
    </div>
</section>

<!-- Best Sellers Section -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="mb-12">
        <h2 class="text-3xl font-bold text-gray-900 mb-2">Bán chạy nhất</h2>
        <p class="text-gray-600">Những sản phẩm được khách hàng lựa chọn nhiều nhất</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @forelse($bestSellers ?? [] as $product)
            <x-product-card :product="$product" />
        @empty
            <div class="col-span-full py-12 text-center text-gray-500">
                <p class="text-lg">Chưa có dữ liệu bán chạy</p>
            </div>
        @endforelse
    </div>
</section>

<!-- Reviews/Testimonials Section -->
<section class="bg-gray-50 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-gray-900 mb-12 text-center">Nhận xét từ khách hàng</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @php
                $reviews = [
                    [
                        'name' => 'Nguyễn Văn A',
                        'rating' => 5,
                        'text' => 'Sản phẩm chất lượng tốt, giao hàng nhanh. Sẽ tiếp tục mua!',
                    ],
                    [
                        'name' => 'Trần Thị B',
                        'rating' => 4.5,
                        'text' => 'Rất hài lòng với chất lượng và dịch vụ khách hàng. Giá cạnh tranh.',
                    ],
                    [
                        'name' => 'Phạm Văn C',
                        'rating' => 5,
                        'text' => 'Quán áo vừa vặn, màu đẹp. Đúng như mô tả. Cảm ơn shop!',
                    ],
                ];
            @endphp

            @foreach($reviews as $review)
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-semibold text-gray-900">{{ $review['name'] }}</h3>
                        <x-star-rating :rating="$review['rating']" size="sm" />
                    </div>
                    <p class="text-gray-600 text-sm">{{ $review['text'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Newsletter Section -->
<section class="bg-gradient-to-r from-gray-900 to-gray-800 text-white py-12">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold mb-4">Nhận thông tin khuyến mãi</h2>
        <p class="text-gray-300 mb-6">Đăng ký để nhận những ưu đãi độc quyền và thông tin sản phẩm mới trước tiên</p>
        
        <form class="flex gap-2 max-w-md mx-auto">
            <input type="email" placeholder="Nhập email của bạn..." 
                class="flex-1 px-4 py-3 rounded-lg bg-white text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-red-500"
                required>
            <button type="submit" class="btn-primary">
                Đăng ký
            </button>
        </form>
    </div>
</section>

<!-- Pagination Example (if needed) -->
@if(false)
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <x-pagination :paginator="$products ?? null" />
</div>
@endif

@endsection

@section('styles')
<style>
    /* Custom animations */
    @keyframes pulse-slow {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }
    
    .animate-pulse { animation: pulse-slow 2s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
    
    /* Smooth transitions */
    * { @apply transition-all duration-300; }
</style>
@endsection
