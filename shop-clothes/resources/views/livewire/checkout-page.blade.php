<div class="min-h-screen pb-12">
    <!-- Header -->
    <div class="max-w-7xl mx-auto px-4 pt-8 pb-4">
        <div class="fashion-section p-6 md:p-7">
            <h1 class="fashion-title text-3xl md:text-4xl">Thanh toán</h1>
            <p class="fashion-subtitle mt-2">Hoàn tất đơn hàng trong vài bước nhanh gọn.</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <form wire:submit="placeOrder" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Forms -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Shipping Address Section -->
                <div class="fashion-section p-6">
                    <h2 class="fashion-title text-2xl mb-6">Địa chỉ giao hàng</h2>

                    <!-- Saved Addresses -->
                    @if ($this->savedAddresses->count() > 0 && !$useNewAddress)
                        <div class="mb-6">
                            <h3 class="font-semibold text-gray-900 mb-4">Địa chỉ đã lưu</h3>
                            <div class="grid grid-cols-1 gap-3">
                                @foreach ($this->savedAddresses as $address)
                                    <div class="relative">
                                        <label class="block cursor-pointer">
                                            <input
                                                type="radio"
                                                name="address"
                                                value="{{ $address->id }}"
                                                wire:click="selectAddress({{ $address->id }})"
                                                class="sr-only"
                                                {{ $selectedAddressId === $address->id ? 'checked' : '' }}>
                                            <div class="border-2 rounded-lg p-4 transition-all {{ $selectedAddressId === $address->id ? 'border-red-600 bg-red-50' : 'border-gray-200 hover:border-gray-300' }}">
                                                <div class="flex items-start justify-between">
                                                    <div class="flex-1">
                                                        <div class="flex items-center gap-2">
                                                            <p class="font-semibold text-gray-900">{{ $address->recipient_name }}</p>
                                                            @if ($address->is_default)
                                                                <span class="text-xs bg-red-100 text-red-600 px-2 py-0.5 rounded font-medium">Mặc định</span>
                                                            @endif
                                                        </div>
                                                        <p class="text-sm text-gray-600 mt-1">{{ $address->phone }}</p>
                                                        <p class="text-sm text-gray-600 mt-1">{{ $address->full_address }}</p>
                                                    </div>
                                                </div>

                                                <!-- Address Actions -->
                                                <div class="flex items-center gap-3 mt-3 pt-3 border-t border-gray-100">
                                                    @if (!$address->is_default)
                                                        <button type="button"
                                                            wire:click="setDefaultAddress({{ $address->id }})"
                                                            class="text-xs text-blue-600 hover:text-blue-700 font-medium">
                                                            Đặt mặc định
                                                        </button>
                                                    @endif
                                                    <button type="button"
                                                        wire:click="editAddress({{ $address->id }})"
                                                        class="text-xs text-gray-600 hover:text-gray-700 font-medium">
                                                        ✏️ Sửa
                                                    </button>
                                                    <button type="button"
                                                        wire:click="deleteAddress({{ $address->id }})"
                                                        wire:confirm="Bạn có chắc muốn xóa địa chỉ này?"
                                                        class="text-xs text-red-600 hover:text-red-700 font-medium">
                                                        🗑️ Xóa
                                                    </button>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>

                            <button
                                type="button"
                                wire:click="useNewAddress"
                                class="mt-4 text-red-600 hover:text-red-700 font-semibold text-sm flex items-center gap-1">
                                + Thêm địa chỉ mới
                            </button>
                        </div>
                    @endif

                    <!-- New/Edit Address Form -->
                    @if ($useNewAddress)
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <h3 class="font-semibold text-gray-900">
                                    @if ($editingAddressId)
                                        Chỉnh sửa địa chỉ
                                    @elseif ($this->savedAddresses->count() > 0)
                                        Địa chỉ mới
                                    @else
                                        Nhập địa chỉ giao hàng
                                    @endif
                                </h3>
                                @if ($this->savedAddresses->count() > 0)
                                    <button type="button" wire:click="cancelEditAddress"
                                        class="text-sm text-gray-500 hover:text-gray-700">
                                        Hủy
                                    </button>
                                @endif
                            </div>

                            <!-- Recipient Name -->
                            <div>
                                <label for="recipientName" class="block text-sm font-semibold text-gray-900 mb-2">
                                    Họ và tên *
                                </label>
                                <input
                                    type="text"
                                    id="recipientName"
                                    wire:model.lazy="recipientName"
                                    placeholder="Nhập họ và tên người nhận"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent @error('recipientName') border-red-500 @enderror">
                                @error('recipientName')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div>
                                <label for="phone" class="block text-sm font-semibold text-gray-900 mb-2">
                                    Số điện thoại *
                                </label>
                                <input
                                    type="tel"
                                    id="phone"
                                    wire:model.lazy="phone"
                                    placeholder="0xxxxxxxxx"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent @error('phone') border-red-500 @enderror">
                                @error('phone')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Address -->
                            <div>
                                <label for="addressDetail" class="block text-sm font-semibold text-gray-900 mb-2">
                                    Địa chỉ giao hàng *
                                </label>
                                <textarea
                                    id="addressDetail"
                                    wire:model.lazy="addressDetail"
                                    placeholder="Số nhà, tên đường, phường/xã, quận/huyện, tỉnh/thành phố"
                                    rows="3"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent @error('addressDetail') border-red-500 @enderror"></textarea>
                                @error('addressDetail')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Save Address Button (separate from place order) -->
                            <div class="flex gap-3">
                                <button type="button" wire:click="saveAddress"
                                    class="px-6 py-2.5 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 transition-colors text-sm">
                                    {{ $editingAddressId ? 'Cập nhật địa chỉ' : 'Lưu địa chỉ' }}
                                </button>
                                @if ($this->savedAddresses->count() > 0)
                                    <button type="button" wire:click="cancelEditAddress"
                                        class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition-colors text-sm">
                                        Hủy
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Voucher Section -->
                <div class="fashion-section p-6">
                    <h2 class="fashion-title text-2xl mb-4">Mã giảm giá</h2>

                    @if ($appliedCoupon)
                        <div class="flex items-center justify-between bg-green-50 border border-green-200 rounded-lg p-4">
                            <div>
                                <p class="font-semibold text-green-800">Mã: {{ $appliedCoupon->code }}</p>
                                <p class="text-sm text-green-600 mt-1">
                                    Giảm {{ number_format($discountAmount, 0, ',', '.') }}₫
                                </p>
                            </div>
                            <button type="button" wire:click="removeCoupon"
                                class="text-red-600 hover:text-red-700 font-semibold text-sm">
                                ✕ Bỏ mã
                            </button>
                        </div>
                    @else
                        <div class="flex gap-2">
                            <input type="text" wire:model="couponCode" placeholder="Nhập mã giảm giá"
                                class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent uppercase"
                                wire:keydown.enter="applyCoupon">
                            <button type="button" wire:click="applyCoupon"
                                class="px-6 py-3 bg-red-600 text-white rounded-lg font-semibold hover:bg-red-700 transition-colors whitespace-nowrap">
                                Áp dụng
                            </button>
                        </div>
                        @if ($couponError)
                            <p class="text-red-600 text-xs mt-2">{{ $couponError }}</p>
                        @endif
                        @if ($couponSuccess)
                            <p class="text-green-600 text-xs mt-2">{{ $couponSuccess }}</p>
                        @endif

                        <!-- Available Coupons -->
                        @if ($this->availableCoupons->count() > 0)
                            <div class="mt-4">
                                <p class="text-xs text-gray-500 font-medium mb-2">Mã giảm giá khả dụng:</p>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                    @foreach ($this->availableCoupons as $coupon)
                                        <div class="border border-dashed border-red-200 rounded-lg p-3 bg-red-50/50 flex items-center justify-between gap-2">
                                            <div>
                                                <p class="text-sm font-bold text-red-600">{{ $coupon->code }}</p>
                                                <p class="text-xs text-gray-500 mt-0.5">
                                                    @if ($coupon->type === 'percent')
                                                        Giảm {{ $coupon->value }}%
                                                        @if ($coupon->max_discount)
                                                            (tối đa {{ number_format($coupon->max_discount, 0, ',', '.') }}₫)
                                                        @endif
                                                    @else
                                                        Giảm {{ number_format($coupon->value, 0, ',', '.') }}₫
                                                    @endif
                                                </p>
                                                @if ($coupon->min_order_amount)
                                                    <p class="text-[10px] text-gray-400 mt-0.5">Đơn tối thiểu {{ number_format($coupon->min_order_amount, 0, ',', '.') }}₫</p>
                                                @endif
                                            </div>
                                            <button type="button" wire:click="applyCouponCode('{{ $coupon->code }}')"
                                                class="text-xs text-red-600 hover:text-red-700 font-semibold border border-red-300 px-3 py-1 rounded hover:bg-red-100 transition-colors whitespace-nowrap">
                                                Dùng
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endif
                </div>

                <!-- Order Notes -->
                <div class="fashion-section p-6">
                    <h2 class="fashion-title text-2xl mb-6">Ghi chú đơn hàng</h2>
                    <textarea
                        wire:model.lazy="notes"
                        placeholder="Nhập ghi chú cho đơn hàng (tùy chọn)"
                        rows="4"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent @error('notes') border-red-500 @enderror"></textarea>
                    @error('notes')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Payment Method -->
                <div class="fashion-section p-6">
                    <h2 class="fashion-title text-2xl mb-6">Phương thức thanh toán</h2>

                    <div class="space-y-3">
                        <!-- COD -->
                        <label class="relative">
                            <input type="radio" name="paymentMethod" value="cod" wire:model="paymentMethod" class="sr-only">
                            <div class="border-2 rounded-lg p-4 cursor-pointer transition-all border-red-600 bg-red-50">
                                <div class="flex items-center gap-4">
                                    <div class="text-3xl">💳</div>
                                    <div>
                                        <p class="font-semibold text-gray-900">Thanh toán khi nhận hàng (COD)</p>
                                        <p class="text-sm text-gray-500 mt-1">Thanh toán tiền mặt khi nhận đơn hàng</p>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Right Column - Order Summary -->
            <div class="lg:col-span-1">
                <div class="fashion-section p-6 sticky top-24">
                    <h3 class="fashion-title text-xl mb-6">Tóm tắt đơn hàng</h3>

                    <!-- Items List -->
                    <div class="space-y-3 mb-6 pb-6 border-b border-slate-200 max-h-96 overflow-y-auto">
                        @foreach ($this->cartItems as $item)
                            <div class="flex gap-3">
                                <img
                                    src="{{ str_starts_with($item['image_url'] ?? '', 'http://') || str_starts_with($item['image_url'] ?? '', 'https://') ? ($item['image_url'] ?? asset('images/product-placeholder.svg')) : asset($item['image_url'] ?? 'images/product-placeholder.svg') }}"
                                    alt="{{ $item['product_name'] }}"
                                    class="w-12 h-12 object-cover rounded-xl border border-slate-200">
                                <div class="flex-1 text-sm">
                                    <p class="font-semibold text-gray-900 line-clamp-1">{{ $item['product_name'] }}</p>
                                    <p class="text-gray-500">{{ $item['size'] }} - {{ $item['color'] }}</p>
                                    <p class="text-gray-600">x{{ $item['quantity'] }}</p>
                                </div>
                                <p class="font-semibold text-gray-900 text-sm text-right">
                                    {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}₫
                                </p>
                            </div>
                        @endforeach
                    </div>

                    <!-- Summary -->
                    <div class="space-y-3 border-b border-slate-200 pb-6">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Tạm tính:</span>
                            <span class="font-semibold">{{ number_format($this->subtotal, 0, ',', '.') }}₫</span>
                        </div>
                        @if ($this->discountAmount > 0)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Giảm giá:</span>
                                <span class="font-semibold text-green-600">-{{ number_format($this->discountAmount, 0, ',', '.') }}₫</span>
                            </div>
                        @endif
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Phí vận chuyển:</span>
                            <span class="font-semibold">
                                @if ($this->shippingFee == 0)
                                    <span class="text-green-600">Miễn phí</span>
                                @else
                                    {{ number_format($this->shippingFee, 0, ',', '.') }}₫
                                @endif
                            </span>
                        </div>
                    </div>

                    <!-- Total -->
                    <div class="pt-6 mb-6">
                        <div class="flex justify-between items-baseline">
                            <span class="text-gray-700 font-semibold">Tổng cộng:</span>
                            <span class="text-3xl font-bold text-red-600">
                                {{ number_format($this->total, 0, ',', '.') }}₫
                            </span>
                        </div>
                    </div>

                    <!-- Place Order Button -->
                    <button type="submit" class="w-full btn-primary py-3">
                        Đặt hàng
                    </button>

                    <!-- Back to Cart -->
                    <a href="/cart"
                        class="block text-center mt-3 px-6 py-3 rounded-xl border border-red-500 text-red-500 font-semibold hover:bg-red-50 transition-colors">
                        Quay lại giỏ hàng
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
