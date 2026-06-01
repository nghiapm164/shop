@extends('layouts.app')

@section('content')
<div class="bg-white min-h-screen">
    <!-- Header -->
    <div class="max-w-7xl mx-auto px-4 py-8 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                    <h1 class="text-2xl font-bold text-gray-900">Chi tiết đơn hàng</h1>
                    <p class="text-gray-500 mt-1 text-sm">{{ $order->order_code }}</p>
            </div>
            <a href="/" class="text-red-600 hover:text-red-700 font-semibold">← Quay lại</a>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Order Status -->
        <div class="bg-gray-50 rounded-lg p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Trạng thái đơn hàng</p>
                    <p class="font-semibold text-gray-900">
                        <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold {{ $order->status === 'delivered' ? 'bg-green-100 text-green-600' : ($order->status === 'cancelled' ? 'bg-red-100 text-red-600' : 'bg-blue-100 text-blue-600') }}">
                            @switch($order->status)
                                @case('pending')
                                    ⏱ Chờ xử lý
                                    @break
                                @case('processing')
                                    📦 Đang chuẩn bị
                                    @break
                                @case('shipped')
                                    🚚 Đang giao
                                    @break
                                @case('completed')
                                    ✓ Hoàn thành
                                    @break
                                @case('cancelled')
                                    ✕ Đã hủy
                                    @break
                                @default
                                    {{ $order->status }}
                            @endswitch
                        </span>
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Trạng thái thanh toán</p>
                    <p class="font-semibold text-gray-900">
                        <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-600' : 'bg-yellow-100 text-yellow-600' }}">
                            {{ $order->payment_status === 'paid' ? '✓ Đã thanh toán' : '⏱ Chờ thanh toán' }}
                        </span>
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Ngày đặt hàng</p>
                    <p class="font-semibold text-gray-900">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Phương thức thanh toán</p>
                    <p class="font-semibold text-gray-900">
                        @switch($order->payment_method)
                            @case('cod')
                                COD
                                @break
                            @case('bank')
                                Chuyển khoản
                                @break
                            @case('vnpay')
                                VNPay
                                @break
                        @endswitch
                    </p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Products List -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg border border-gray-200 p-6 mb-8">
                    <h2 class="text-lg font-bold text-gray-900 mb-6">Sản phẩm ({{ $order->items->count() }})</h2>

                    <div class="space-y-4">
                        @foreach ($order->items as $item)
                            @php
                                $product = $item->productVariant?->product;
                                $imageUrl = $product?->image_url ?? asset('images/product-placeholder.svg');
                            @endphp
                            <div class="flex gap-4 pb-4 border-b border-gray-200 last:border-0">
                                <img
                                    src="{{ $imageUrl }}"
                                    alt="{{ $item->product_name }}"
                                    class="w-20 h-20 object-cover rounded">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900">{{ $item->product_name }}</h3>
                                    <p class="text-sm text-gray-600 mt-1">
                                        Màu: <span class="font-semibold">{{ $item->color }}</span>
                                        | Kích cỡ: <span class="font-semibold">{{ $item->size }}</span>
                                    </p>
                                    <p class="text-sm text-gray-600 mt-1">
                                        Số lượng: <span class="font-semibold">x{{ $item->quantity }}</span>
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-gray-900">{{ number_format($item->price, 0, ',', '.') }}₫</p>
                                    <p class="text-sm text-gray-600 mt-2">Thành tiền</p>
                                    <p class="font-bold text-red-600">{{ number_format($item->subtotal, 0, ',', '.') }}₫</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Địa chỉ giao hàng</h2>
                    @php
                        $shippingAddress = is_array($order->shipping_address) ? $order->shipping_address : json_decode($order->shipping_address, true);
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
                            <p class="font-semibold text-gray-900 mt-1">{{ $shippingAddress['address'] ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-gray-50 rounded-lg p-6 sticky top-24">
                    <h3 class="text-lg font-bold text-gray-900 mb-6">Tóm tắt đơn hàng</h3>

                    <div class="space-y-3 pb-6 border-b border-gray-200">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Tạm tính:</span>
                            <span class="font-semibold">{{ number_format($order->subtotal, 0, ',', '.') }}₫</span>
                        </div>
                        @if ($order->discount_amount > 0)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Giảm giá:</span>
                                <span class="font-semibold text-green-600">-{{ number_format($order->discount_amount, 0, ',', '.') }}₫</span>
                            </div>
                        @endif
                        <div class="flex justify-between text-sm">
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

                    <!-- Total -->
                    <div class="pt-6">
                        <div class="flex justify-between items-baseline">
                            <span class="text-gray-700 font-semibold">Tổng cộng:</span>
                            <span class="text-3xl font-bold text-red-600">
                                {{ number_format($order->total, 0, ',', '.') }}₫
                            </span>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="mt-6 space-y-2">
                        @if ($order->status !== 'completed' && $order->status !== 'cancelled')
                            <form method="POST" action="{{ route('client.orders.cancel', $order) }}" onsubmit="return confirm('Bạn có chắc muốn hủy đơn hàng này?')">
                                @csrf
                                <button type="submit" class="w-full px-4 py-2 bg-orange-600 text-white rounded-lg font-semibold hover:bg-orange-700 transition-colors text-sm">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                                    Hủy đơn hàng
                                </button>
                            </form>
                        @endif
                        <button class="w-full px-4 py-2 border-2 border-gray-300 text-gray-900 rounded-lg font-semibold hover:border-gray-400 transition-colors text-sm">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                            Liên hệ hỗ trợ
                        </button>
                    </div>

                    <!-- Notes Section -->
                    @if ($order->notes)
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <p class="text-sm text-gray-600 mb-2">Ghi chú</p>
                            <p class="text-sm text-gray-900">{{ $order->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
