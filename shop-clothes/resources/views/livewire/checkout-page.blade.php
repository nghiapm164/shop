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
                    @if ($this->savedAddresses->count() > 0)
                        <div class="mb-6">
                            <h3 class="font-semibold text-gray-900 mb-4">Địa chỉ đã lưu</h3>
                            <div class="grid grid-cols-1 gap-3">
                                @foreach ($this->savedAddresses as $address)
                                    <label class="relative">
                                        <input
                                            type="radio"
                                            name="address"
                                            value="{{ $address->id }}"
                                            wire:model.live="selectedAddressId"
                                            class="sr-only">
                                        <div class="border-2 rounded-lg p-4 cursor-pointer transition-all {{ $this->selectedAddressId === $address->id ? 'border-red-600 bg-red-50' : 'border-gray-200 hover:border-gray-300' }}">
                                            <div class="flex items-start justify-between">
                                                <div>
                                                    <p class="font-semibold text-gray-900">{{ $address->recipient_name }}</p>
                                                    <p class="text-sm text-gray-600 mt-1">{{ $address->phone }}</p>
                                                    <p class="text-sm text-gray-600 mt-2">{{ $address->full_address }}</p>
                                                </div>
                                                @if ($address->is_default)
                                                    <span class="text-xs bg-red-100 text-red-600 px-2 py-1 rounded">Mặc định</span>
                                                @endif
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>

                            <button
                                type="button"
                                wire:click="useNewAddress"
                                class="mt-4 text-red-600 hover:text-red-700 font-semibold text-sm">
                                + Thêm địa chỉ mới
                            </button>
                        </div>
                    @endif

                    <!-- New Address Form -->
                    @if ($this->useNewAddress || $this->savedAddresses->count() == 0)
                        <div class="space-y-4 {{ $this->selectedAddressId && !$this->useNewAddress ? 'hidden' : '' }}">
                            <h3 class="font-semibold text-gray-900">{{ $this->savedAddresses->count() > 0 ? 'Địa chỉ mới' : 'Nhập địa chỉ giao hàng' }}</h3>

                            <!-- Recipient Name -->
                            <div>
                                <label for="recipientName" class="block text-sm font-semibold text-gray-900 mb-2">
                                    Họ và tên *
                                </label>
                                <input
                                    type="text"
                                    id="recipientName"
                                    wire:model.lazy="recipientName"
                                    placeholder="Nhập họ và tên"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent @error('recipientName') border-red-500 @enderror">
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
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent @error('phone') border-red-500 @enderror">
                                @error('phone')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Province -->
                            <div>
                                <label for="province" class="block text-sm font-semibold text-gray-900 mb-2">
                                    Tỉnh/Thành phố *
                                </label>
                                <select
                                    id="province"
                                    wire:model.live="province"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent @error('province') border-red-500 @enderror">
                                    <option value="">Chọn tỉnh/thành phố</option>
                                    @foreach (array_keys($this->provinces) as $prov)
                                        <option value="{{ $prov }}">{{ $prov }}</option>
                                    @endforeach
                                </select>
                                @error('province')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- District -->
                            @if ($this->districts)
                                <div>
                                    <label for="district" class="block text-sm font-semibold text-gray-900 mb-2">
                                        Quận/Huyện *
                                    </label>
                                    <select
                                        id="district"
                                        wire:model.live="district"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent @error('district') border-red-500 @enderror">
                                        <option value="">Chọn quận/huyện</option>
                                        @foreach ($this->districts as $dist)
                                            <option value="{{ $dist }}">{{ $dist }}</option>
                                        @endforeach
                                    </select>
                                    @error('district')
                                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endif

                            <!-- Ward -->
                            @if ($this->wards)
                                <div>
                                    <label for="ward" class="block text-sm font-semibold text-gray-900 mb-2">
                                        Phường/Xã *
                                    </label>
                                    <select
                                        id="ward"
                                        wire:model.lazy="ward"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent @error('ward') border-red-500 @enderror">
                                        <option value="">Chọn phường/xã</option>
                                        @foreach ($this->wards as $w)
                                            <option value="{{ $w }}">{{ $w }}</option>
                                        @endforeach
                                    </select>
                                    @error('ward')
                                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endif

                            <!-- Address Detail -->
                            <div>
                                <label for="addressDetail" class="block text-sm font-semibold text-gray-900 mb-2">
                                    Địa chỉ cụ thể *
                                </label>
                                <input
                                    type="text"
                                    id="addressDetail"
                                    wire:model.lazy="addressDetail"
                                    placeholder="Nhập số nhà, tên đường..."
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent @error('addressDetail') border-red-500 @enderror">
                                @error('addressDetail')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
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
                            <input
                                type="radio"
                                name="paymentMethod"
                                value="cod"
                                wire:model="paymentMethod"
                                class="sr-only">
                            <div class="border-2 rounded-lg p-4 cursor-pointer transition-all {{ $this->paymentMethod === 'cod' ? 'border-red-600 bg-red-50' : 'border-gray-200 hover:border-gray-300' }}">
                                <div class="flex items-center gap-4">
                                    <div class="text-3xl">💳</div>
                                    <div>
                                        <p class="font-semibold text-gray-900">Thanh toán khi nhận hàng (COD)</p>
                                        <p class="text-sm text-gray-500 mt-1">Thanh toán tiền mặt khi nhận đơn hàng</p>
                                    </div>
                                </div>
                            </div>
                        </label>

                        <!-- Bank Transfer -->
                        <label class="relative">
                            <input
                                type="radio"
                                name="paymentMethod"
                                value="bank"
                                wire:model="paymentMethod"
                                class="sr-only">
                            <div class="border-2 rounded-lg p-4 cursor-pointer transition-all {{ $this->paymentMethod === 'bank' ? 'border-red-600 bg-red-50' : 'border-gray-200 hover:border-gray-300' }}">
                                <div class="flex items-center gap-4">
                                    <div class="text-3xl">🏦</div>
                                    <div>
                                        <p class="font-semibold text-gray-900">Chuyển khoản ngân hàng</p>
                                        <p class="text-sm text-gray-500 mt-1">Chuyển khoản để xác nhận đơn hàng</p>
                                    </div>
                                </div>
                            </div>
                        </label>

                        <!-- VNPay -->
                        <label class="relative">
                            <input
                                type="radio"
                                name="paymentMethod"
                                value="vnpay"
                                wire:model="paymentMethod"
                                class="sr-only">
                            <div class="border-2 rounded-lg p-4 cursor-pointer transition-all {{ $this->paymentMethod === 'vnpay' ? 'border-red-600 bg-red-50' : 'border-gray-200 hover:border-gray-300' }}">
                                <div class="flex items-center gap-4">
                                    <div class="text-3xl">💳</div>
                                    <div>
                                        <p class="font-semibold text-gray-900">VNPay</p>
                                        <p class="text-sm text-gray-500 mt-1">Thanh toán trực tuyến qua VNPay</p>
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
                    <button
                        type="submit"
                        class="w-full btn-primary py-3">
                        Đặt hàng
                    </button>

                    <!-- Back to Cart -->
                    <a
                        href="/cart"
                        class="block text-center mt-3 px-6 py-3 rounded-xl border border-red-500 text-red-500 font-semibold hover:bg-red-50 transition-colors">
                        Quay lại giỏ hàng
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
