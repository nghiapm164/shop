@extends('layouts.app')

@section('meta_title', 'SportWear Shop | Quần áo thể thao nam trẻ trung')
@section('meta_description', 'Nâng cấp phong cách thể thao nam với áo, quần, phụ kiện năng động. Mua sắm nhanh, giao hàng toàn quốc.')
@section('meta_keywords', 'sportwear, quần áo thể thao nam, gym, running, training')

@section('content')
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-12">
    <div class="grid lg:grid-cols-5 gap-5 items-stretch">
        <div class="lg:col-span-3 rounded-[2rem] overflow-hidden p-8 md:p-10 relative bg-gradient-to-br from-red-500 via-orange-400 to-amber-200 text-slate-950 stagger-enter">
            <div class="absolute inset-0 opacity-35" style="background-image: linear-gradient(120deg, rgba(255,255,255,0.35) 0%, transparent 60%)"></div>
            <div class="relative z-10 max-w-xl">
                <p class="chip bg-white/65 border-white/80 text-slate-900">BST mùa mới 2026</p>
                <h1 class="fashion-title text-4xl md:text-6xl mt-4 leading-tight">Tập chất. Mặc chất.</h1>
                <p class="mt-4 text-base md:text-lg text-slate-800/85">Phong cách athleisure hiện đại cho nam giới: khỏe khoắn, tinh gọn và sẵn sàng cho mọi nhịp sống.</p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('shop.index') }}" class="btn-primary">Mua bộ sưu tập</a>
                    <a href="#new-products" class="btn-secondary">Xem hàng mới</a>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 grid gap-5">
            <div class="fashion-section p-6 stagger-enter stagger-enter-delay-1">
                <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Ưu đãi giới hạn</p>
                <h3 class="fashion-title text-2xl mt-2">Giảm đến 35%</h3>
                <p class="fashion-subtitle mt-2">Áp dụng cho các mẫu training và running bán chạy nhất.</p>
            </div>
            <div class="rounded-3xl border border-slate-200 bg-slate-900 text-white p-6 stagger-enter stagger-enter-delay-2">
                <p class="text-xs uppercase tracking-[0.2em] text-slate-300">Trải nghiệm mua sắm</p>
                <h3 class="display-font text-2xl font-bold mt-2">Giao nhanh 2h nội thành</h3>
                <p class="text-sm text-slate-300 mt-2">Freeship toàn quốc cho đơn từ 299.000đ.</p>
            </div>
        </div>
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
    <div class="fashion-section overflow-hidden p-2 md:p-3 stagger-enter stagger-enter-delay-1">
        <livewire:banner-slider />
    </div>
</section>

@if(($flashSales ?? collect())->isNotEmpty())
<section class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="rounded-[2rem] overflow-hidden border border-red-200 bg-gradient-to-br from-red-50 via-orange-50 to-amber-50 p-6 md:p-8">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-3 mb-8">
                <div>
                    <p class="chip bg-red-100 border-red-200 text-red-700">Flash Sale Live</p>
                    <h2 class="fashion-title text-3xl mt-3">Deal nóng trong ngày</h2>
                    <p class="fashion-subtitle mt-2">Giá cực tốt, số lượng có hạn. Chốt đơn ngay trước khi hết giờ.</p>
                </div>
                <a href="{{ route('shop.index', ['collection' => 'flash_sale']) }}" class="btn-secondary">Xem tất cả deal nóng</a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                @foreach($flashSales as $flashSale)
                    @php
                        $product = $flashSale->product;
                        $originalPrice = $product->sale_price ?? $product->price;
                        $discountPercent = $flashSale->discount_percent;
                        $flashImage = $product->image_url;
                        $flashImageSrc = str_starts_with($flashImage, 'http://') || str_starts_with($flashImage, 'https://')
                            ? $flashImage
                            : asset($flashImage);
                    @endphp

                    <article class="group relative overflow-hidden rounded-3xl border border-red-100 bg-white shadow-[0_15px_40px_-28px_rgba(220,38,38,0.6)]">
                        <a href="{{ route('products.show', $product->slug) }}" class="absolute inset-0 z-10" aria-label="{{ $product->name }}"></a>

                        <div class="relative aspect-[3/4] overflow-hidden bg-slate-100">
                            <img
                                src="{{ $flashImageSrc }}"
                                alt="{{ $product->name }}"
                                class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105"
                                loading="lazy"
                            >
                            <div class="absolute top-3 left-3 z-20 rounded-full bg-red-600 px-3 py-1 text-xs font-bold text-white">
                                -{{ $discountPercent }}%
                            </div>
                            <div class="absolute top-3 right-3 z-20 rounded-full bg-black/75 px-2.5 py-1 text-[11px] font-semibold text-white backdrop-blur" data-flash-countdown data-end="{{ $flashSale->end_at->toIso8601String() }}">
                                Đang cập nhật...
                            </div>
                        </div>

                        <div class="p-4">
                            <h3 class="line-clamp-1 text-base font-bold text-slate-900">{{ $product->name }}</h3>

                            <div class="mt-3 flex items-end gap-2">
                                <span class="text-xl font-extrabold text-red-600">{{ number_format($flashSale->flash_price, 0) }}đ</span>
                                <span class="text-sm text-slate-500 line-through">{{ number_format($originalPrice, 0) }}đ</span>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif

<section class="py-12" id="new-products">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between mb-8">
            <div>
                <h2 class="fashion-title text-3xl">Hàng mới lên kệ</h2>
                <p class="fashion-subtitle mt-2">Tinh thần trẻ, phối nhanh, mặc đẹp mỗi ngày.</p>
            </div>
            <a href="{{ route('shop.index', ['collection' => 'new_arrivals', 'sort' => 'newest']) }}" class="btn-secondary">Xem tất cả</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($newProducts ?? [] as $product)
                <x-product-card :product="$product" />
            @empty
                <div class="col-span-full fashion-section py-12 text-center text-slate-500">Chưa có sản phẩm mới.</div>
            @endforelse
        </div>
    </div>
</section>

<section class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="fashion-section p-5 md:p-8">
            <div class="flex items-end justify-between mb-8">
                <div>
                    <h2 class="fashion-title text-3xl">Top bán chạy</h2>
                    <p class="fashion-subtitle mt-2">Sản phẩm được khách hàng chọn mua nhiều nhất tuần này.</p>
                </div>
                <a href="{{ route('shop.index', ['collection' => 'best_sellers', 'sort' => 'popularity']) }}" class="btn-secondary">Xem tất cả</a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($bestSellers ?? [] as $product)
                    <x-product-card :product="$product" />
                @empty
                    <div class="col-span-full border border-dashed border-slate-300 rounded-2xl py-12 text-center text-slate-500">Chưa có dữ liệu bán chạy.</div>
                @endforelse
            </div>
        </div>
    </div>
</section>

<section class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="fashion-title text-3xl text-center mb-8">Thương hiệu đồng hành</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            @forelse($brands ?? [] as $brand)
                <div class="fashion-card h-24 bg-white flex items-center justify-center p-3">
                    <img
                        src="{{ $brand->logo_url }}"
                        alt="{{ $brand->name }}"
                        class="max-h-full max-w-full object-contain"
                    >
                </div>
            @empty
                <div class="col-span-full text-center text-slate-500">Chưa có thương hiệu hiển thị.</div>
            @endforelse
        </div>
    </div>
</section>

<section class="py-16 bg-slate-950 text-white">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8" x-data="{ current: 0, reviews: @js($testimonials ?? []) }" x-init="setInterval(() => { if (reviews.length) current = (current + 1) % reviews.length }, 5000)">
        <h2 class="display-font text-3xl md:text-4xl text-center font-extrabold mb-10">Khách hàng nói gì về MODESPORT</h2>

        <div class="overflow-hidden rounded-3xl border border-white/20">
            <div class="flex transition-transform duration-500" :style="`transform: translateX(-${current * 100}%)`">
                <template x-for="item in reviews" :key="item.id">
                    <div class="w-full shrink-0 px-2">
                        <div class="bg-white/10 p-8 md:p-10 text-center">
                            <div class="text-5xl" x-text="item.avatar"></div>
                            <h3 class="mt-4 text-xl font-bold" x-text="item.name"></h3>
                            <p class="mt-3 text-slate-200 text-lg" x-text="item.text"></p>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <div class="flex justify-center gap-2 mt-6">
            <template x-for="(item, index) in reviews" :key="index">
                <button class="h-2 rounded-full transition-all" @click="current = index" :class="current === index ? 'w-8 bg-red-500' : 'w-2 bg-white/40'"></button>
            </template>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const formatTime = (value) => String(value).padStart(2, '0');
        const countdownElements = document.querySelectorAll('[data-flash-countdown]');

        const tick = () => {
            countdownElements.forEach((element) => {
                const endAt = new Date(element.dataset.end || '').getTime();
                const now = Date.now();
                const diff = endAt - now;

                if (Number.isNaN(endAt) || diff <= 0) {
                    element.textContent = 'Đã kết thúc';
                    return;
                }

                const totalSeconds = Math.floor(diff / 1000);
                const days = Math.floor(totalSeconds / 86400);
                const hours = Math.floor((totalSeconds % 86400) / 3600);
                const minutes = Math.floor((totalSeconds % 3600) / 60);
                const seconds = totalSeconds % 60;

                element.textContent = days > 0
                    ? `Còn ${days}d ${formatTime(hours)}h`
                    : `Còn ${formatTime(hours)}:${formatTime(minutes)}:${formatTime(seconds)}`;
            });
        };

        tick();
        setInterval(tick, 1000);
    });
</script>
@endpush
