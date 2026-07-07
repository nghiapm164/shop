@props(['product', 'variant' => 'default', 'index' => null])

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

@if($variant === 'minimal')
<article x-data="{ hovered: false }" @mouseenter="hovered = true" @mouseleave="hovered = false" class="group w-48 md:w-64 lg:w-72 shrink-0 snap-start flex flex-col gap-4">
    <div class="relative aspect-[3/4] overflow-hidden rounded-none bg-slate-50 border border-slate-200 transition-all duration-300 group-hover:border-brand-ink">
        <a href="{{ route('products.show', $product->slug) }}" class="absolute inset-0 z-30" aria-label="{{ $product->name }}"></a>
        
        <img src="{{ $primarySrc }}" alt="{{ $product->name }}" class="absolute inset-0 h-full w-full object-cover transition-transform duration-700 z-10 group-hover:scale-110" loading="lazy" onerror="this.onerror=null;this.src='{{ $fallbackImage }}';">

        @if($badgeType === 'sale')
            <div class="absolute top-4 left-4 z-40 flex items-center justify-center rounded-none bg-brand-primary px-3 py-1.5 text-[10px] font-black tracking-widest text-white">-{{ $discountPercent }}%</div>
        @elseif($isNew)
            <div class="absolute top-4 left-4 z-40 flex items-center justify-center rounded-none bg-brand-ink px-3 py-1.5 text-[10px] font-black tracking-widest text-white">MỚI</div>
        @endif

        <div class="absolute top-4 right-4 z-50 translate-x-4 opacity-0 transition-all duration-300 group-hover:translate-x-0 group-hover:opacity-100">
            @auth
                @php
                    $isWishlisted = auth()->user()->wishlist()->where('product_id', $product->id)->exists();
                    $wishlistedJson = $isWishlisted ? 'true' : 'false';
                @endphp
                <div x-data="{ wishlisted: {!! $wishlistedJson !!}, loading: false }">
                    <button type="button" @click.prevent.stop="
                            loading = true;
                            fetch('{{ route('wishlist.toggle', $product->id) }}', {
                                method: 'POST',
                                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                            }).then(r => r.json()).then(d => {
                                wishlisted = d.action === 'added';
                                loading = false;
                                window.dispatchEvent(new CustomEvent('notify', { detail: { message: d.message || (wishlisted ? 'Đã thêm vào yêu thích' : 'Đã bỏ yêu thích'), type: 'success' } }));
                                window.dispatchEvent(new CustomEvent('wishlist-updated'));
                            }).catch(() => { loading = false; })
                        " 
                        class="flex h-10 w-10 items-center justify-center rounded-full bg-white text-slate-900 shadow-md transition-colors hover:text-red-500" :class="wishlisted ? 'text-red-500' : ''">
                        <svg class="h-5 w-5" :fill="wishlisted ? 'currentColor' : 'none'" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                    </button>
                </div>
            @else
                <a href="{{ route('login') }}" class="flex h-10 w-10 items-center justify-center rounded-full bg-white text-slate-900 shadow-md transition-colors hover:text-red-500 relative z-50">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                </a>
            @endauth
        </div>

        <div class="absolute bottom-4 left-4 right-4 z-40 translate-y-4 opacity-0 transition-all duration-300 group-hover:translate-y-0 group-hover:opacity-100">
            <a href="{{ route('products.show', $product->slug) }}" class="flex w-full items-center justify-center gap-2 rounded-none bg-brand-ink/95 backdrop-blur-sm py-3 text-sm font-bold uppercase tracking-wider text-white transition-colors hover:bg-brand-primary relative z-50">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                Xem chi tiết
            </a>
        </div>
    </div>
    
    <div class="px-2 text-left">
        <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-2">{{ $product->category->name ?? 'Thời trang' }}</p>
        <a href="{{ route('products.show', $product->slug) }}">
            <h3 class="text-sm md:text-base font-bold uppercase text-brand-ink transition-colors group-hover:text-brand-primary line-clamp-2 leading-tight tracking-tight mt-1">{{ $product->name }}</h3>
        </a>
        <div class="mt-2.5 flex items-end justify-between">
            <div class="flex items-baseline gap-2">
                @if($product->sale_price)
                    <span class="text-base font-black text-brand-primary">{{ number_format($product->sale_price, 0, ',', '.') }}₫</span>
                    <span class="text-xs font-bold text-slate-400 line-through">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                @else
                    <span class="text-base font-black text-brand-ink">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                @endif
            </div>
            @if($product->colors->count() > 0)
                <div class="flex items-center gap-1">
                    @foreach($product->colors->take(3) as $color)
                        <span class="h-3.5 w-3.5 rounded-full border border-slate-200 shadow-sm" style="background-color: {{ $color->hex_code }}" title="{{ $color->name }}"></span>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</article>

@elseif($variant === 'bold')
<article x-data="{ hovered: false }" @mouseenter="hovered = true" @mouseleave="hovered = false" class="group relative w-48 md:w-64 lg:w-72 shrink-0 snap-start rounded-none bg-white border border-slate-200 transition-all duration-300 hover:border-brand-ink overflow-hidden">
    @if($index)
        <div class="absolute top-4 left-4 z-40 flex h-10 w-10 md:h-12 md:w-12 items-center justify-center rounded-none bg-brand-primary text-lg md:text-xl font-black text-white">
            #{{ $index }}
        </div>
    @endif
    <div class="relative aspect-[4/5] overflow-hidden bg-slate-50">
        <a href="{{ route('products.show', $product->slug) }}" class="absolute inset-0 z-30" aria-label="{{ $product->name }}"></a>
        <img src="{{ $primarySrc }}" alt="{{ $product->name }}" class="absolute inset-0 h-full w-full object-cover transition-transform duration-700 z-10 group-hover:scale-110" loading="lazy" onerror="this.onerror=null;this.src='{{ $fallbackImage }}';">
        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-slate-900/0 to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100 z-20"></div>
        @if($badgeType === 'sale')
            <div class="absolute top-4 right-4 z-20 rounded-none bg-brand-ink px-3 py-1.5 text-[10px] font-black tracking-widest text-white">-{{ $discountPercent }}%</div>
        @endif
        <div class="absolute bottom-4 left-4 right-4 z-40 translate-y-4 opacity-0 transition-all duration-300 group-hover:translate-y-0 group-hover:opacity-100">
            <a href="{{ route('products.show', $product->slug) }}" class="flex w-full items-center justify-center gap-2 rounded-none bg-brand-primary py-3 text-sm font-bold uppercase tracking-wider text-white transition-colors hover:bg-brand-ink">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                Khám phá ngay
            </a>
        </div>
    </div>
    <div class="p-5 md:p-6 bg-white relative">
        <p class="text-[10px] font-black text-brand-primary uppercase tracking-[0.2em] mb-2">{{ $product->category->name ?? 'Thời trang' }}</p>
        <a href="{{ route('products.show', $product->slug) }}" class="block min-h-[3rem]">
            <h3 class="text-base md:text-lg font-bold uppercase text-brand-ink group-hover:text-brand-primary transition-colors line-clamp-2 leading-tight tracking-tight">{{ $product->name }}</h3>
        </a>
        <div class="mt-4 flex items-center justify-between">
            <div class="flex items-baseline gap-2">
                @if($product->sale_price)
                    <span class="text-lg md:text-xl font-black text-brand-primary">{{ number_format($product->sale_price, 0, ',', '.') }}₫</span>
                    <span class="text-xs font-bold text-slate-400 line-through">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                @else
                    <span class="text-lg md:text-xl font-black text-brand-ink">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                @endif
            </div>
            @if($product->colors->count() > 0)
                <div class="flex items-center gap-1">
                    @foreach($product->colors->take(3) as $color)
                        <span class="h-3 w-3 rounded-full shadow-[inset_0_1px_3px_rgba(0,0,0,0.1)]" style="background-color: {{ $color->hex_code }}" title="{{ $color->name }}"></span>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</article>

@else
<article class="product-card stagger-enter w-full">
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
                    $wishlistedJson = $isWishlisted ? 'true' : 'false';
                @endphp
                <div x-data="{ wishlisted: {!! $wishlistedJson !!}, loading: false }">
                    <button type="button"
                            class="btn-wishlist"
                            :class="wishlisted ? 'text-red-500' : ''"
                            :disabled="loading"
                            @click="
                                loading = true;
                                fetch('{{ route('wishlist.toggle', $product->id) }}', {
                                    method: 'POST',
                                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                                }).then(r => r.json()).then(d => {
                                    wishlisted = d.action === 'added';
                                    loading = false;
                                    window.dispatchEvent(new CustomEvent('notify', { detail: { message: d.message || (wishlisted ? 'Đã thêm vào yêu thích' : 'Đã bỏ yêu thích'), type: 'success' } }));
                                    window.dispatchEvent(new CustomEvent('wishlist-updated'));
                                }).catch(() => { loading = false; })
                            "
                            :title="wishlisted ? 'Bỏ yêu thích' : 'Thêm vào yêu thích'">
                        <svg class="w-4 h-4" :fill="wishlisted ? 'currentColor' : 'none'" :stroke="wishlisted ? 'none' : 'currentColor'" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                        </svg>
                    </button>
                </div>
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
@endif
