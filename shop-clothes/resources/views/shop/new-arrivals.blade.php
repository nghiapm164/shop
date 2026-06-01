@extends('layouts.app')

@section('meta_title', 'Hàng mới về | MODESPORT')
@section('meta_description', 'Khám phá các sản phẩm thể thao mới nhất vừa lên kệ tại MODESPORT.')

@section('content')
@php
    $newProducts = \App\Models\Product::where('is_active', true)
        ->with(['category', 'images', 'variants.color', 'reviews'])
        ->latest('created_at')
        ->limit(16)
        ->get();

    $newCategories = \App\Models\Category::where('is_active', true)
        ->whereNull('parent_id')
        ->withCount(['products' => fn($q) => $q->where('is_active', true)->latest('products.created_at')])
        ->orderByDesc('products_count')
        ->limit(6)
        ->get();
@endphp

{{-- Hero Banner --}}
<section class="relative overflow-hidden bg-gradient-to-br from-slate-900 via-slate-800 to-slate-950">
    <div class="absolute inset-0 opacity-20">
        <div class="absolute top-0 right-0 w-96 h-96 bg-red-500 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3"></div>
        <div class="absolute bottom-0 left-0 w-72 h-72 bg-orange-400 rounded-full blur-3xl translate-y-1/3 -translate-x-1/4"></div>
    </div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-12 py-16 md:py-24">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div class="animate-fade-up">
                <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-red-500/20 border border-red-500/30 text-red-300 text-xs font-semibold tracking-wider uppercase">
                    <span class="w-2 h-2 bg-red-400 rounded-full animate-pulse"></span>
                    Just Dropped
                </span>
                <h1 class="mt-6 text-4xl md:text-6xl font-black text-white leading-tight tracking-tight">
                    Hàng mới<br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-400 to-orange-400">lên kệ</span>
                </h1>
                <p class="mt-4 text-lg text-slate-300 max-w-lg leading-relaxed">
                    Khám phá những sản phẩm thể thao mới nhất với thiết kế hiện đại, chất liệu cao cấp và phong cách trẻ trung.
                </p>
                <div class="mt-8 flex flex-wrap gap-4">
                    <a href="#products" class="inline-flex items-center gap-2 px-6 py-3 bg-red-500 hover:bg-red-600 text-white font-bold rounded-xl transition shadow-lg shadow-red-500/25">
                        Khám phá ngay
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                    </a>
                    <a href="{{ route('shop.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-white/10 hover:bg-white/20 text-white font-semibold rounded-xl transition border border-white/20">
                        Xem tất cả sản phẩm
                    </a>
                </div>
            </div>
            <div class="hidden lg:grid grid-cols-2 gap-4 animate-fade-up" style="animation-delay: 0.15s">
                @foreach($newProducts->take(4) as $i => $product)
                    @php
                        $img = $product->image_url;
                        $imgSrc = str_starts_with($img, 'http') ? $img : asset($img);
                    @endphp
                    <a href="{{ route('products.show', $product->slug) }}" class="group relative aspect-[3/4] rounded-2xl overflow-hidden bg-slate-800 {{ $i >= 2 ? 'mt-8' : '' }}">
                        <img src="{{ $imgSrc }}" alt="{{ $product->name }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" loading="lazy">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                        <div class="absolute bottom-3 left-3 right-3">
                            <p class="text-white text-sm font-bold line-clamp-1">{{ $product->name }}</p>
                            <p class="text-red-300 text-xs font-semibold mt-0.5">{{ number_format($product->sale_price ?? $product->price, 0, ',', '.') }}₫</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- Category Quick Links --}}
@if($newCategories->count() > 0)
<section class="py-12 bg-white border-b border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-12">
        <div class="flex items-center gap-4 overflow-x-auto pb-2 scrollbar-hide">
            <a href="{{ route('shop.index', ['sort' => 'newest']) }}" class="flex-shrink-0 inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-slate-900 text-white text-sm font-semibold transition">
                Tất cả
            </a>
            @foreach($newCategories as $cat)
                <a href="{{ route('shop.index', ['category' => $cat->id, 'sort' => 'newest']) }}" class="flex-shrink-0 inline-flex items-center gap-2 px-5 py-2.5 rounded-full border border-slate-200 text-slate-600 text-sm font-semibold hover:border-slate-900 hover:text-slate-900 transition">
                    {{ $cat->name }}
                    <span class="text-xs text-slate-400">({{ $cat->products_count }})</span>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Products Grid --}}
<section id="products" class="py-12 md:py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-12">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-2xl md:text-3xl font-extrabold text-slate-900">Sản phẩm mới nhất</h2>
                <p class="text-slate-500 mt-1">{{ $newProducts->count() }} sản phẩm vừa cập bến</p>
            </div>
        </div>

        @if($newProducts->count() > 0)
            {{-- Featured Product (Full Width) --}}
            @php $featured = $newProducts->first(); @endphp
            @if($featured)
            <a href="{{ route('products.show', $featured->slug) }}" class="group block mb-8 rounded-3xl overflow-hidden bg-gradient-to-r from-slate-50 to-slate-100 border border-slate-200">
                <div class="grid md:grid-cols-2">
                    <div class="relative aspect-[4/3] md:aspect-auto overflow-hidden bg-slate-200">
                        @php
                            $fImg = $featured->image_url;
                            $fImgSrc = str_starts_with($fImg, 'http') ? $fImg : asset($fImg);
                        @endphp
                        <img src="{{ $fImgSrc }}" alt="{{ $featured->name }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" loading="lazy">
                        <span class="absolute top-4 left-4 inline-flex items-center gap-1.5 px-3 py-1 bg-red-500 text-white text-xs font-bold rounded-full">
                            <span class="w-1.5 h-1.5 bg-white rounded-full animate-pulse"></span>
                            MỚI NHẤT
                        </span>
                    </div>
                    <div class="flex flex-col justify-center p-8 md:p-12">
                        <span class="text-xs font-semibold text-red-500 uppercase tracking-wider">{{ $featured->category->name ?? 'Sản phẩm mới' }}</span>
                        <h3 class="mt-3 text-2xl md:text-3xl font-extrabold text-slate-900 group-hover:text-red-500 transition-colors">{{ $featured->name }}</h3>
                        @if($featured->short_description)
                            <p class="mt-3 text-slate-500 leading-relaxed line-clamp-3">{{ $featured->short_description }}</p>
                        @endif
                        <div class="mt-6 flex items-baseline gap-3">
                            @if($featured->sale_price)
                                <span class="text-3xl font-black text-red-500">{{ number_format($featured->sale_price, 0, ',', '.') }}₫</span>
                                <span class="text-lg text-slate-400 line-through">{{ number_format($featured->price, 0, ',', '.') }}₫</span>
                            @else
                                <span class="text-3xl font-black text-slate-900">{{ number_format($featured->price, 0, ',', '.') }}₫</span>
                            @endif
                        </div>
                        <div class="mt-6">
                            <span class="inline-flex items-center gap-2 px-6 py-3 bg-slate-900 text-white font-bold rounded-xl group-hover:bg-red-500 transition-colors">
                                Xem chi tiết
                                <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </span>
                        </div>
                    </div>
                </div>
            </a>
            @endif

            {{-- Product Grid --}}
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
                @foreach($newProducts->skip(1) as $product)
                    @php
                        $img = $product->image_url;
                        $imgSrc = str_starts_with($img, 'http') ? $img : asset($img);
                        $hasDiscount = $product->sale_price && $product->sale_price < $product->price;
                        $discount = $hasDiscount ? round((1 - $product->sale_price / $product->price) * 100) : 0;
                        $avgRating = $product->reviews->avg('rating') ?? 0;
                        $reviewCount = $product->reviews->count();
                    @endphp
                    <a href="{{ route('products.show', $product->slug) }}" class="group">
                        <div class="relative aspect-[3/4] rounded-2xl overflow-hidden bg-slate-100 mb-3">
                            <img src="{{ $imgSrc }}" alt="{{ $product->name }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy">
                            @if($hasDiscount)
                                <span class="absolute top-3 left-3 px-2 py-0.5 bg-red-500 text-white text-xs font-bold rounded-full">-{{ $discount }}%</span>
                            @endif
                            <span class="absolute top-3 right-3 px-2 py-0.5 bg-slate-900 text-white text-xs font-bold rounded-full">NEW</span>
                        </div>
                        <p class="text-xs text-slate-400 font-medium uppercase tracking-wide">{{ $product->category->name ?? '' }}</p>
                        <h3 class="mt-1 text-sm font-bold text-slate-900 line-clamp-2 group-hover:text-red-500 transition-colors">{{ $product->name }}</h3>
                        @if($reviewCount > 0)
                            <div class="flex items-center gap-1 mt-1.5">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-3 h-3 {{ $i <= round($avgRating) ? 'text-amber-400' : 'text-slate-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                @endfor
                                <span class="text-[10px] text-slate-400 ml-0.5">({{ $reviewCount }})</span>
                            </div>
                        @endif
                        <div class="mt-1.5 flex items-baseline gap-2">
                            @if($hasDiscount)
                                <span class="text-base font-extrabold text-red-500">{{ number_format($product->sale_price, 0, ',', '.') }}₫</span>
                                <span class="text-xs text-slate-400 line-through">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                            @else
                                <span class="text-base font-extrabold text-slate-900">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-10 text-center">
                <a href="{{ route('shop.index', ['sort' => 'newest']) }}" class="inline-flex items-center gap-2 px-8 py-3 bg-slate-900 hover:bg-red-500 text-white font-bold rounded-xl transition shadow-lg">
                    Xem tất cả sản phẩm mới
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>
        @else
            <div class="text-center py-20">
                <div class="text-6xl mb-4">📦</div>
                <h3 class="text-xl font-bold text-slate-900">Sản phẩm mới đang cập nhật</h3>
                <p class="mt-2 text-slate-500">Hãy quay lại sau để khám phá những sản phẩm mới nhất.</p>
                <a href="{{ route('shop.index') }}" class="mt-6 inline-flex items-center gap-2 px-6 py-3 bg-slate-900 text-white font-bold rounded-xl">
                    Xem tất cả sản phẩm
                </a>
            </div>
        @endif
    </div>
</section>
@endsection
