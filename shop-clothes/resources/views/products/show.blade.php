@extends('layouts.app')

@section('content')
<div class="bg-white">
    <!-- Breadcrumb -->
    <div class="max-w-7xl mx-auto px-4 py-4 border-b border-gray-200">
        <div class="flex items-center gap-2 text-sm">
            <a href="{{ route('home') }}" class="text-gray-600 hover:text-red-600">Trang chủ</a>
            <span class="text-gray-400">/</span>
            @if ($product->category)
                <a href="{{ route('products.index', ['category' => $product->category->id]) }}" class="text-gray-600 hover:text-red-600">
                    {{ $product->category->name }}
                </a>
                <span class="text-gray-400">/</span>
            @endif
            <span class="text-gray-900 font-semibold">{{ $product->name }}</span>
        </div>
    </div>

    <!-- Product Detail Section -->
    <div class="max-w-7xl mx-auto px-4 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- LEFT COLUMN - Image Gallery -->
            <div x-data="{ mainImage: 0, showLightbox: false }" class="space-y-4">
                <!-- Main Image -->
                <div class="relative bg-gray-100 rounded-lg overflow-hidden aspect-square cursor-zoom-in" 
                     @click="showLightbox = true"
                     x-transition>
                    @if ($product->images->count() > 0)
                        <img
                            :src="$el.querySelector('[data-image-' + mainImage + ']')?.src || '{{ asset('images/placeholder.jpg') }}'"
                            alt="{{ $product->name }}"
                            class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gray-200">
                            <svg class="w-24 h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    @endif

                    <!-- Sale Badge -->
                    @if ($product->sale_price && $product->sale_price < $product->price)
                        <div class="absolute top-3 right-3 bg-red-600 text-white px-3 py-1 rounded-full text-sm font-bold">
                            SALE
                        </div>
                    @elseif ($product->is_featured)
                        <div class="absolute top-3 right-3 bg-blue-600 text-white px-3 py-1 rounded-full text-sm font-bold">
                            NEW
                        </div>
                    @endif

                    <!-- Zoom Icon -->
                    <div class="absolute inset-0 flex items-center justify-center bg-black/0 hover:bg-black/20 transition-colors opacity-0 hover:opacity-100">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM13 10H7" />
                        </svg>
                    </div>
                </div>

                <!-- Image Thumbnails -->
                @if ($product->images->count() > 0)
                    <div class="flex gap-2 overflow-x-auto">
                        @foreach ($product->images as $index => $image)
                            <button
                                @click="mainImage = {{ $index }}"
                                :class="{ 'ring-2 ring-red-600': mainImage === {{ $index }} }"
                                class="flex-shrink-0 w-20 h-20 rounded-lg border-2 border-transparent hover:border-gray-300 overflow-hidden transition-all">
                                <img
                                    src="{{ asset('storage/' . $image->image_path) }}"
                                    alt="Thumbnail {{ $index + 1 }}"
                                    data-image-{{ $index }}="{{ asset('storage/' . $image->image_path) }}"
                                    class="w-full h-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif

                <!-- Lightbox Modal -->
                @if ($product->images->count() > 0)
                    <div x-show="showLightbox" 
                         @click.self="showLightbox = false"
                         class="fixed inset-0 bg-black/90 z-50 flex items-center justify-center p-4"
                         x-transition>
                        <button @click="showLightbox = false" class="absolute top-6 right-6 text-white text-4xl">✕</button>
                        <img
                            :src="$el.closest('[x-cloak]') ? '{{ asset('images/placeholder.jpg') }}' : document.querySelector('[data-image-' + mainImage + ']')?.src"
                            alt="{{ $product->name }}"
                            class="max-w-4xl max-h-[90vh] w-full h-auto object-contain">
                    </div>
                @endif
            </div>

            <!-- RIGHT COLUMN - Product Info -->
            <div class="space-y-6">
                @livewire('add-to-cart', ['product' => $product])
            </div>
        </div>
    </div>

    <!-- Tabs Section -->
    <div class="border-t border-gray-200 mt-12">
        <div class="max-w-7xl mx-auto px-4">
            <div x-data="{ activeTab: 'description' }" class="pt-12">
                <!-- Tab Navigation -->
                <div class="flex border-b border-gray-200 mb-8" role="tablist">
                    <button
                        @click="activeTab = 'description'"
                        :class="{ 'border-b-2 border-red-600 text-red-600': activeTab === 'description' }"
                        class="px-6 py-4 font-semibold text-gray-600 hover:text-gray-900 transition-colors"
                        role="tab"
                        :aria-selected="activeTab === 'description'">
                        Mô tả sản phẩm
                    </button>
                    <button
                        @click="activeTab = 'specs'"
                        :class="{ 'border-b-2 border-red-600 text-red-600': activeTab === 'specs' }"
                        class="px-6 py-4 font-semibold text-gray-600 hover:text-gray-900 transition-colors"
                        role="tab"
                        :aria-selected="activeTab === 'specs'">
                        Thông số kỹ thuật
                    </button>
                    <button
                        @click="activeTab = 'reviews'"
                        :class="{ 'border-b-2 border-red-600 text-red-600': activeTab === 'reviews' }"
                        class="px-6 py-4 font-semibold text-gray-600 hover:text-gray-900 transition-colors"
                        role="tab"
                        :aria-selected="activeTab === 'reviews'">
                        Đánh giá
                    </button>
                </div>

                <!-- Tab Content -->
                <div class="pb-12">
                    <!-- Description Tab -->
                    <div x-show="activeTab === 'description'" x-transition role="tabpanel">
                        <div class="prose prose-sm max-w-none text-gray-700">
                            @if ($product->description)
                                {!! nl2br(e($product->description)) !!}
                            @else
                                <p class="text-gray-500">Chưa có mô tả cho sản phẩm này</p>
                            @endif
                        </div>
                    </div>

                    <!-- Specifications Tab -->
                    <div x-show="activeTab === 'specs'" x-transition role="tabpanel">
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <div class="text-sm text-gray-600">Chất liệu</div>
                                    <div class="font-semibold text-gray-900">Polyester 100%</div>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <div class="text-sm text-gray-600">Xuất xứ</div>
                                    <div class="font-semibold text-gray-900">Việt Nam</div>
                                </div>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="text-sm text-gray-600">Cách bảo quản</div>
                                <div class="text-gray-900">
                                    <ul class="list-disc list-inside space-y-2 mt-2">
                                        <li>Giặt với nước lạnh dưới 30°C</li>
                                        <li>Không tẩy trắng</li>
                                        <li>Không giặt máy</li>
                                        <li>Phơi ngoài ánh nắng trực tiếp</li>
                                        <li>Ủi ở nhiệt độ thấp nếu cần</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="text-sm text-gray-600">SKU</div>
                                <div class="font-semibold text-gray-900">{{ $product->sku }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Reviews Tab -->
                    <div x-show="activeTab === 'reviews'" x-transition role="tabpanel">
                        @livewire('review-list', ['product' => $product])
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if ($related->count() > 0)
        <div class="border-t border-gray-200 mt-12">
            <div class="max-w-7xl mx-auto px-4 py-12">
                <h3 class="text-2xl font-bold text-gray-900 mb-8">Sản phẩm liên quan</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ($related as $relatedProduct)
                        <div class="group cursor-pointer">
                            <div class="relative bg-gray-100 rounded-lg overflow-hidden aspect-square mb-4 hover:bg-gray-200 transition-colors">
                                <a href="{{ route('products.show', $relatedProduct->slug) }}" class="absolute inset-0">
                                    @if ($relatedProduct->images->count() > 0)
                                        <img
                                            src="{{ asset('storage/' . $relatedProduct->images->first()->image_path) }}"
                                            alt="{{ $relatedProduct->name }}"
                                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-gray-200">
                                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                </a>

                                @if ($relatedProduct->sale_price && $relatedProduct->sale_price < $relatedProduct->price)
                                    <div class="absolute top-3 right-3 bg-red-600 text-white px-2 py-1 rounded text-xs font-bold">
                                        SALE
                                    </div>
                                @endif
                            </div>

                            <a href="{{ route('products.show', $relatedProduct->slug) }}" class="block hover:text-red-600 transition-colors">
                                <p class="text-sm text-gray-600">{{ $relatedProduct->category?->name }}</p>
                                <h4 class="font-semibold text-gray-900 line-clamp-2 group-hover:text-red-600">
                                    {{ $relatedProduct->name }}
                                </h4>

                                @if ($relatedProduct->reviews_count > 0)
                                    <div class="flex items-center gap-2 mt-2">
                                        <div class="flex items-center gap-0.5">
                                            @for ($i = 0; $i < 5; $i++)
                                                <svg class="w-3 h-3 {{ $i < round($relatedProduct->average_rating) ? 'fill-yellow-400' : 'text-gray-300' }}"
                                                     viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                                </svg>
                                            @endfor
                                        </div>
                                        <span class="text-xs text-gray-500">({{ $relatedProduct->reviews_count }})</span>
                                    </div>
                                @endif

                                <div class="flex items-baseline gap-2 mt-3">
                                    <span class="text-lg font-bold text-red-600">
                                        {{ number_format($relatedProduct->sale_price ?? $relatedProduct->price, 0, ',', '.') }}₫
                                    </span>
                                    @if ($relatedProduct->sale_price && $relatedProduct->sale_price < $relatedProduct->price)
                                        <span class="text-sm text-gray-400 line-through">
                                            {{ number_format($relatedProduct->price, 0, ',', '.') }}₫
                                        </span>
                                    @endif
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:navigating', function() {
        // Reset scroll position when navigating
        window.scrollTo(0, 0);
    });
</script>
@endpush
@endsection
