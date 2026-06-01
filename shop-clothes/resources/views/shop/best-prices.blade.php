@extends('layouts.app')

@section('meta_title', 'Giá tốt | MODESPORT')
@section('meta_description', 'Sản phẩm thể thao nam giá tốt nhất tại MODESPORT. Ưu đãi hấp dẫn, tiết kiệm mỗi ngày.')

@section('content')
@php
    $cheapProducts = \App\Models\Product::where('is_active', true)
        ->whereNotNull('sale_price')
        ->whereColumn('sale_price', '<', 'price')
        ->with(['category', 'images', 'variants.color', 'reviews'])
        ->orderBy('price', 'asc')
        ->limit(16)
        ->get();

    $allCheapProducts = \App\Models\Product::where('is_active', true)
        ->with(['category', 'images', 'variants.color', 'reviews'])
        ->orderBy('price', 'asc')
        ->limit(16)
        ->get();

    $priceRanges = [
        ['label' => 'Dưới 200K', 'min' => 0, 'max' => 200000, 'icon' => '🔥', 'color' => 'from-red-500 to-orange-500'],
        ['label' => '200K - 500K', 'min' => 200000, 'max' => 500000, 'icon' => '⭐', 'color' => 'from-amber-500 to-yellow-500'],
        ['label' => '500K - 1 triệu', 'min' => 500000, 'max' => 1000000, 'icon' => '💎', 'color' => 'from-blue-500 to-cyan-500'],
        ['label' => 'Trên 1 triệu', 'min' => 1000000, 'max' => 99999999, 'icon' => '👑', 'color' => 'from-purple-500 to-pink-500'],
    ];
@endphp

{{-- Hero Banner --}}
<section class="relative overflow-hidden bg-gradient-to-br from-amber-50 via-orange-50 to-red-50">
    <div class="absolute inset-0">
        <div class="absolute top-10 right-10 w-40 h-40 bg-orange-200 rounded-full opacity-40 blur-2xl"></div>
        <div class="absolute bottom-10 left-10 w-60 h-60 bg-red-200 rounded-full opacity-30 blur-3xl"></div>
    </div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-12 py-16 md:py-20">
        <div class="text-center">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white shadow-sm border border-orange-200 mb-6">
                <span class="text-2xl">💰</span>
                <span class="text-sm font-bold text-orange-700">Giá tốt nhất - Chất lượng đảm bảo</span>
            </div>
            <h1 class="text-4xl md:text-6xl font-black text-slate-900 leading-tight">
                Sản phẩm <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-red-500">giá tốt</span>
            </h1>
            <p class="mt-4 text-lg text-slate-600 max-w-2xl mx-auto">
                Thời trang thể thao chất lượng cao với mức giá phải chăng. Sắm ngay không cần suy nghĩ!
            </p>
        </div>

        {{-- Price Range Cards --}}
        <div class="mt-12 grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($priceRanges as $range)
                <a href="{{ route('shop.index', ['sort' => 'price_low', 'price_min' => $range['min'], 'price_max' => $range['max']]) }}" class="group relative overflow-hidden rounded-2xl p-5 bg-white border border-slate-100 hover:border-orange-200 transition-all hover:shadow-lg hover:-translate-y-1">
                    <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br {{ $range['color'] }} opacity-10 rounded-bl-full"></div>
                    <span class="text-3xl">{{ $range['icon'] }}</span>
                    <h3 class="mt-3 font-bold text-slate-900 text-sm">{{ $range['label'] }}</h3>
                    <div class="mt-2 flex items-center gap-1 text-xs text-orange-600 font-semibold group-hover:text-red-500 transition-colors">
                        Xem ngay
                        <svg class="w-3 h-3 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- Trust Bar --}}
<section class="bg-white border-b border-slate-100 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-12">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-slate-900">Giá tốt nhất</p>
                    <p class="text-xs text-slate-500">Cam kết giá rẻ</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-slate-900">Chính hãng 100%</p>
                    <p class="text-xs text-slate-500">Đảm bảo chất lượng</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-orange-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-slate-900">Giao nhanh</p>
                    <p class="text-xs text-slate-500">2-3 ngày toàn quốc</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-slate-900">Đổi trả 7 ngày</p>
                    <p class="text-xs text-slate-500">Miễn phí đổi trả</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Flash Deal Products --}}
@if($cheapProducts->count() > 0)
<section class="py-12 md:py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-12">
        <div class="flex items-center gap-3 mb-8">
            <div class="flex-shrink-0 w-12 h-12 rounded-2xl bg-gradient-to-br from-red-500 to-orange-500 flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <div>
                <h2 class="text-2xl md:text-3xl font-extrabold text-slate-900">Đang giảm giá</h2>
                <p class="text-slate-500 text-sm mt-0.5">Sản phẩm đang có ưu đãi, mua ngay kẻo lỡ!</p>
            </div>
        </div>

        <div class="flex gap-4 overflow-x-auto pb-4 scrollbar-hide snap-x snap-mandatory">
            @foreach($cheapProducts->take(8) as $product)
                @php
                    $img = $product->image_url;
                    $imgSrc = str_starts_with($img, 'http') ? $img : asset($img);
                    $hasDiscount = $product->sale_price && $product->sale_price < $product->price;
                    $discount = $hasDiscount ? round((1 - $product->sale_price / $product->price) * 100) : 0;
                @endphp
                <a href="{{ route('products.show', $product->slug) }}" class="group flex-shrink-0 w-56 snap-start">
                    <div class="relative aspect-[3/4] rounded-2xl overflow-hidden bg-slate-100 mb-3">
                        <img src="{{ $imgSrc }}" alt="{{ $product->name }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy">
                        @if($hasDiscount)
                            <div class="absolute top-0 left-0">
                                <div class="inline-flex items-center gap-1 px-3 py-1 bg-gradient-to-r from-red-500 to-orange-500 text-white text-xs font-bold rounded-br-xl">
                                    -{{ $discount }}%
                                </div>
                            </div>
                        @endif
                    </div>
                    <h3 class="text-sm font-bold text-slate-900 line-clamp-2 group-hover:text-red-500 transition-colors">{{ $product->name }}</h3>
                    <div class="mt-1.5 flex items-baseline gap-2">
                        @if($hasDiscount)
                            <span class="text-base font-black text-red-500">{{ number_format($product->sale_price, 0, ',', '.') }}₫</span>
                            <span class="text-xs text-slate-400 line-through">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                        @else
                            <span class="text-base font-black text-slate-900">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- All Products Grid --}}
<section class="py-12 md:py-16 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-12">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-2xl md:text-3xl font-extrabold text-slate-900">Tất cả sản phẩm giá tốt</h2>
                <p class="text-slate-500 mt-1">Sắp xếp từ giá thấp đến cao</p>
            </div>
            <a href="{{ route('shop.index', ['sort' => 'price_low']) }}" class="hidden md:inline-flex items-center gap-2 text-sm font-semibold text-orange-600 hover:text-red-500 transition-colors">
                Xem tất cả
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
            @foreach($allCheapProducts as $product)
                @php
                    $img = $product->image_url;
                    $imgSrc = str_starts_with($img, 'http') ? $img : asset($img);
                    $hasDiscount = $product->sale_price && $product->sale_price < $product->price;
                    $discount = $hasDiscount ? round((1 - $product->sale_price / $product->price) * 100) : 0;
                    $avgRating = $product->reviews->avg('rating') ?? 0;
                    $reviewCount = $product->reviews->count();
                @endphp
                <a href="{{ route('products.show', $product->slug) }}" class="group bg-white rounded-2xl overflow-hidden border border-slate-100 hover:shadow-lg hover:-translate-y-1 transition-all">
                    <div class="relative aspect-[3/4] overflow-hidden bg-slate-100">
                        <img src="{{ $imgSrc }}" alt="{{ $product->name }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy">
                        @if($hasDiscount)
                            <span class="absolute top-3 left-3 px-2.5 py-1 bg-gradient-to-r from-red-500 to-orange-500 text-white text-xs font-bold rounded-full shadow-sm">-{{ $discount }}%</span>
                        @endif
                        @if($product->price < 300000)
                            <span class="absolute top-3 right-3 px-2 py-0.5 bg-green-500 text-white text-[10px] font-bold rounded-full">RẺ</span>
                        @endif
                    </div>
                    <div class="p-4">
                        <p class="text-[11px] text-slate-400 font-medium uppercase tracking-wide">{{ $product->category->name ?? '' }}</p>
                        <h3 class="mt-1 text-sm font-bold text-slate-900 line-clamp-2 group-hover:text-red-500 transition-colors min-h-[2.5rem]">{{ $product->name }}</h3>
                        @if($reviewCount > 0)
                            <div class="flex items-center gap-1 mt-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-3 h-3 {{ $i <= round($avgRating) ? 'text-amber-400' : 'text-slate-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                @endfor
                                <span class="text-[10px] text-slate-400 ml-0.5">({{ $reviewCount }})</span>
                            </div>
                        @endif
                        <div class="mt-2 flex items-baseline gap-2">
                            @if($hasDiscount)
                                <span class="text-lg font-black text-red-500">{{ number_format($product->sale_price, 0, ',', '.') }}₫</span>
                                <span class="text-xs text-slate-400 line-through">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                            @else
                                <span class="text-lg font-black text-slate-900">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-10 text-center">
            <a href="{{ route('shop.index', ['sort' => 'price_low']) }}" class="inline-flex items-center gap-2 px-8 py-3 bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600 text-white font-bold rounded-xl transition shadow-lg shadow-orange-500/25">
                Xem tất cả sản phẩm giá tốt
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    </div>
</section>
@endsection
