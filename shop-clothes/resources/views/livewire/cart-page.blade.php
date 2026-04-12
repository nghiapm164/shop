<div class="bg-white min-h-screen">
    <!-- Header -->
    <div class="max-w-7xl mx-auto px-4 py-8 border-b border-gray-200">
        <h1 class="text-3xl font-bold text-gray-900">Giỏ hàng</h1>
        <p class="text-gray-500 mt-2">{{ $this->cartItems->count() }} sản phẩm</p>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-8">
        @if ($this->cartItems->isEmpty())
            <!-- Empty Cart State -->
            <div class="flex flex-col items-center justify-center py-16">
                <svg class="w-24 h-24 text-gray-300 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <h2 class="text-2xl font-semibold text-gray-900 mb-2">Giỏ hàng của bạn trống</h2>
                <p class="text-gray-500 mb-6">Hãy thêm một số sản phẩm để bắt đầu mua sắm</p>
                <button
                    wire:click="continueShopping"
                    type="button"
                    class="px-8 py-3 bg-red-600 text-white rounded-lg font-semibold hover:bg-red-700 transition-colors">
                    Tiếp tục mua sắm
                </button>
            </div>
        @else
            <!-- Cart Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Cart Items Section -->
                <div class="lg:col-span-2">
                    <!-- Cart Table -->
                    <div class="bg-white rounded-lg border border-gray-200">
                        <!-- Table Header -->
                        <div class="hidden lg:grid grid-cols-6 gap-4 p-6 border-b border-gray-200 font-semibold text-sm text-gray-900">
                            <div>Sản phẩm</div>
                            <div class="text-center">Size</div>
                            <div class="text-center">Màu</div>
                            <div class="text-right">Giá</div>
                            <div class="text-center">Số lượng</div>
                            <div class="text-center">Xóa</div>
                        </div>

                        <!-- Cart Items -->
                        @foreach ($this->cartItems as $item)
                            <div class="border-b border-gray-200 last:border-b-0 p-6 grid grid-cols-1 lg:grid-cols-6 gap-4 lg:items-center">
                                <!-- Product Info -->
                                <div class="lg:col-span-1 flex gap-4">
                                    @php
                                        $product = \App\Models\Product::find($item['product_id']);
                                        $imageUrl = $product?->images->first()?->image_path 
                                            ? asset('storage/' . $product->images->first()->image_path)
                                            : asset('images/placeholder.jpg');
                                    @endphp
                                    <img
                                        src="{{ $imageUrl }}"
                                        alt="{{ $item['product_name'] }}"
                                        class="w-20 h-20 object-cover rounded-lg">
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-900 text-sm">{{ $item['product_name'] }}</h3>
                                        <p class="text-xs text-gray-500 mt-1">SKU: {{ $item['sku'] ?? 'N/A' }}</p>
                                    </div>
                                </div>

                                <!-- Size (Mobile/Desktop) -->
                                <div class="lg:col-span-1">
                                    <p class="lg:hidden text-xs text-gray-500">Size</p>
                                    <p class="font-medium text-sm">{{ $item['size'] ?? 'N/A' }}</p>
                                </div>

                                <!-- Color (Mobile/Desktop) -->
                                <div class="lg:col-span-1">
                                    <p class="lg:hidden text-xs text-gray-500">Màu</p>
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
                                <div class="lg:col-span-1 lg:text-right">
                                    <p class="lg:hidden text-xs text-gray-500">Giá</p>
                                    <p class="font-semibold text-red-600">
                                        {{ number_format($item['price'], 0, ',', '.') }}₫
                                    </p>
                                </div>

                                <!-- Quantity (Mobile/Desktop) -->
                                <div class="lg:col-span-1">
                                    <p class="lg:hidden text-xs text-gray-500 mb-2">Số lượng</p>
                                    <div class="flex items-center border border-gray-300 rounded-lg w-fit">
                                        <button
                                            wire:click="updateQuantity({{ $item['product_variant_id'] }}, {{ $item['quantity'] - 1 }})"
                                            type="button"
                                            class="px-3 py-1 text-gray-600 hover:text-gray-900">
                                            −
                                        </button>
                                        <input
                                            type="number"
                                            value="{{ $item['quantity'] }}"
                                            wire:change="updateQuantity({{ $item['product_variant_id'] }}, $event.target.value)"
                                            min="1"
                                            max="999"
                                            class="w-12 text-center border-0 py-1 focus:ring-0 text-sm font-medium">
                                        <button
                                            wire:click="updateQuantity({{ $item['product_variant_id'] }}, {{ $item['quantity'] + 1 }})"
                                            type="button"
                                            class="px-3 py-1 text-gray-600 hover:text-gray-900">
                                            +
                                        </button>
                                    </div>
                                </div>

                                <!-- Delete Button (Mobile/Desktop) -->
                                <div class="lg:col-span-1 lg:text-center">
                                    <button
                                        wire:click="removeItem({{ $item['product_variant_id'] }})"
                                        type="button"
                                        class="text-red-600 hover:text-red-700 font-medium text-sm w-full lg:w-auto">
                                        Xóa
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Continue Shopping Button (Mobile) -->
                    <button
                        wire:click="continueShopping"
                        type="button"
                        class="w-full lg:hidden mt-6 px-6 py-3 border-2 border-red-600 text-red-600 rounded-lg font-semibold hover:bg-red-50 transition-colors">
                        Tiếp tục mua sắm
                    </button>
                </div>

                <!-- Order Summary Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-gray-50 rounded-lg p-6 sticky top-24">
                        <h3 class="text-lg font-bold text-gray-900 mb-6">Tóm tắt đơn hàng</h3>

                        <!-- Summary Items -->
                        <div class="space-y-4 border-b border-gray-200 pb-6">
                            <!-- Subtotal -->
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Tạm tính:</span>
                                <span class="font-semibold text-gray-900">
                                    {{ number_format($this->subtotal, 0, ',', '.') }}₫
                                </span>
                            </div>

                            <!-- Discount Code -->
                            <div class="space-y-2">
                                <div class="flex gap-2">
                                    <input
                                        type="text"
                                        wire:model.live="couponCode"
                                        placeholder="Mã giảm giá"
                                        class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                        @keydown.enter="$wire.applyCoupon()">
                                    <button
                                        wire:click="applyCoupon"
                                        type="button"
                                        class="px-4 py-2 bg-red-600 text-white rounded-lg font-semibold text-sm hover:bg-red-700 transition-colors whitespace-nowrap">
                                        Áp dụng
                                    </button>
                                </div>

                                @if ($this->errorMessage)
                                    <p class="text-red-600 text-xs">{{ $this->errorMessage }}</p>
                                @endif

                                @if ($this->successMessage)
                                    <p class="text-green-600 text-xs">{{ $this->successMessage }}</p>
                                @endif

                                @if ($this->appliedCoupon)
                                    <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                                        <div class="flex justify-between items-center mb-2">
                                            <span class="font-semibold text-green-700 text-sm">Mã: {{ $this->appliedCoupon->code }}</span>
                                            <button
                                                wire:click="removeCoupon"
                                                type="button"
                                                class="text-green-600 hover:text-green-700 text-xs">
                                                ✕ Xóa
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
                        <div class="pt-6 border-b border-gray-200 pb-6">
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
                                class="w-full px-6 py-3 bg-red-600 text-white rounded-lg font-bold hover:bg-red-700 transition-colors">
                                Tiến hành thanh toán
                            </button>
                            <button
                                wire:click="continueShopping"
                                type="button"
                                class="hidden lg:block w-full px-6 py-3 border-2 border-red-600 text-red-600 rounded-lg font-semibold hover:bg-red-50 transition-colors">
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
