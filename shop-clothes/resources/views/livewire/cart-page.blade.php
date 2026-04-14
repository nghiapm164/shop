<div class="min-h-screen pb-12">
    <!-- Header -->
    <div class="max-w-7xl mx-auto px-4 pt-8 pb-4">
        <div class="fashion-section p-6 md:p-7">
            <h1 class="fashion-title text-3xl md:text-4xl">Giỏ hàng</h1>
            <p class="fashion-subtitle mt-2">{{ $this->cartItems->count() }} sản phẩm đang chờ bạn thanh toán.</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-8">
        @if ($this->cartItems->isEmpty())
            <!-- Empty Cart State -->
            <div class="fashion-section flex flex-col items-center justify-center py-16 px-4 text-center">
                <svg class="w-24 h-24 text-gray-300 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <h2 class="text-2xl font-semibold text-gray-900 mb-2">Giỏ hàng của bạn trống</h2>
                <p class="text-gray-500 mb-6">Hãy thêm một số sản phẩm để bắt đầu mua sắm</p>
                <button
                    wire:click="continueShopping"
                    type="button"
                    class="btn-primary px-8 py-3">
                    Tiếp tục mua sắm
                </button>
            </div>
        @else
            <!-- Cart Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Cart Items Section -->
                <div class="lg:col-span-2">
                    <!-- Cart Table -->
                    <div class="fashion-section overflow-hidden">
                        <!-- Table Header -->
                        <div class="hidden lg:grid lg:grid-cols-[minmax(0,2.4fr)_0.75fr_0.95fr_1fr_1.2fr_0.6fr] gap-4 p-6 border-b border-slate-200 font-semibold text-sm text-slate-900 bg-slate-50/80 items-center">
                            <div>Sản phẩm</div>
                            <div class="text-left">Size</div>
                            <div class="text-left">Màu</div>
                            <div class="text-right">Giá</div>
                            <div class="text-center">Số lượng</div>
                            <div class="text-center">Xóa</div>
                        </div>

                        <!-- Cart Items -->
                        @foreach ($this->cartItems as $item)
                            <div
                                wire:key="cart-item-{{ $item['product_variant_id'] }}"
                                class="border-b border-slate-200 last:border-b-0 p-6 grid grid-cols-1 lg:grid-cols-[minmax(0,2.4fr)_0.75fr_0.95fr_1fr_1.2fr_0.6fr] gap-4 lg:items-center transition-all duration-200"
                                wire:loading.class="opacity-60 scale-[0.995]"
                                wire:target="removeItem({{ $item['product_variant_id'] }}),updateQuantity({{ $item['product_variant_id'] }}, {{ $item['quantity'] - 1 }}),updateQuantity({{ $item['product_variant_id'] }}, {{ $item['quantity'] + 1 }})"
                            >
                                <!-- Product Info -->
                                <div class="flex gap-4 min-w-0">
                                    <img
                                        src="{{ str_starts_with($item['image_url'] ?? '', 'http://') || str_starts_with($item['image_url'] ?? '', 'https://') ? ($item['image_url'] ?? asset('images/product-placeholder.svg')) : asset($item['image_url'] ?? 'images/product-placeholder.svg') }}"
                                        alt="{{ $item['product_name'] }}"
                                        class="w-20 h-20 object-cover rounded-xl border border-slate-200">
                                    <div class="flex-1 min-w-0">
                                        <a href="{{ !empty($item['product_slug']) ? route('products.show', $item['product_slug']) : route('shop.index') }}" class="font-semibold text-slate-900 text-base hover:text-red-600 line-clamp-2 leading-snug">
                                            {{ $item['product_name'] }}
                                        </a>
                                        <p class="text-xs text-slate-500 mt-1">SKU: {{ $item['sku'] ?? 'N/A' }}</p>
                                        <p class="text-xs text-slate-500 mt-1">{{ $item['size'] ?? 'N/A' }} • {{ $item['color'] ?? 'N/A' }}</p>
                                    </div>
                                </div>

                                <!-- Size (Mobile/Desktop) -->
                                <div class="hidden lg:block">
                                    <p class="font-medium text-sm">{{ $item['size'] ?? 'N/A' }}</p>
                                </div>

                                <!-- Color (Mobile/Desktop) -->
                                <div class="hidden lg:block">
                                    <div class="flex items-center gap-2">
                                        @if (isset($item['color_hex']))
                                            <div
                                                class="w-6 h-6 rounded border-2 border-gray-300"
                                                style="background-color: {{ $item['color_hex'] }}"></div>
                                        @endif
                                        <span class="text-sm">{{ $item['color'] ?? 'N/A' }}</span>
                                    </div>
                                </div>

                                <!-- Price (Mobile/Desktop) -->
                                <div class="lg:text-right">
                                    <p class="lg:hidden text-xs text-gray-500">Giá</p>
                                    <p class="font-semibold text-red-600">
                                        {{ number_format($item['price'], 0, ',', '.') }}₫
                                    </p>
                                    <p class="text-xs text-slate-500 mt-1">
                                        Tạm tính: {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}₫
                                    </p>
                                </div>

                                <!-- Quantity (Mobile/Desktop) -->
                                <div>
                                    <p class="lg:hidden text-xs text-gray-500 mb-2">Số lượng</p>
                                    <div class="flex items-center border border-slate-300 rounded-xl w-fit h-11">
                                        <button
                                            wire:click="updateQuantity({{ $item['product_variant_id'] }}, {{ $item['quantity'] - 1 }})"
                                            type="button"
                                            wire:loading.attr="disabled"
                                            wire:target="updateQuantity({{ $item['product_variant_id'] }}, {{ $item['quantity'] - 1 }})"
                                            class="px-3.5 h-full text-gray-600 hover:text-gray-900 text-lg leading-none">
                                            −
                                        </button>
                                        <input
                                            type="number"
                                            value="{{ $item['quantity'] }}"
                                            wire:change="updateQuantity({{ $item['product_variant_id'] }}, $event.target.value)"
                                            min="1"
                                            max="{{ $item['stock_quantity'] ?? 999 }}"
                                            class="w-16 h-full text-center border-0 py-0 focus:ring-0 text-base font-semibold">
                                        <button
                                            wire:click="updateQuantity({{ $item['product_variant_id'] }}, {{ $item['quantity'] + 1 }})"
                                            type="button"
                                            wire:loading.attr="disabled"
                                            wire:target="updateQuantity({{ $item['product_variant_id'] }}, {{ $item['quantity'] + 1 }})"
                                            class="px-3.5 h-full text-gray-600 hover:text-gray-900 text-lg leading-none {{ ($item['quantity'] >= ($item['stock_quantity'] ?? 999)) ? 'opacity-40 cursor-not-allowed' : '' }}"
                                            {{ ($item['quantity'] >= ($item['stock_quantity'] ?? 999)) ? 'disabled' : '' }}>
                                            +
                                        </button>
                                    </div>
                                    <p class="text-xs text-slate-500 mt-2">Tồn kho: {{ $item['stock_quantity'] ?? 0 }}</p>
                                </div>

                                <!-- Delete Button (Mobile/Desktop) -->
                                <div class="lg:text-center">
                                    <button
                                        wire:click="removeItem({{ $item['product_variant_id'] }})"
                                        type="button"
                                        wire:loading.attr="disabled"
                                        wire:target="removeItem({{ $item['product_variant_id'] }})"
                                        class="text-red-600 hover:text-red-700 font-medium text-sm w-full lg:w-auto">
                                        <span wire:loading.remove wire:target="removeItem({{ $item['product_variant_id'] }})">Xóa</span>
                                        <span wire:loading wire:target="removeItem({{ $item['product_variant_id'] }})">Đang xóa...</span>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Continue Shopping Button (Mobile) -->
                    <button
                        wire:click="continueShopping"
                        type="button"
                        class="w-full lg:hidden mt-6 px-6 py-3 rounded-xl border border-red-500 text-red-500 font-semibold hover:bg-red-50 transition-colors">
                        Tiếp tục mua sắm
                    </button>
                </div>

                <!-- Order Summary Sidebar -->
                <div class="lg:col-span-1">
                    <div class="fashion-section p-6 sticky top-24">
                        <h3 class="fashion-title text-xl mb-6">Tóm tắt đơn hàng</h3>

                        <!-- Summary Items -->
                        <div class="space-y-4 border-b border-slate-200 pb-6">
                            <!-- Subtotal -->
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Tạm tính:</span>
                                <span class="font-semibold text-gray-900">
                                    {{ number_format($this->subtotal, 0, ',', '.') }}₫
                                </span>
                            </div>

                            <!-- Discount Code -->
                            <div class="space-y-3">
                                <div class="flex gap-2">
                                    <input
                                        type="text"
                                        wire:model.defer="couponCode"
                                        placeholder="Mã giảm giá"
                                        class="flex-1 h-11 px-4 border border-gray-300 rounded-lg text-base focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                        @keydown.enter="$wire.applyCoupon()">
                                    <button
                                        wire:click="applyCoupon"
                                        type="button"
                                        class="h-11 px-5 bg-red-600 text-white rounded-lg font-semibold text-sm hover:bg-red-700 transition-colors whitespace-nowrap">
                                        Áp dụng
                                    </button>
                                </div>

                                @if ($this->errorMessage)
                                    <p class="text-red-600 text-xs">{{ $this->errorMessage }}</p>
                                @endif

                                @if ($this->availableCoupons->isNotEmpty())
                                    <div x-data="{ expanded: false }" class="pt-1 space-y-2">
                                        <div class="flex flex-wrap gap-2">
                                        @foreach ($this->availableCoupons as $index => $coupon)
                                            <div x-show="expanded || {{ $index }} < 3" x-transition.opacity class="relative" x-data="{ open: false }" @keydown.escape.window="open = false">
                                                <div class="inline-flex items-center gap-1 rounded-full border border-slate-200 bg-slate-50 px-2.5 py-1.5 text-xs">
                                                <button
                                                    type="button"
                                                    class="font-bold text-slate-800 hover:text-red-600"
                                                    wire:click="applyCouponCode('{{ $coupon->code }}')"
                                                    title="Áp dụng mã {{ $coupon->code }}"
                                                >
                                                    {{ $coupon->code }}
                                                </button>
                                                <span class="text-slate-500">• {{ $coupon->discount_text }}</span>

                                                <button
                                                    type="button"
                                                    class="inline-flex h-5 w-5 items-center justify-center rounded-full text-slate-500 hover:bg-slate-200 hover:text-slate-700"
                                                    wire:click="applyCouponCode('{{ $coupon->code }}')"
                                                    title="Áp dụng nhanh mã"
                                                >
                                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                </button>

                                                <button
                                                    type="button"
                                                    class="inline-flex h-5 w-5 items-center justify-center rounded-full text-slate-500 hover:bg-slate-200 hover:text-slate-700"
                                                    title="Xem chi tiết voucher"
                                                    @click="open = !open"
                                                >
                                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                </button>
                                                </div>

                                                <div
                                                    x-show="open"
                                                    @click.outside="open = false"
                                                    x-transition.opacity.scale.origin.top.left
                                                    class="absolute left-0 top-full mt-2 z-20 w-[260px] rounded-xl border border-slate-200 bg-white px-3 py-2 text-[11px] text-slate-600 shadow-lg"
                                                >
                                                    <p><span class="font-semibold text-slate-800">{{ $coupon->code }}</span> • Giảm {{ $coupon->discount_text }}</p>
                                                    <p>Tối thiểu {{ number_format((float) $coupon->min_order_amount, 0, ',', '.') }}đ</p>
                                                    @if ($coupon->max_discount)
                                                        <p>Tối đa {{ number_format((float) $coupon->max_discount, 0, ',', '.') }}đ</p>
                                                    @endif
                                                    <p>HSD {{ optional($coupon->end_date)->format('d/m/Y') }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                        </div>

                                        @if ($this->availableCoupons->count() > 3)
                                            <button
                                                type="button"
                                                class="text-[11px] font-semibold text-slate-600 hover:text-red-600"
                                                @click="expanded = !expanded"
                                            >
                                                <span x-show="!expanded">+{{ $this->availableCoupons->count() - 3 }} mã khác</span>
                                                <span x-show="expanded">Thu gọn danh sách mã</span>
                                            </button>
                                        @endif
                                    </div>
                                @endif

                                @if ($this->successMessage)
                                    <p class="text-green-600 text-xs">{{ $this->successMessage }}</p>
                                @endif

                                @if ($this->appliedCoupon)
                                    <div class="bg-green-50 border border-green-200 rounded-lg p-3 animate-[fadeIn_.2s_ease-out]">
                                        <div class="flex justify-between items-center mb-2">
                                            <span class="font-semibold text-green-700 text-sm">Mã: {{ $this->appliedCoupon->code }}</span>
                                            <button
                                                wire:click="removeCoupon"
                                                type="button"
                                                class="inline-flex h-6 w-6 items-center justify-center rounded-full text-green-600 hover:bg-green-100 hover:text-green-700"
                                                title="Bỏ voucher">
                                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                        </div>
                                        <p class="text-green-600 text-sm font-semibold">
                                            -{{ number_format($this->discountAmount, 0, ',', '.') }}₫
                                        </p>
                                    </div>
                                @endif
                            </div>

                            <!-- Discount Amount -->
                            @if ($this->appliedCoupon)
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Giảm giá:</span>
                                    <span class="font-semibold text-green-600">
                                        -{{ number_format($this->discountAmount, 0, ',', '.') }}₫
                                    </span>
                                </div>
                            @endif

                            <!-- Shipping Fee -->
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">
                                    Phí vận chuyển:
                                    @if ($this->shippingFee == 0)
                                        <span class="text-green-600 font-semibold text-xs">(Miễn phí)</span>
                                    @endif
                                </span>
                                <span class="font-semibold text-gray-900">
                                    @if ($this->shippingFee == 0)
                                        <span class="text-green-600">Miễn phí</span>
                                    @else
                                        {{ number_format($this->shippingFee, 0, ',', '.') }}₫
                                    @endif
                                </span>
                            </div>
                        </div>

                        <!-- Total -->
                            <div class="pt-6 border-b border-slate-200 pb-6">
                            <div class="flex justify-between items-baseline">
                                <span class="text-gray-700 font-semibold">Tổng cộng:</span>
                                <span class="text-3xl font-bold text-red-600">
                                    {{ number_format($this->total, 0, ',', '.') }}₫
                                </span>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="pt-6 space-y-3">
                            <button
                                wire:click="proceedToCheckout"
                                type="button"
                                wire:loading.attr="disabled"
                                wire:target="proceedToCheckout"
                                class="w-full btn-primary py-3">
                                <span wire:loading.remove wire:target="proceedToCheckout">Tiến hành thanh toán</span>
                                <span wire:loading wire:target="proceedToCheckout">Đang xử lý...</span>
                            </button>
                            <button
                                wire:click="continueShopping"
                                type="button"
                                class="hidden lg:block w-full px-6 py-3 rounded-xl border border-red-500 text-red-500 font-semibold hover:bg-red-50 transition-colors">
                                Tiếp tục mua sắm
                            </button>
                        </div>

                        <!-- Free Shipping Info -->
                        @if ($this->shippingFee > 0)
                            <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                                <p class="text-yellow-800 text-xs">
                                    <span class="font-semibold">Mẹo:</span> Mua thêm {{ number_format(self::SHIPPING_FREE_THRESHOLD - $this->subtotal, 0, ',', '.') }}₫ để được miễn phí vận chuyển
                                </p>
                            </div>
                        @else
                            <div class="mt-6 bg-green-50 border border-green-200 rounded-lg p-3">
                                <p class="text-green-800 text-xs">
                                    <span class="font-semibold">✓ Bạn được miễn phí vận chuyển!</span>
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
