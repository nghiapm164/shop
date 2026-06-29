@extends('layouts.app')

@section('meta_title', 'Kết quả tra cứu đơn hàng ' . $order->order_code . ' - MODESPORT')

@section('content')
@php
    $statusSteps = [
        'pending' => ['label' => 'Chờ xác nhận', 'color' => 'blue', 'icon' => 'clock'],
        'confirmed' => ['label' => 'Đã xác nhận', 'color' => 'sky', 'icon' => 'check-circle'],
        'processing' => ['label' => 'Đang xử lý', 'color' => 'amber', 'icon' => 'box'],
        'shipped' => ['label' => 'Đang giao', 'color' => 'violet', 'icon' => 'truck'],
        'delivered' => ['label' => 'Đã giao', 'color' => 'green', 'icon' => 'check-badge'],
        'cancelled' => ['label' => 'Đã hủy', 'color' => 'red', 'icon' => 'x-circle'],
        'refunded' => ['label' => 'Hoàn tiền', 'color' => 'slate', 'icon' => 'arrow-uturn-left'],
    ];

    $statusOrder = ['pending', 'confirmed', 'processing', 'shipped', 'delivered'];
    $currentStatusIndex = array_search($order->status, $statusOrder);
    $isCancelled = in_array($order->status, ['cancelled', 'refunded']);
    $statusInfo = $statusSteps[$order->status] ?? ['label' => ucfirst($order->status), 'color' => 'gray', 'icon' => 'question-mark-circle'];
@endphp

<div class="max-w-4xl mx-auto px-4 py-12">
    {{-- Header --}}
    <div class="text-center mb-8">
        <a href="{{ route('order.lookup') }}" class="text-sm text-red-600 hover:text-red-700 font-medium mb-3 inline-block">
            ← Tra cứu đơn khác
        </a>
        <h1 class="fashion-title text-3xl">Đơn hàng {{ $order->order_code }}</h1>
        <p class="text-slate-500 mt-1">Đặt ngày {{ $order->created_at->format('d/m/Y lúc H:i') }}</p>
    </div>

    {{-- Status Banner --}}
    <div class="fashion-section p-6 mb-6
        @if($order->status === 'delivered') border-l-4 border-green-500
        @elseif($order->status === 'cancelled') border-l-4 border-red-500
        @elseif($order->status === 'shipped') border-l-4 border-violet-500
        @else border-l-4 border-blue-500
        @endif
    ">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-full
                @if($order->status === 'delivered') bg-green-100 text-green-600
                @elseif($order->status === 'cancelled') bg-red-100 text-red-600
                @elseif($order->status === 'shipped') bg-violet-100 text-violet-600
                @else bg-blue-100 text-blue-600
                @endif
                flex items-center justify-center text-2xl">
                @if($order->status === 'delivered') 🎉
                @elseif($order->status === 'cancelled') ❌
                @elseif($order->status === 'shipped') 🚚
                @elseif($order->status === 'processing') 📦
                @else 📋
                @endif
            </div>
            <div>
                <h2 class="text-xl font-bold text-slate-900">{{ $statusInfo['label'] }}</h2>
                <p class="text-sm text-slate-500 mt-1">
                    @if($order->status === 'pending')
                        Đơn hàng đang chờ xác nhận từ shop.
                    @elseif($order->status === 'confirmed')
                        Đơn hàng đã được xác nhận và sẽ sớm được chuẩn bị.
                    @elseif($order->status === 'processing')
                        Đơn hàng đang được đóng gói cẩn thận.
                    @elseif($order->status === 'shipped')
                        Đơn hàng đã được giao cho đơn vị vận chuyển. Dự kiến 2-5 ngày.
                    @elseif($order->status === 'delivered')
                        Đơn hàng đã giao thành công. Cảm ơn bạn đã mua hàng!
                    @elseif($order->status === 'cancelled')
                        Đơn hàng đã bị hủy.
                    @elseif($order->status === 'refunded')
                        Tiền đã được hoàn trả.
                    @endif
                </p>
            </div>
        </div>
    </div>

    {{-- Status Timeline (only for normal flow) --}}
    @if(!$isCancelled)
        <div class="fashion-section p-6 mb-6">
            <h3 class="font-bold text-slate-900 mb-4">Tiến trình đơn hàng</h3>
            <div class="flex items-center justify-between relative">
                {{-- Progress line background --}}
                <div class="absolute top-5 left-0 right-0 h-1 bg-slate-200 mx-10"></div>
                {{-- Progress line active --}}
                @if($currentStatusIndex !== false && $currentStatusIndex >= 0)
                    <div class="absolute top-5 left-0 h-1 bg-red-500 mx-10 transition-all" style="width: calc({{ $currentStatusIndex }} / {{ count($statusOrder) - 1 }} * (100% - 5rem))"></div>
                @endif

                @foreach($statusOrder as $index => $step)
                    @php
                        $stepInfo = $statusSteps[$step];
                        $isActive = $currentStatusIndex !== false && $index <= $currentStatusIndex;
                        $isCurrent = $order->status === $step;
                    @endphp
                    <div class="relative z-10 flex flex-col items-center text-center" style="flex: 1;">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold
                            {{ $isActive ? 'bg-red-500 text-white' : 'bg-slate-200 text-slate-400' }}
                            {{ $isCurrent ? 'ring-4 ring-red-200' : '' }}
                        ">
                            @if($isActive)
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            @else
                                {{ $index + 1 }}
                            @endif
                        </div>
                        <p class="text-xs mt-2 font-medium {{ $isActive ? 'text-red-600' : 'text-slate-400' }}">{{ $stepInfo['label'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Order Details --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Items --}}
            <div class="fashion-section p-6">
                <h3 class="font-bold text-slate-900 mb-4">Sản phẩm ({{ $order->items->count() }})</h3>
                <div class="space-y-4">
                    @foreach($order->items as $item)
                        @php
                            $variant = $item->productVariant;
                            $product = $variant?->product;
                            $image = $product?->image_url ?? asset('images/product-placeholder.svg');
                            $imgSrc = \Illuminate\Support\Str::startsWith($image, ['http://', 'https://']) ? $image : asset($image);
                        @endphp
                        <div class="flex gap-4 pb-4 {{ !$loop->last ? 'border-b border-slate-100' : '' }}">
                            <img src="{{ $imgSrc }}" alt="{{ $item->product_name }}" class="w-16 h-16 object-cover rounded-xl border border-slate-200">
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-slate-900 text-sm">{{ $item->product_name }}</p>
                                <p class="text-xs text-slate-500 mt-0.5">
                                    @if($item->size) Size: {{ $item->size }} @endif
                                    @if($item->color) &bull; Màu: {{ $item->color }} @endif
                                </p>
                                <p class="text-xs text-slate-500 mt-0.5">x{{ $item->quantity }}</p>
                            </div>
                            <p class="font-bold text-slate-900 text-sm whitespace-nowrap">
                                {{ number_format($item->subtotal, 0, ',', '.') }}₫
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Shipping Address --}}
            <div class="fashion-section p-6">
                <h3 class="font-bold text-slate-900 mb-3">Địa chỉ giao hàng</h3>
                @if($order->shipping_address)
                    <div class="text-sm text-slate-600 space-y-1">
                        <p class="font-semibold text-slate-900">{{ $order->shipping_address['recipient_name'] ?? 'N/A' }}</p>
                        <p>{{ $order->shipping_address['phone'] ?? '' }}</p>
                        <p>{{ $order->shipping_address['address'] ?? '' }}</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Summary --}}
        <div class="space-y-6">
            <div class="fashion-section p-6 sticky top-24">
                <h3 class="font-bold text-slate-900 mb-4">Tóm tắt thanh toán</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Tạm tính:</span>
                        <span class="font-semibold">{{ number_format($order->subtotal, 0, ',', '.') }}₫</span>
                    </div>
                    @if($order->discount_amount > 0)
                        <div class="flex justify-between">
                            <span class="text-slate-500">Giảm giá:</span>
                            <span class="font-semibold text-green-600">-{{ number_format($order->discount_amount, 0, ',', '.') }}₫</span>
                        </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-slate-500">Phí ship:</span>
                        <span class="font-semibold">
                            @if($order->shipping_fee > 0)
                                {{ number_format($order->shipping_fee, 0, ',', '.') }}₫
                            @else
                                <span class="text-green-600">Miễn phí</span>
                            @endif
                        </span>
                    </div>
                    <div class="border-t border-slate-200 pt-3 flex justify-between">
                        <span class="font-bold text-slate-900">Tổng cộng:</span>
                        <span class="text-xl font-black text-red-600">{{ number_format($order->total, 0, ',', '.') }}₫</span>
                    </div>
                </div>

                <div class="mt-4 pt-4 border-t border-slate-200 space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Thanh toán:</span>
                        <span class="font-medium">
                            @if($order->payment_method === 'cod') COD
                            @elseif($order->payment_method === 'bank_transfer') Chuyển khoản
                            @elseif($order->payment_method === 'e_wallet') VNPay
                            @else {{ $order->payment_method }}
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">TT thanh toán:</span>
                        <span class="font-medium
                            @if($order->payment_status === 'paid') text-green-600
                            @elseif($order->payment_status === 'unpaid') text-amber-600
                            @else text-slate-600
                            @endif
                        ">
                            @if($order->payment_status === 'paid') Đã thanh toán
                            @elseif($order->payment_status === 'unpaid') Chưa thanh toán
                            @else {{ $order->payment_status }}
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="space-y-3">
                <a href="{{ route('shop.index') }}" class="block w-full text-center rounded-xl bg-red-500 hover:bg-red-600 text-white py-3 font-bold text-sm transition">
                    Tiếp tục mua sắm
                </a>
                <a href="{{ route('pages.contact') }}" class="block w-full text-center rounded-xl border border-slate-300 text-slate-700 py-3 font-semibold text-sm hover:bg-slate-50 transition">
                    Liên hệ hỗ trợ
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
