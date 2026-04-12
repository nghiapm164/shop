@extends('layouts.app')

@section('content')
<div class="bg-white min-h-screen flex items-center">
    <div class="w-full max-w-2xl mx-auto px-4">
        <!-- Success Animation -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center mb-6">
                <svg
                    class="w-24 h-24 text-green-500 animate-bounce"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>

            <h1 class="text-4xl font-bold text-gray-900 mb-2">Đặt hàng thành công!</h1>
            <p class="text-lg text-gray-600">Cảm ơn bạn đã tin tưởng chúng tôi</p>
        </div>

        <!-- Order Code -->
        <div class="bg-gray-50 rounded-lg p-6 mb-8">
            <p class="text-gray-600 text-sm mb-2">Mã đơn hàng của bạn</p>
            <p class="text-3xl font-bold text-red-600 font-mono">{{ $order->order_code }}</p>
            <p class="text-gray-500 text-sm mt-2">Ngày đặt hàng: {{ $order->created_at->format('d/m/Y H:i') }}</p>
        </div>

        <!-- Order Details Tabs -->
        <div class="space-y-6" x-data="{ activeTab: 'items' }">
            <!-- Tabs Navigation -->
            <div class="flex gap-4 border-b border-gray-200">
                <button
                    @click="activeTab = 'items'"
                    :class="activeTab === 'items' ? 'border-b-2 border-red-600 text-red-600' : 'text-gray-600 hover:text-gray-900'"
                    class="py-4 px-6 font-semibold transition-colors">
                    Sản phẩm ({{ $order->items->count() }})
                </button>
                <button
                    @click="activeTab = 'address'"
                    :class="activeTab === 'address' ? 'border-b-2 border-red-600 text-red-600' : 'text-gray-600 hover:text-gray-900'"
                    class="py-4 px-6 font-semibold transition-colors">
                    Địa chỉ giao hàng
                </button>
                <button
                    @click="activeTab = 'payment'"
                    :class="activeTab === 'payment' ? 'border-b-2 border-red-600 text-red-600' : 'text-gray-600 hover:text-gray-900'"
                    class="py-4 px-6 font-semibold transition-colors">
                    Thanh toán
                </button>
            </div>

            <!-- Items Tab -->
            <div x-show="activeTab === 'items'" class="space-y-4">
                @foreach ($order->items as $item)
                    <div class="flex gap-4 bg-gray-50 rounded-lg p-4">
                        <img
                            src="{{ asset('storage/' . $item->productVariant->product->images->first()?->image_path ?? 'images/placeholder.jpg') }}"
                            alt="{{ $item->product_name }}"
                            class="w-20 h-20 object-cover rounded">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900">{{ $item->product_name }}</h3>
                            <p class="text-sm text-gray-600 mt-1">
                                Màu: <span class="font-semibold">{{ $item->color }}</span>
                                | Kích cỡ: <span class="font-semibold">{{ $item->size }}</span>
                            </p>
                            <p class="text-sm text-gray-600 mt-1">
                                Số lượng: <span class="font-semibold">{{ $item->quantity }}</span>
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-600">Giá</p>
                            <p class="font-semibold text-gray-900">{{ number_format($item->price, 0, ',', '.') }}₫</p>
                            <p class="text-sm text-gray-600 mt-2">Thành tiền</p>
                            <p class="font-bold text-red-600">{{ number_format($item->subtotal, 0, ',', '.') }}₫</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Address Tab -->
            <div x-show="activeTab === 'address'" class="bg-gray-50 rounded-lg p-6">
                @php
                    $shippingAddress = json_decode($order->shipping_address, true);
                @endphp
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">Người nhận</p>
                        <p class="font-semibold text-gray-900 text-lg">{{ $shippingAddress['recipient_name'] ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Số điện thoại</p>
                        <p class="font-semibold text-gray-900">{{ $shippingAddress['phone'] ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Địa chỉ giao hàng</p>
                        <p class="font-semibold text-gray-900 mt-1">
                            {{ $shippingAddress['address'] ?? 'N/A' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Payment Tab -->
            <div x-show="activeTab === 'payment'" class="bg-gray-50 rounded-lg p-6">
                <div class="space-y-4">
                    <!-- Order Summary -->
                    <div class="space-y-3 pb-4 border-b border-gray-200">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tạm tính:</span>
                            <span class="font-semibold">{{ number_format($order->subtotal, 0, ',', '.') }}₫</span>
                        </div>
                        @if ($order->discount_amount > 0)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Giảm giá:</span>
                                <span class="font-semibold text-green-600">-{{ number_format($order->discount_amount, 0, ',', '.') }}₫</span>
                            </div>
                        @endif
                        <div class="flex justify-between">
                            <span class="text-gray-600">Phí vận chuyển:</span>
                            <span class="font-semibold">
                                @if ($order->shipping_fee == 0)
                                    <span class="text-green-600">Miễn phí</span>
                                @else
                                    {{ number_format($order->shipping_fee, 0, ',', '.') }}₫
                                @endif
                            </span>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="pt-4">
                        <p class="text-sm text-gray-600 mb-2">Phương thức thanh toán</p>
                        <p class="font-bold text-gray-900 text-lg">
                            @switch($order->payment_method)
                                @case('cod')
                                    💳 Thanh toán khi nhận hàng (COD)
                                    @break
                                @case('bank')
                                    🏦 Chuyển khoản ngân hàng
                                    @break
                                @case('vnpay')
                                    💳 VNPay
                                    @break
                                @default
                                    {{ $order->payment_method }}
                            @endswitch
                        </p>
                    </div>

                    <!-- Payment Status -->
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <p class="text-sm text-gray-600 mb-2">Trạng thái thanh toán</p>
                        <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-600' : 'bg-yellow-100 text-yellow-600' }}">
                            @if ($order->payment_status === 'paid')
                                ✓ Đã thanh toán
                            @else
                                ⏱ Chờ thanh toán
                            @endif
                        </span>
                    </div>

                    <!-- Total -->
                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <div class="flex justify-between items-baseline">
                            <span class="text-gray-700 font-semibold">Tổng cộng:</span>
                            <span class="text-3xl font-bold text-red-600">
                                {{ number_format($order->total, 0, ',', '.') }}₫
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Call to Action Buttons -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-12">
            <a
                href="{{ route('order.detail', ['code' => $order->order_code]) }}"
                class="flex items-center justify-center gap-2 px-6 py-3 bg-red-600 text-white rounded-lg font-bold hover:bg-red-700 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                Xem chi tiết đơn hàng
            </a>
            <a
                href="/"
                class="flex items-center justify-center gap-2 px-6 py-3 border-2 border-red-600 text-red-600 rounded-lg font-bold hover:bg-red-50 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 4l4 2m-8-8l4-2m12 0l-4 2"></path>
                </svg>
                Về trang chủ
            </a>
        </div>

        <!-- Info Section -->
        <div class="mt-12 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h3 class="font-semibold text-blue-900 mb-3">📞 Cần hỗ trợ?</h3>
            <p class="text-blue-800 text-sm">
                Nếu bạn có thắc mắc về đơn hàng, vui lòng liên hệ với chúng tôi qua:
                <br>
                <a href="tel:0123456789" class="font-semibold hover:underline">0123 456 789</a> hoặc
                <a href="mailto:support@shop.com" class="font-semibold hover:underline">support@shop.com</a>
            </p>
        </div>
    </div>
</div>

<style>
    @keyframes bounce {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-10px);
        }
    }

    .animate-bounce {
        animation: bounce 1s infinite;
    }
</style>
@endsection
