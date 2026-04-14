@props(['product'])

@php
    $fallbackImage = asset('images/product-placeholder.svg');
    $primaryImage = $product->image_url ?? 'images/product-placeholder.svg';
    $secondaryImage = $product->secondary_image_url;

    $primarySrc = str_starts_with($primaryImage, 'http://') || str_starts_with($primaryImage, 'https://')
        ? $primaryImage
        : asset($primaryImage);

    $secondarySrc = null;
    if ($secondaryImage) {
        $secondarySrc = str_starts_with($secondaryImage, 'http://') || str_starts_with($secondaryImage, 'https://')
            ? $secondaryImage
            : asset($secondaryImage);
    }
@endphp

<article class="group h-full overflow-hidden rounded-[1.8rem] border border-[#eadfd5] bg-white shadow-[0_20px_45px_-30px_rgba(92,64,42,0.55)] transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_30px_60px_-34px_rgba(92,64,42,0.65)] stagger-enter">
    <div class="relative overflow-hidden bg-[#f6f0e9] aspect-[3/4]" x-data="{ showSecond: false }">
        <img
            x-show="!showSecond"
            src="{{ $primarySrc }}"
            alt="{{ $product->name }}"
            class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105"
            loading="lazy"
            onerror="this.onerror=null;this.src='{{ $fallbackImage }}';"
            @mouseenter="showSecond = true"
            @mouseleave="showSecond = false"
        >

        @if($secondarySrc)
            <img
                x-show="showSecond"
                src="{{ $secondarySrc }}"
                alt="{{ $product->name }}"
                class="absolute inset-0 h-full w-full object-cover transition-transform duration-700 group-hover:scale-105"
                loading="lazy"
                onerror="this.onerror=null;this.src='{{ $fallbackImage }}';"
            >
        @endif

        <button type="button" aria-label="Yêu thích" class="absolute left-4 top-4 z-10 inline-flex h-9 w-9 items-center justify-center rounded-full bg-[#d8b294] text-white shadow-md transition-transform duration-300 hover:scale-105">
            <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                <path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.17a4 4 0 115.656 5.656L10 17.657l-6.828-6.828a4 4 0 010-5.657z"></path>
            </svg>
        </button>

        @if($product->sale_price)
            <div class="absolute right-4 top-4 z-10">
                <span class="rounded-full bg-rose-500 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide text-white">-{{ round((1 - $product->sale_price / $product->price) * 100) }}%</span>
            </div>
        @endif

        <div class="absolute inset-x-0 bottom-0 h-52 bg-gradient-to-t from-black/85 via-black/68 to-transparent"></div>

        @if($product->colors->count() > 0)
            <div class="absolute left-0 right-0 bottom-[122px] z-10 flex items-center justify-center gap-2">
                @foreach($product->colors->take(4) as $color)
                    <span class="h-4 w-4 rounded-full border-2 border-white/90 shadow" style="background-color: {{ $color->hex_code }}" title="{{ $color->name }}"></span>
                @endforeach
                @if($product->colors->count() > 4)
                    <span class="rounded-full bg-white/85 px-2 py-0.5 text-[10px] font-semibold text-slate-700">+{{ $product->colors->count() - 4 }}</span>
                @endif
            </div>
        @endif

        <div class="absolute inset-x-0 bottom-0 z-10 bg-black/32 px-4 pb-4 pt-3">
            <h3 class="line-clamp-1 text-[15px] font-bold leading-tight text-[#ffd2a6]" style="font-family: 'Tomato', 'Nunito Sans', sans-serif;">
                {{ $product->name }}
            </h3>

            <div class="mt-2 inline-flex items-center rounded-full bg-white px-2.5 py-1 text-[14px] font-extrabold leading-none text-slate-900" style="font-family: 'Tomato', 'Nunito Sans', sans-serif;">
                @if($product->sale_price)
                    {{ number_format($product->sale_price, 0) }}₫
                @else
                    {{ number_format($product->price, 0) }}₫
                @endif
            </div>

            <p class="mt-2 line-clamp-2 text-[12px] leading-snug text-white/50" style="font-family: 'Tomato', 'Nunito Sans', sans-serif;">
                {{ \Illuminate\Support\Str::limit(strip_tags($product->description ?? ($product->category->name ?? 'Sản phẩm thời trang hiện đại dành cho bạn.')), 88) }}
            </p>
        </div>

        <a href="{{ route('products.show', $product->slug) }}" class="absolute inset-0"></a>
    </div>
</article>
