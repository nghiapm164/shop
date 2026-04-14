@extends('layouts.app')

@section('content')
@php
    $placeholder = asset('images/product-placeholder.svg');

    $gallery = $product->images
        ->map(function ($image) {
            if (\Illuminate\Support\Str::startsWith($image->image_path, ['http://', 'https://'])) {
                return $image->image_path;
            }

            return asset('storage/' . ltrim($image->image_path, '/'));
        })
        ->values();

    if ($gallery->isEmpty()) {
        $gallery = collect([$placeholder]);
    }

    $colorOptions = $product->variants->pluck('color')->filter()->unique('id')->values();
    $sizeOptions = $product->variants->pluck('size')->filter()->unique('id')->values();
@endphp

<div class="pb-14">
    <div class="max-w-7xl mx-auto px-4 py-5">
        <div class="fashion-section px-4 py-3 flex items-center gap-2 text-sm text-slate-500">
            <a href="{{ route('home') }}" class="hover:text-red-500">Trang chủ</a>
            <span>/</span>
            <a href="{{ route('shop.index') }}" class="hover:text-red-500">Sản phẩm</a>
            @if ($product->category)
                <span>/</span>
                <a href="{{ route('shop.index', ['category' => $product->category->id]) }}" class="hover:text-red-500">{{ $product->category->name }}</a>
            @endif
            <span>/</span>
            <span class="font-semibold text-slate-900 line-clamp-1">{{ $product->name }}</span>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-start">
            <div x-data="{ current: 0, open: false, images: @js($gallery) }" class="space-y-4">
                <div class="relative fashion-section overflow-hidden aspect-square cursor-zoom-in" @click="open = true">
                    <img :src="images[current]" alt="{{ $product->name }}" class="w-full h-full object-cover" onerror="this.onerror=null;this.src='{{ $placeholder }}';">

                    @if ($product->sale_price && $product->sale_price < $product->price)
                        <div class="absolute top-3 left-3 chip border-red-200 bg-red-50 text-red-600">
                            -{{ round((1 - $product->sale_price / $product->price) * 100) }}%
                        </div>
                    @endif
                </div>

                <div class="grid grid-cols-5 gap-2 md:gap-3">
                    <template x-for="(img, index) in images" :key="index">
                        <button
                            type="button"
                            @click="current = index"
                            :class="current === index ? 'ring-2 ring-red-600' : 'ring-1 ring-slate-200'"
                            class="aspect-square overflow-hidden rounded-xl bg-slate-100 transition-all"
                        >
                            <img :src="img" class="w-full h-full object-cover" alt="Thumbnail" onerror="this.onerror=null;this.src='{{ $placeholder }}';">
                        </button>
                    </template>
                </div>

                <div x-show="open" x-transition.opacity class="fixed inset-0 z-50 bg-black/90 p-4 flex items-center justify-center" @click.self="open = false">
                    <button type="button" class="absolute top-6 right-6 text-white text-3xl" @click="open = false">&times;</button>
                    <img :src="images[current]" class="max-h-[90vh] max-w-[90vw] object-contain" alt="{{ $product->name }}" onerror="this.onerror=null;this.src='{{ $placeholder }}';">
                </div>
            </div>

            <div class="space-y-5">
                <div class="fashion-section p-6 md:p-8 space-y-3">
                    <p class="text-sm text-slate-500">
                        {{ $product->brand?->name ?? 'Thương hiệu cập nhật sau' }}
                    </p>
                    <h1 class="fashion-title text-3xl leading-tight">{{ $product->name }}</h1>
                    @if ($product->short_description)
                        <p class="text-slate-600 leading-relaxed">{{ $product->short_description }}</p>
                    @endif
                </div>

                <div class="fashion-section p-6 md:p-8">
                    @livewire('add-to-cart', ['product' => $product])
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div class="rounded-2xl border border-slate-200 bg-white p-4">
                        <p class="text-xs uppercase tracking-wide text-slate-500">Giao hàng</p>
                        <p class="mt-1 font-semibold text-slate-900">Nội thành 2h</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-4">
                        <p class="text-xs uppercase tracking-wide text-slate-500">Đổi trả</p>
                        <p class="mt-1 font-semibold text-slate-900">Trong 7 ngày</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-4">
                        <p class="text-xs uppercase tracking-wide text-slate-500">Cam kết</p>
                        <p class="mt-1 font-semibold text-slate-900">Hàng chính hãng</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 mt-10">
        <div class="fashion-section p-6 md:p-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900">Mô tả sản phẩm</h2>
                    <div class="mt-4 prose prose-sm max-w-none text-slate-700 leading-relaxed">
                        @if ($product->description)
                            {!! nl2br(e($product->description)) !!}
                        @else
                            <p>Chưa có mô tả chi tiết cho sản phẩm này.</p>
                        @endif
                    </div>
                </div>

                <div class="space-y-4">
                    <h2 class="text-2xl font-bold text-slate-900">Thông tin thêm</h2>
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-sm text-slate-500">SKU</p>
                        <p class="font-semibold text-slate-900">{{ $product->sku }}</p>
                    </div>
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-sm text-slate-500">Danh mục</p>
                        <p class="font-semibold text-slate-900">{{ $product->category?->name ?? 'Đang cập nhật' }}</p>
                    </div>
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-sm text-slate-500">Màu sắc</p>
                        <p class="font-semibold text-slate-900">
                            {{ $colorOptions->pluck('name')->implode(', ') ?: 'Đang cập nhật' }}
                        </p>
                    </div>
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-sm text-slate-500">Kích cỡ</p>
                        <p class="font-semibold text-slate-900">
                            {{ $sizeOptions->map(fn($size) => $size->short_label)->implode(', ') ?: 'Đang cập nhật' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 mt-10">
        <div class="fashion-section p-6 md:p-8">
            @livewire('review-list', ['product' => $product])
        </div>
    </div>

    @if ($related->isNotEmpty())
        <div class="max-w-7xl mx-auto px-4 mt-12">
            <div class="flex items-end justify-between mb-6">
                <h3 class="fashion-title text-3xl">Sản phẩm liên quan</h3>
                <div class="flex items-center gap-2">
                    <button
                        type="button"
                        class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-300 text-slate-700 hover:border-red-400 hover:text-red-500 transition"
                        x-data
                        @click="$dispatch('related-slide', { dir: -1 })"
                        aria-label="Trượt trái"
                    >
                        <span aria-hidden="true">&larr;</span>
                    </button>
                    <button
                        type="button"
                        class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-300 text-slate-700 hover:border-red-400 hover:text-red-500 transition"
                        x-data
                        @click="$dispatch('related-slide', { dir: 1 })"
                        aria-label="Trượt phải"
                    >
                        <span aria-hidden="true">&rarr;</span>
                    </button>
                    <a href="{{ route('shop.index', ['category' => $product->category_id]) }}" class="btn-secondary">Xem thêm</a>
                </div>
            </div>

            <div
                x-data="{
                    move(dir) {
                        const track = this.$refs.track;
                        if (!track) return;
                        const distance = Math.max(track.clientWidth * 0.85, 260);
                        track.scrollBy({ left: dir * distance, behavior: 'smooth' });
                    }
                }"
                @related-slide.window="move($event.detail.dir)"
            >
                <div
                    x-ref="track"
                    class="flex gap-6 overflow-x-auto pb-2 snap-x snap-mandatory"
                >
                    @foreach ($related as $relatedProduct)
                        <div class="min-w-[85%] sm:min-w-[58%] lg:min-w-[31%] xl:min-w-[23%] snap-start">
                            <x-product-card :product="$relatedProduct" />
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
