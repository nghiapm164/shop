@props(['product'])

<div class="group bg-white rounded-lg border border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-300">
    <!-- Image Container -->
    <div class="relative overflow-hidden bg-gray-100 h-80 flex items-center justify-center" x-data="{ showSecond: false }">
        <!-- Primary Image -->
        <img x-show="!showSecond" 
            src="{{ asset($product->image_url ?? 'images/placeholder.jpg') }}" 
            alt="{{ $product->name }}"
            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300 lazy"
            loading="lazy"
            @mouseenter="showSecond = true"
            @mouseleave="showSecond = false">

        <!-- Secondary Image Hover -->
        @if($product->secondary_image_url)
            <img x-show="showSecond" 
                src="{{ asset($product->secondary_image_url) }}" 
                alt="{{ $product->name }}"
                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300 absolute inset-0"
                loading="lazy">
        @endif

        <!-- Badges -->
        <div class="absolute top-4 left-4 flex flex-col gap-2">
            <!-- NEW Badge (within 7 days) -->
            @if($product->created_at->diffInDays(now()) <= 7)
                <span class="badge-success animate-pulse">NEW</span>
            @endif

            <!-- SALE Badge -->
            @if($product->sale_price)
                <span class="badge-warning">SALE</span>
                <span class="badge bg-red-50 text-red-600 text-xs font-bold">
                    -{{ round((1 - $product->sale_price / $product->price) * 100) }}%
                </span>
            @endif

            <!-- HOT Badge (high views/sales) -->
            @if($product->view_count > 1000 || $product->sales_count > 100)
                <span class="badge-primary animate-bounce">🔥 HOT</span>
            @endif
        </div>

        <!-- Quick View Button (on hover) -->
        <button class="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition-all duration-300 flex items-center justify-center opacity-0 group-hover:opacity-100">
            <a href="{{ route('products.show', $product->slug) }}" 
                class="bg-white text-gray-900 px-6 py-2 rounded-lg font-semibold hover:bg-red-500 hover:text-white transition-all">
                Xem chi tiết
            </a>
        </button>
    </div>

    <!-- Product Info -->
    <div class="p-4">
        <!-- Category -->
        <div class="mb-2">
            <a href="#" class="text-xs text-gray-500 hover:text-red-500 transition-all">
                {{ $product->category->name ?? 'Uncategorized' }}
            </a>
        </div>

        <!-- Product Name -->
        <a href="{{ route('products.show', $product->slug) }}" 
            class="block text-sm font-semibold text-gray-900 line-clamp-2 hover:text-red-500 transition-colors mb-2">
            {{ $product->name }}
        </a>

        <!-- Rating (if available) -->
        @if($product->average_rating)
            <div class="flex items-center gap-2 mb-2">
                <div class="flex items-center">
                    @for($i = 0; $i < 5; $i++)
                        @if($i < floor($product->average_rating))
                            <svg class="w-3 h-3 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                        @else
                            <svg class="w-3 h-3 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                        @endif
                    @endfor
                </div>
                <span class="text-xs text-gray-500">({{ $product->review_count ?? 0 }})</span>
            </div>
        @endif

        <!-- Price -->
        <div class="flex items-center gap-2 mb-4">
            @if($product->sale_price)
                <span class="text-lg font-bold text-red-500">{{ number_format($product->sale_price, 0) }}₫</span>
                <span class="text-sm text-gray-400 line-through">{{ number_format($product->price, 0) }}₫</span>
            @else
                <span class="text-lg font-bold text-gray-900">{{ number_format($product->price, 0) }}₫</span>
            @endif
        </div>

        <!-- Colors (if available) -->
        @if($product->colors->count() > 0)
            <div class="flex items-center gap-2 mb-4">
                @foreach($product->colors->take(5) as $color)
                    <div class="w-6 h-6 rounded-full border-2 border-gray-300 cursor-pointer hover:border-gray-900 transition-all"
                        style="background-color: {{ $color->hex_code }}"
                        title="{{ $color->name }}"></div>
                @endforeach
                @if($product->colors->count() > 5)
                    <span class="text-xs text-gray-500">+{{ $product->colors->count() - 5 }}</span>
                @endif
            </div>
        @endif

        <!-- Action Buttons -->
        <div class="flex gap-2">
            <!-- Add to Cart Button -->
            <button wire:click="addToCart({{ $product->id }})"
                class="flex-1 btn-primary text-sm">
                <span class="flex items-center justify-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Thêm giỏ
                </span>
            </button>

            <!-- Wishlist Button -->
            <button wire:click="toggleWishlist({{ $product->id }})"
                class="btn-secondary text-sm p-2 flex items-center justify-center"
                :class="{ 'bg-red-50 border-red-300': isInWishlist }">
                <svg class="w-4 h-4" :fill="isInWishlist ? 'currentColor' : 'none'" 
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
            </button>
        </div>
    </div>
</div>
