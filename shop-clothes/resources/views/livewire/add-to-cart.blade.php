<div class="space-y-6">
    <!-- Brand & Name -->
    <div>
        <div class="text-sm text-gray-500 mb-2">
            <a href="{{ $product->brand ? route('products.index', ['brand' => [$product->brand->id]]) : '#' }}" 
               class="hover:text-red-600">
                {{ $product->brand?->name ?? 'Không xác định' }}
            </a>
        </div>
        <h1 class="text-3xl font-bold text-gray-900">{{ $product->name }}</h1>
        <div class="text-sm text-gray-500 mt-2">SKU: {{ $product->sku }}</div>
    </div>

    <!-- Rating -->
    <div class="flex items-center gap-4">
        <div class="flex items-center gap-1">
            @for ($i = 0; $i < 5; $i++)
                <svg class="w-5 h-5 {{ $i < floor($product->average_rating) ? 'fill-yellow-400 text-yellow-400' : ($i < $product->average_rating ? 'fill-yellow-400 text-yellow-400' : 'text-gray-300') }}"
                     viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                </svg>
            @endfor
        </div>
        <span class="text-sm font-semibold text-gray-900">{{ round($product->average_rating, 1) }}</span>
        <span class="text-sm text-gray-500">({{ $product->reviews_count }} đánh giá)</span>
    </div>

    <!-- Price -->
    <div class="space-y-2">
        <div class="flex items-baseline gap-3">
            <span class="text-4xl font-bold text-red-600">
                {{ number_format($product->sale_price ?? $product->price, 0, ',', '.') }}₫
            </span>
            @if ($product->sale_price && $product->sale_price < $product->price)
                <span class="text-lg text-gray-400 line-through">
                    {{ number_format($product->price, 0, ',', '.') }}₫
                </span>
                <span class="bg-red-100 text-red-600 px-3 py-1 rounded-full text-sm font-semibold">
                    Tiết kiệm {{ round((1 - $product->sale_price / $product->price) * 100) }}%
                </span>
            @endif
        </div>
        @if ($this->maxQuantity > 0 && $this->selectedColorId && $this->selectedSizeId)
            @if ($this->variantPrice && $this->variantPrice > ($product->sale_price ?? $product->price))
                <div class="text-sm text-gray-600">
                    Giá cho variant này: <strong>{{ number_format($this->variantPrice, 0, ',', '.') }}₫</strong>
                </div>
            @endif
        @endif
    </div>

    <!-- Color Selection -->
    <div>
        <div class="flex items-center justify-between mb-3">
            <label class="text-sm font-semibold text-gray-900">Chọn màu sắc</label>
        </div>
        <div class="flex gap-3 flex-wrap">
            @forelse ($this->availableColors as $color)
                <button
                    wire:click="selectColor({{ $color->id }})"
                    type="button"
                    class="w-10 h-10 rounded-full border-2 transition-all relative group"
                    style="background-color: {{ $color->hex_code }}; border-color: {{ $this->selectedColorId === $color->id ? '#E11D48' : '#D1D5DB' }}"
                    title="{{ $color->name }}">
                    @if ($this->selectedColorId === $color->id)
                        <div class="absolute inset-0 rounded-full ring-2 ring-red-600 ring-offset-2"></div>
                    @endif
                    <span class="invisible group-hover:visible absolute -top-8 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white px-2 py-1 rounded text-xs whitespace-nowrap">
                        {{ $color->name }}
                    </span>
                </button>
            @empty
                <p class="text-gray-500">Không có màu sắc nào</p>
            @endforelse
        </div>
    </div>

    <!-- Size Selection -->
    <div>
        <div class="flex items-center justify-between mb-3">
            <label class="text-sm font-semibold text-gray-900">Chọn kích cỡ</label>
            <button
                wire:click="toggleSizeGuide"
                type="button"
                class="text-red-600 hover:underline text-sm">
                Hướng dẫn chọn size
            </button>
        </div>
        <div class="flex gap-2 flex-wrap">
            @forelse ($this->availableSizes as $size)
                <button
                    wire:click="selectSize({{ $size->id }})"
                    type="button"
                    class="px-4 py-2 border-2 rounded-lg font-semibold transition-all {{ $this->selectedSizeId === $size->id ? 'border-red-600 bg-red-50 text-red-600' : 'border-gray-300 text-gray-900 hover:border-gray-400' }}">
                    {{ $size->name }}
                </button>
            @empty
                <p class="text-gray-500">Vui lòng chọn màu sắc trước</p>
            @endforelse
        </div>

        <!-- Size Guide Modal -->
        @if ($this->showSizeGuide)
            <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4" wire:click="toggleSizeGuide">
                <div class="bg-white rounded-lg max-w-2xl w-full max-h-[80vh] overflow-y-auto" wire:click.stop>
                    <div class="sticky top-0 bg-white border-b p-6 flex justify-between items-center">
                        <h3 class="text-xl font-bold">Hướng dẫn chọn kích cỡ</h3>
                        <button wire:click="toggleSizeGuide" class="text-gray-400 hover:text-gray-900">
                            ✕
                        </button>
                    </div>
                    <div class="p-6 space-y-6">
                        <div>
                            <h4 class="font-semibold mb-3">Bảng chuyển đổi kích cỡ</h4>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm border-collapse">
                                    <thead>
                                        <tr class="bg-gray-100">
                                            <th class="border p-3 text-left">Kích cỡ</th>
                                            <th class="border p-3 text-left">Vòng ngực (cm)</th>
                                            <th class="border p-3 text-left">Dài (cm)</th>
                                            <th class="border p-3 text-left">Khuyến cáo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="border p-3 font-semibold">XS</td>
                                            <td class="border p-3">82-86</td>
                                            <td class="border p-3">65-68</td>
                                            <td class="border p-3">Cơ thể nhỏ, tuổi 14-16</td>
                                        </tr>
                                        <tr class="bg-gray-50">
                                            <td class="border p-3 font-semibold">S</td>
                                            <td class="border p-3">86-90</td>
                                            <td class="border p-3">68-71</td>
                                            <td class="border p-3">Cơ thể nhỏ, tuổi 16-18</td>
                                        </tr>
                                        <tr>
                                            <td class="border p-3 font-semibold">M</td>
                                            <td class="border p-3">90-94</td>
                                            <td class="border p-3">71-74</td>
                                            <td class="border p-3">Cơ thể trung bình</td>
                                        </tr>
                                        <tr class="bg-gray-50">
                                            <td class="border p-3 font-semibold">L</td>
                                            <td class="border p-3">94-98</td>
                                            <td class="border p-3">74-77</td>
                                            <td class="border p-3">Cơ thể lớn</td>
                                        </tr>
                                        <tr>
                                            <td class="border p-3 font-semibold">XL</td>
                                            <td class="border p-3">98-102</td>
                                            <td class="border p-3">77-80</td>
                                            <td class="border p-3">Cơ thể rất lớn</td>
                                        </tr>
                                        <tr class="bg-gray-50">
                                            <td class="border p-3 font-semibold">XXL</td>
                                            <td class="border p-3">102+</td>
                                            <td class="border p-3">80+</td>
                                            <td class="border p-3">Cơ thể rất to, chiều cao cao</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-semibold mb-2">Mẹo chọn size</h4>
                            <ul class="space-y-2 text-sm text-gray-700">
                                <li>✓ Đo vòng ngực ngang qua điểm cao nhất của vú</li>
                                <li>✓ Để áo bình thường, không quá chặt hay quá rộng</li>
                                <li>✓ Nên chọn size hơi rộng một chút để thoải mái vận động</li>
                                <li>✓ Nếu giữa hai size, nên chọn size lớn hơn</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Stock Status -->
    <div>
        @if ($this->maxQuantity > 0)
            <div class="text-lg font-semibold text-green-600">
                ✓ Còn {{ $this->maxQuantity }} sản phẩm
            </div>
        @else
            <div class="text-lg font-semibold text-red-600">
                Hết hàng
            </div>
        @endif
    </div>

    <!-- Quantity Selection -->
    @if ($this->maxQuantity > 0)
        <div>
            <label class="text-sm font-semibold text-gray-900 block mb-3">Số lượng</label>
            <div class="flex items-center border border-gray-300 rounded-lg w-fit">
                <button
                    wire:click="decreaseQuantity"
                    type="button"
                    class="px-4 py-2 text-gray-600 hover:text-gray-900 font-bold">
                    −
                </button>
                <input
                    type="number"
                    value="{{ $this->quantity }}"
                    readonly
                    class="w-16 text-center border-0 py-2 font-semibold focus:ring-0">
                <button
                    wire:click="increaseQuantity"
                    type="button"
                    class="px-4 py-2 text-gray-600 hover:text-gray-900 font-bold">
                    +
                </button>
            </div>
        </div>
    @endif

    <!-- Action Buttons -->
    <div class="flex gap-3 pt-4">
        <button
            wire:click="addToCart"
            type="button"
            {{ $this->maxQuantity <= 0 ? 'disabled' : '' }}
            class="flex-1 bg-white border-2 border-red-600 text-red-600 py-3 px-6 rounded-lg font-bold hover:bg-red-50 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
            <span class="inline-flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span>Thêm vào giỏ</span>
            </span>
        </button>
        <button
            wire:click="buyNow"
            type="button"
            {{ $this->maxQuantity <= 0 ? 'disabled' : '' }}
            class="flex-1 bg-red-600 text-white py-3 px-6 rounded-lg font-bold hover:bg-red-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
            Mua ngay
        </button>
    </div>

    <!-- Wishlist & Share -->
    <div class="flex items-center gap-6 pt-4 border-t border-gray-200">
        <button
            type="button"
            class="flex items-center gap-2 text-gray-600 hover:text-red-600 transition-colors group">
            <svg class="w-6 h-6 group-hover:fill-current" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>
            <span>Yêu thích</span>
        </button>

        <div class="flex items-center gap-3 ml-auto">
            <span class="text-sm text-gray-600">Chia sẻ:</span>
            <button type="button" class="p-2 text-gray-400 hover:text-blue-600 transition-colors" title="Chia sẻ Facebook">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M20 10a10 10 0 11-20 0 10 10 0 0120 0zm-4.5-2.5a.5.5 0 01.5.5v5a.5.5 0 01-.5.5H13V9.5h1.5V8a2.5 2.5 0 012.5-2.5H14.5V7h-1a1 1 0 00-1 1v1.5h1.5v1.5H12.5V15h-1.5V12H9.5v-1.5h1.5V9a2 2 0 012-2h1.5V7z" clip-rule="evenodd" />
                </svg>
            </button>
            <button type="button" class="p-2 text-gray-400 hover:text-green-600 transition-colors" title="Chia sẻ Zalo">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 0C4.477 0 0 4.477 0 10s4.477 10 10 10 10-4.477 10-10S15.523 0 10 0zm3.5 11.5h-3v2h-1v-2h-3v-1h3v-2h1v2h3v1z" />
                </svg>
            </button>
            <button
                type="button"
                onclick="navigator.clipboard.writeText(window.location.href); alert('Đã sao chép link')"
                class="p-2 text-gray-400 hover:text-gray-600 transition-colors"
                title="Sao chép link">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                </svg>
            </button>
        </div>
    </div>
</div>
