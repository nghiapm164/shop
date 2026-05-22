@props(['product'])

@php
    $fallbackImage = asset('images/product-placeholder.svg');
    $primaryImage = $product->image_url ?? 'images/product-placeholder.svg';
    $secondaryImage = $product->secondary_image_url ?? null;

    $primarySrc = str_starts_with($primaryImage, 'http://') || str_starts_with($primaryImage, 'https://')
        ? $primaryImage
        : asset($primaryImage);

    $secondarySrc = null;
    if ($secondaryImage) {
        $secondarySrc = str_starts_with($secondaryImage, 'http://') || str_starts_with($secondaryImage, 'https://')
            ? $secondaryImage
            : asset($secondaryImage);
    }

    // Calculate discount
    $discountPercent = 0;
    if ($product->sale_price && $product->price > 0) {
        $discountPercent = round((1 - $product->sale_price / $product->price) * 100);
    }

    // Rating
    $avgRating = $product->reviews->avg('rating') ?? 0;
    $reviewCount = $product->reviews->count();

    // Badge logic
    $badgeType = null;
    $badgeLabel = '';
    if ($product->sale_price && $discountPercent > 0) {
        $badgeType = 'sale';
        $badgeLabel = '-' . $discountPercent . '%';
    }

    // Check if product is new (created within 7 days)
    $isNew = $product->created_at && $product->created_at->diffInDays(now()) <= 7;
@endphp

<article class="product-card stagger-enter">
    {{-- Image Container --}}
    <div class="card-image-wrap" x-data="{ hovered: false }" @mouseenter="hovered = true" @mouseleave="hovered = false">
        {{-- Primary Image --}}
        <img
            src="{{ $primarySrc }}"
            alt="{{ $product->name }}"
            loading="lazy"
            onerror="this.onerror=null;this.src='{{ $fallbackImage }}';"
            x-show="!hovered || !{{ $secondarySrc ? 'true' : 'false' }}"
        >

        {{-- Secondary Image (on hover) --}}
        @if($secondarySrc)
            <img
                src="{{ $secondarySrc }}"
                alt="{{ $product->name }}"
                class="absolute inset-0 h-full w-full object-cover transition-opacity duration-500"
                loading="lazy"
                onerror="this.onerror=null;this.src='{{ $fallbackImage }}';"
                x-show="hovered"
                x-cloak
            >
        @endif

        {{-- Badges --}}
        <div class="absolute top-3 left-3 z-10 flex flex-col gap-1.5">
            @if($badgeType === 'sale')
                <span class="badge badge-sale">{{ $badgeLabel }}</span>
            @endif
            @if($isNew && $badgeType !== 'sale')
                <span class="badge badge-new">NEW</span>
            @endif
        </div>

        {{-- Wishlist Button --}}
        <div class="absolute top-3 right-3 z-10">
            @auth
                @php
                    $isWishlisted = auth()->user()->wishlist()->where('product_id', $product->id)->exists();
                @endphp
                <form action="{{ route('wishlist.toggle', $product->id) }}" method="POST" class="inline" x-data="{ wishlisted: {{ $isWishlisted ? 'true' : 'false' }} }">
                    @csrf
                    <button type="submit" 
                            class="btn-wishlist"
                            @click.prevent="
                                fetch('{{ route('wishlist.toggle', $product->id) }}', {
                                    method: 'POST',
                                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                                }).then(r => r.json()).then(d => {
                                    wishlisted = d.action === 'added';
                                    window.dispatchEvent(new CustomEvent('notify', { detail: { message: d.message || (wishlisted ? 'Đã thêm vào yêu thích' : 'Đã bỏ yêu thích'), type: 'success' } }));
                                })
                            "
                            :title="wishlisted ? 'Bỏ yêu thích' : 'Thêm vào yêu thích'">
                        <svg class="w-4 h-4" :fill="wishlisted ? 'currentColor' : 'none'" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                        </svg>
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn-wishlist" title="Đăng nhập để yêu thích">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                    </svg>
                </a>
            @endauth
        </div>

        {{-- Quick Add to Cart (appears on hover) --}}
        <div class="card-quick-actions">
            <a href="{{ route('products.show', $product->slug) }}" class="btn-quick-add">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                Xem nhanh
            </a>
        </div>
    </div>

    {{-- Card Body --}}
    <div class="card-body">
        {{-- Category --}}
        <p class="text-[11px] font-semibold uppercase tracking-[0.08em] text-slate-400 mb-1.5">
            {{ $product->category->name ?? 'Thời trang' }}
        </p>

        {{-- Product Name --}}
        <a href="{{ route('products.show', $product->slug) }}" class="block">
            <h3 class="text-sm font-bold text-slate-900 leading-snug line-clamp-2 group-hover:text-red-500 transition-colors duration-200 min-h-[2.5rem]">
                {{ $product->name }}
            </h3>
        </a>

        {{-- Rating --}}
        @if($reviewCount > 0)
            <div class="flex items-center gap-1.5 mt-2">
                <div class="star-rating">
                    @for($i = 1; $i <= 5; $i++)
                        <span class="star {{ $i <= round($avgRating) ? 'filled' : '' }}">★</span>
                    @endfor
                </div>
                <span class="text-[11px] text-slate-400 font-medium">({{ $reviewCount }})</span>
            </div>
        @endif

        {{-- Color Variants --}}
        @if($product->colors->count() > 0)
            <div class="flex items-center gap-1.5 mt-2.5">
                @foreach($product->colors->take(5) as $color)
                    <span class="h-3.5 w-3.5 rounded-full border border-slate-200 shadow-sm" 
                          style="background-color: {{ $color->hex_code }}" 
                          title="{{ $color->name }}"></span>
                @endforeach
                @if($product->colors->count() > 5)
                    <span class="text-[10px] text-slate-400 font-medium">+{{ $product->colors->count() - 5 }}</span>
                @endif
            </div>
        @endif

        {{-- Price --}}
        <div class="flex items-baseline gap-2 mt-3">
            @if($product->sale_price)
                <span class="text-base font-extrabold text-red-500">
                    {{ number_format($product->sale_price, 0, ',', '.') }}₫
                </span>
                <span class="text-xs text-slate-400 line-through font-medium">
                    {{ number_format($product->price, 0, ',', '.') }}₫
                </span>
            @else
                <span class="text-base font-extrabold text-slate-900">
                    {{ number_format($product->price, 0, ',', '.') }}₫
                </span>
            @endif
        </div>
    </div>

    {{-- Full card link overlay --}}
    <a href="{{ route('products.show', $product->slug) }}" class="absolute inset-0 z-0" aria-label="{{ $product->name }}"></a>
</article>