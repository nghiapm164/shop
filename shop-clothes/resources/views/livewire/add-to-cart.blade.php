@php
    $variantPayload = $this->variantCollection->map(fn ($variant) => [
        'id' => (int) $variant->id,
        'color_id' => (int) $variant->color_id,
        'size_id' => (int) $variant->size_id,
        'stock' => (int) $variant->stock_quantity,
        'additional_price' => (float) ($variant->additional_price ?? 0),
        'color_name' => $variant->color?->name,
        'color_hex' => $variant->color?->hex_code,
        'size_label' => $variant->size?->short_label,
    ])->values();

    $colorPayload = $this->variantCollection->pluck('color')->filter()->unique('id')->sortBy('name')->map(fn ($color) => [
        'id' => (int) $color->id,
        'name' => $color->name,
        'hex' => $color->hex_code,
    ])->values();

    $sizePayload = $this->variantCollection->pluck('size')->filter()->unique('id')->sortBy('id')->map(fn ($size) => [
        'id' => (int) $size->id,
        'label' => $size->short_label,
    ])->values();

    $baseProductPrice = (float) ($product->sale_price ?? $product->price);
@endphp

<div
    class="space-y-6"
    x-data="variantPicker({
        basePrice: {{ $baseProductPrice }},
        initialColorId: {{ $selectedColorId ?? 'null' }},
        initialSizeId: {{ $selectedSizeId ?? 'null' }},
        initialQuantity: {{ $quantity }},
        variants: @js($variantPayload),
        colors: @js($colorPayload),
        sizes: @js($sizePayload),
    })"
>
    <input type="hidden" x-model="selectedColorId" wire:model.defer="selectedColorId">
    <input type="hidden" x-model="selectedSizeId" wire:model.defer="selectedSizeId">
    <input type="hidden" x-model="quantity" wire:model.defer="quantity">

    <div class="flex items-center justify-between gap-3 text-sm text-gray-500">
        <span>SKU: {{ $product->sku }}</span>
        <a href="{{ $product->brand ? route('shop.index', ['brand' => [$product->brand->id]]) : '#' }}" class="hover:text-red-600">
            {{ $product->brand?->name ?? 'Không xác định' }}
        </a>
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
            <span class="text-4xl font-bold text-red-600" x-text="formatVnd(displayPrice()) + '₫'">
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
        <div class="text-sm text-gray-600" x-show="selectedVariant()">
            Biến thể đã chọn:
            <strong x-text="selectedVariant()?.size_label"></strong>
            /
            <strong x-text="selectedVariant()?.color_name"></strong>
            <span class="text-slate-500" x-show="(selectedVariant()?.additional_price || 0) > 0">
                (+<span x-text="formatVnd(selectedVariant()?.additional_price || 0)"></span>₫)
            </span>
        </div>
    </div>

    <!-- Color Selection -->
    <div>
        <div class="flex items-center justify-between mb-3">
            <label class="text-sm font-semibold text-gray-900">Chọn màu sắc</label>
        </div>
        <div class="flex gap-3 flex-wrap" x-show="colors.length > 0">
            <template x-for="color in colors" :key="color.id">
                <button
                    @click="selectColor(color.id)"
                    type="button"
                    class="w-10 h-10 rounded-full border-2 transition-colors duration-75 relative group"
                    :style="`background-color:${isColorOut(color.id) ? '#d1d5db' : (color.hex || '#d1d5db')}; border-color:${selectedColorId === color.id ? '#E11D48' : '#D1D5DB'}`"
                    :title="color.name"
                >
                    <div class="absolute inset-0 rounded-full ring-2 ring-red-600 ring-offset-2" x-show="selectedColorId === color.id"></div>
                    <div class="absolute inset-0 rounded-full bg-white/30" x-show="isColorOut(color.id)"></div>
                    <span class="invisible group-hover:visible absolute -top-8 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white px-2 py-1 rounded text-xs whitespace-nowrap" x-text="isColorOut(color.id) ? `${color.name} (hết hàng)` : color.name"></span>
                </button>
            </template>
        </div>
        <p class="text-gray-500" x-show="colors.length === 0">Không có màu sắc nào</p>
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
        <div class="flex gap-2 flex-wrap" x-show="sizesForCurrentColor().length > 0">
            <template x-for="size in sizesForCurrentColor()" :key="size.id">
                <button
                    @click="selectSize(size.id)"
                    type="button"
                    class="px-4 py-2 border-2 rounded-lg font-semibold transition-colors duration-75"
                    :class="isSizeOut(size.id)
                        ? 'border-gray-300 bg-gray-100 text-gray-500 hover:border-gray-400'
                        : (selectedSizeId === size.id
                            ? 'border-red-600 bg-red-50 text-red-600'
                            : 'border-gray-300 text-gray-900 hover:border-gray-400')"
                    x-text="size.label"
                ></button>
            </template>
        </div>
        <p class="text-gray-500" x-show="sizesForCurrentColor().length === 0">Vui lòng chọn màu sắc trước</p>

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
        <div class="text-lg font-semibold text-green-600" x-show="maxQuantity() > 0">
            ✓ Còn <span x-text="maxQuantity()"></span> sản phẩm
        </div>
        <div class="text-lg font-semibold text-red-600" x-show="maxQuantity() <= 0">
            Hết hàng
        </div>
    </div>

    <!-- Quantity Selection -->
    <div x-show="maxQuantity() > 0">
        <label class="text-sm font-semibold text-gray-900 block mb-3">Số lượng</label>
        <div class="flex items-center border border-gray-300 rounded-lg w-fit">
            <button @click="decreaseQty()" type="button" class="px-4 py-2 text-gray-600 hover:text-gray-900 font-bold">−</button>
            <input type="number" x-model="quantity" readonly class="w-16 text-center border-0 py-2 font-semibold focus:ring-0">
            <button @click="increaseQty()" type="button" class="px-4 py-2 text-gray-600 hover:text-gray-900 font-bold">+</button>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex gap-3 pt-4">
        <button
            @click.prevent="$wire.addToCartFromState(selectedColorId, selectedSizeId, quantity)"
            type="button"
            wire:loading.attr="disabled"
            wire:target="addToCartFromState,buyNowFromState"
            :disabled="maxQuantity() <= 0"
            class="flex-1 bg-white border-2 border-red-600 text-red-600 py-3 px-6 rounded-lg font-bold hover:bg-red-50 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
            <span class="inline-flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span wire:loading.remove wire:target="addToCartFromState">Thêm vào giỏ</span>
                <span wire:loading wire:target="addToCartFromState">Đang thêm...</span>
            </span>
        </button>
        <button
            @click.prevent="$wire.buyNowFromState(selectedColorId, selectedSizeId, quantity)"
            type="button"
            wire:loading.attr="disabled"
            wire:target="buyNowFromState"
            :disabled="maxQuantity() <= 0"
            class="flex-1 bg-red-600 text-white py-3 px-6 rounded-lg font-bold hover:bg-red-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
            <span wire:loading.remove wire:target="buyNowFromState">Mua ngay</span>
            <span wire:loading wire:target="buyNowFromState">Đang xử lý...</span>
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
<script>
        function variantPicker(config) {
            return {
                basePrice: Number(config.basePrice || 0),
                variants: config.variants || [],
                colors: config.colors || [],
                sizes: config.sizes || [],
                selectedColorId: config.initialColorId ? Number(config.initialColorId) : null,
                selectedSizeId: config.initialSizeId ? Number(config.initialSizeId) : null,
                quantity: Math.max(1, Number(config.initialQuantity || 1)),

                init() {
                    if (!this.selectedColorId || !this.selectedSizeId) {
                        this.pickDefault();
                    }
                },

                pickDefault() {
                    const sorted = [...this.variants].sort((a, b) => {
                        const aOut = a.stock <= 0 ? 1 : 0;
                        const bOut = b.stock <= 0 ? 1 : 0;
                        if (aOut !== bOut) return aOut - bOut;
                        if (a.color_id !== b.color_id) return a.color_id - b.color_id;
                        return a.size_id - b.size_id;
                    });

                    const first = sorted[0] || null;
                    if (!first) return;

                    this.selectedColorId = first.color_id;
                    this.selectedSizeId = first.size_id;
                    this.quantity = 1;
                },

                selectedVariant() {
                    return this.variants.find(v => v.color_id === this.selectedColorId && v.size_id === this.selectedSizeId) || null;
                },

                displayPrice() {
                    const variant = this.selectedVariant();
                    return this.basePrice + Number(variant?.additional_price || 0);
                },

                maxQuantity() {
                    return Number(this.selectedVariant()?.stock || 0);
                },

                colorStock(colorId) {
                    return this.variants
                        .filter(v => v.color_id === colorId)
                        .reduce((sum, v) => sum + Number(v.stock || 0), 0);
                },

                isColorOut(colorId) {
                    return this.colorStock(colorId) <= 0;
                },

                sizesForCurrentColor() {
                    const base = this.selectedColorId
                        ? this.variants.filter(v => v.color_id === this.selectedColorId)
                        : this.variants;

                    const byId = new Map();
                    base.forEach(v => {
                        if (!byId.has(v.size_id)) {
                            const item = this.sizes.find(s => s.id === v.size_id);
                            byId.set(v.size_id, {
                                id: v.size_id,
                                label: item?.label || v.size_label || String(v.size_id),
                            });
                        }
                    });

                    return [...byId.values()].sort((a, b) => a.id - b.id);
                },

                sizeStock(sizeId) {
                    const base = this.selectedColorId
                        ? this.variants.filter(v => v.color_id === this.selectedColorId)
                        : this.variants;

                    return base
                        .filter(v => v.size_id === sizeId)
                        .reduce((sum, v) => sum + Number(v.stock || 0), 0);
                },

                isSizeOut(sizeId) {
                    return this.sizeStock(sizeId) <= 0;
                },

                selectColor(colorId) {
                    this.selectedColorId = Number(colorId);

                    const firstSize = this.variants
                        .filter(v => v.color_id === this.selectedColorId)
                        .sort((a, b) => {
                            const aOut = a.stock <= 0 ? 1 : 0;
                            const bOut = b.stock <= 0 ? 1 : 0;
                            if (aOut !== bOut) return aOut - bOut;
                            return a.size_id - b.size_id;
                        })[0] || null;

                    if (firstSize) {
                        this.selectedSizeId = firstSize.size_id;
                    }

                    this.quantity = 1;
                },

                selectSize(sizeId) {
                    this.selectedSizeId = Number(sizeId);
                    this.quantity = 1;
                },

                increaseQty() {
                    if (this.quantity < this.maxQuantity()) {
                        this.quantity += 1;
                    }
                },

                decreaseQty() {
                    if (this.quantity > 1) {
                        this.quantity -= 1;
                    }
                },

                formatVnd(value) {
                    return new Intl.NumberFormat('vi-VN').format(Number(value || 0));
                },
            };
        }
    </script>
</div>
