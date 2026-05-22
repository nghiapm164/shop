@extends('layouts.app')

@section('title', 'Sản phẩm yêu thích')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-12 py-8 md:py-10">
    {{-- Header --}}
    <div class="mb-8">
        <nav class="flex items-center gap-2 text-sm text-gray-400 mb-4">
            <a href="{{ route('home') }}" class="hover:text-gray-600 transition-colors">Trang chủ</a>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            <span class="text-gray-900 font-medium">Yêu thích</span>
        </nav>
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 flex items-center gap-3">
                    <svg class="w-7 h-7 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.17a4 4 0 115.656 5.656L10 17.657l-6.828-6.828a4 4 0 010-5.657z"/></svg>
                    Sản phẩm yêu thích
                </h1>
                <p class="text-gray-500 mt-1">
                    @if($wishlistProducts->total() > 0)
                        Bạn có <span class="font-semibold text-gray-900">{{ $wishlistProducts->total() }}</span> sản phẩm yêu thích
                    @else
                        Danh sách sản phẩm bạn đã lưu lại
                    @endif
                </p>
            </div>
            @if($wishlistProducts->count() > 0)
                <a href="{{ route('shop.index') }}" class="hidden sm:inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-gray-200 text-sm font-semibold text-gray-700 hover:bg-gray-50 hover:border-gray-300 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    Thêm sản phẩm
                </a>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 flex items-center gap-2">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif

    @if($wishlistProducts->count() > 0)
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-5">
            @foreach($wishlistProducts as $product)
                @php
                    $imgUrl = $product->image_url ?? 'images/product-placeholder.svg';
                    $imgSrc = str_starts_with($imgUrl, 'http://') || str_starts_with($imgUrl, 'https://') ? $imgUrl : asset($imgUrl);
                    $fallbackImage = asset('images/product-placeholder.svg');
                    $discountPercent = ($product->sale_price && $product->price > 0) ? round((1 - $product->sale_price / $product->price) * 100) : 0;
                    $avgRating = $product->reviews->avg('rating') ?? 0;
                    $reviewCount = $product->reviews->count();
                @endphp
                <article class="product-card group">
                    {{-- Image --}}
                    <div class="card-image-wrap">
                        <a href="{{ route('products.show', $product->slug) }}">
                            <img src="{{ $imgSrc }}" alt="{{ $product->name }}" loading="lazy"
                                 onerror="this.onerror=null;this.src='{{ $fallbackImage }}';">
                        </a>

                        {{-- Badge --}}
                        @if($discountPercent > 0)
                            <div class="absolute top-3 left-3 z-10">
                                <span class="badge badge-sale">-{{ $discountPercent }}%</span>
                            </div>
                        @endif

                        {{-- Remove Button --}}
                        <div class="absolute top-3 right-3 z-10">
                            <form method="POST" action="{{ route('wishlist.destroy', $product) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="btn-wishlist"
                                        title="Xóa khỏi yêu thích"
                                        onclick="return confirm('Xóa sản phẩm này khỏi danh sách yêu thích?')">
                                    <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.17a4 4 0 115.656 5.656L10 17.657l-6.828-6.828a4 4 0 010-5.657z"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Body --}}
                    <div class="card-body">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.08em] text-gray-400 mb-1.5">
                            {{ $product->category->name ?? 'Thời trang' }}
                        </p>
                        <a href="{{ route('products.show', $product->slug) }}">
                            <h3 class="text-sm font-bold text-gray-900 leading-snug line-clamp-2 group-hover:text-red-500 transition-colors min-h-[2.5rem]">
                                {{ $product->name }}
                            </h3>
                        </a>

                        @if($reviewCount > 0)
                            <div class="flex items-center gap-1.5 mt-2">
                                <div class="star-rating">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="star {{ $i <= round($avgRating) ? 'filled' : '' }}">★</span>
                                    @endfor
                                </div>
                                <span class="text-[11px] text-gray-400 font-medium">({{ $reviewCount }})</span>
                            </div>
                        @endif

                        <div class="flex items-baseline gap-2 mt-3">
                            @if($product->sale_price)
                                <span class="text-base font-extrabold text-red-500">{{ number_format($product->sale_price, 0, ',', '.') }}₫</span>
                                <span class="text-xs text-gray-400 line-through font-medium">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                            @else
                                <span class="text-base font-extrabold text-gray-900">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                            @endif
                        </div>
                    </div>

                    <a href="{{ route('products.show', $product->slug) }}" class="absolute inset-0 z-0" aria-label="{{ $product->name }}"></a>
                </article>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($wishlistProducts->hasPages())
            <x-pagination :paginator="$wishlistProducts" />
        @endif
    @else
        {{-- Empty State --}}
        <div class="flex flex-col items-center justify-center py-20 text-center">
            <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center mb-6">
                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">Chưa có sản phẩm yêu thích</h3>
            <p class="text-sm text-gray-500 mb-6 max-w-sm">
                Hãy duyệt sản phẩm và nhấn vào biểu tượng trái tim để lưu lại những sản phẩm bạn yêu thích!
            </p>
            <a href="{{ route('shop.index') }}" class="btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                Khám phá sản phẩm
            </a>
        </div>
    @endif
</div>
@endsection