@extends('layouts.app')

@section('title', 'Đơn hàng ' . $order->order_code)

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    {{-- Back Button --}}
    <a href="{{ route('client.orders.index') }}"
       class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 font-semibold mb-6">
        <i class="fas fa-arrow-left"></i> Quay lại danh sách đơn hàng
    </a>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
        </div>
    @endif

    {{-- Order Header --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold text-gray-900">
                    Đơn hàng <span class="text-red-600">{{ $order->order_code }}</span>
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Đặt ngày {{ $order->created_at->format('d/m/Y lúc H:i') }}
                </p>
            </div>
            <div class="flex items-center gap-3">
                @if($order->status === 'pending')
                    <span class="px-4 py-2 text-sm font-semibold text-blue-600 bg-blue-100 rounded-full">
                        <i class="fas fa-clock mr-1"></i> Chờ xử lý
                    </span>
                @elseif($order->status === 'confirmed')
                    <span class="px-4 py-2 text-sm font-semibold text-sky-600 bg-sky-100 rounded-full">
                        <i class="fas fa-check mr-1"></i> Đã xác nhận
                    </span>
                @elseif($order->status === 'processing')
                    <span class="px-4 py-2 text-sm font-semibold text-amber-600 bg-amber-100 rounded-full">
                        <i class="fas fa-cog mr-1"></i> Đang xử lý
                    </span>
                @elseif($order->status === 'shipped')
                    <span class="px-4 py-2 text-sm font-semibold text-purple-600 bg-purple-100 rounded-full">
                        <i class="fas fa-truck mr-1"></i> Đang giao
                    </span>
                @elseif($order->status === 'delivered')
                    <span class="px-4 py-2 text-sm font-semibold text-green-600 bg-green-100 rounded-full">
                        <i class="fas fa-check-double mr-1"></i> Đã giao
                    </span>
                @elseif($order->status === 'cancelled')
                    <span class="px-4 py-2 text-sm font-semibold text-red-600 bg-red-100 rounded-full">
                        <i class="fas fa-times mr-1"></i> Đã hủy
                    </span>
                @elseif($order->status === 'refunded')
                    <span class="px-4 py-2 text-sm font-semibold text-gray-600 bg-gray-100 rounded-full">
                        <i class="fas fa-undo mr-1"></i> Hoàn tiền
                    </span>
                @endif

                @if(in_array($order->status, ['pending', 'confirmed']))
                    <form method="POST" action="{{ route('client.orders.cancel', $order) }}"
                          onsubmit="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')">
                        @csrf
                        <button type="submit"
                                class="px-4 py-2 text-sm font-semibold text-red-600 border border-red-300 rounded-lg hover:bg-red-50 transition-colors">
                            <i class="fas fa-times mr-1"></i> Hủy đơn
                        </button>
                    </form>
                @endif
            </div>
        </div>

        {{-- Order Progress Bar --}}
        @if(!in_array($order->status, ['cancelled', 'refunded']))
            @php
                $steps = [
                    'pending' => ['label' => 'Đặt hàng', 'icon' => 'fas fa-shopping-cart'],
                    'confirmed' => ['label' => 'Xác nhận', 'icon' => 'fas fa-check'],
                    'processing' => ['label' => 'Đóng gói', 'icon' => 'fas fa-box'],
                    'shipped' => ['label' => 'Vận chuyển', 'icon' => 'fas fa-truck'],
                    'delivered' => ['label' => 'Hoàn thành', 'icon' => 'fas fa-check-double'],
                ];
                $statusOrder = array_keys($steps);
                $currentIndex = array_search($order->status, $statusOrder);
            @endphp
            <div class="mt-6 pt-6 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    @foreach($steps as $key => $step)
                        @php
                            $stepIndex = array_search($key, $statusOrder);
                            $isCompleted = $stepIndex <= $currentIndex;
                            $isCurrent = $key === $order->status;
                        @endphp
                        <div class="flex flex-col items-center relative {{ $loop->first ? '' : 'flex-1' }}">
                            @if(!$loop->first)
                                <div class="absolute top-4 right-1/2 w-full h-0.5 {{ $isCompleted ? 'bg-green-500' : 'bg-gray-300' }} -z-10"></div>
                            @endif
                            <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $isCurrent ? 'bg-red-600 ring-4 ring-red-200' : ($isCompleted ? 'bg-green-500' : 'bg-gray-300') }}">
                                <i class="{{ $step['icon'] }} text-white text-xs"></i>
                            </div>
                            <p class="text-xs mt-2 font-semibold {{ $isCurrent ? 'text-red-600' : ($isCompleted ? 'text-green-600' : 'text-gray-400') }}">
                                {{ $step['label'] }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left: Order Items --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-bold text-gray-900">
                        <i class="fas fa-box mr-2 text-red-600"></i>Sản phẩm
                    </h2>
                </div>
                <div class="divide-y divide-gray-100">
                    @foreach($order->items as $item)
                        @php
                            $variant = $item->productVariant;
                            $product = $variant?->product;
                            $imageUrl = $product?->image_url ?? asset('images/product-placeholder.svg');
                        @endphp
                        <div class="px-6 py-4 flex items-center gap-4">
                            <img src="{{ $imageUrl }}" alt="{{ $item->product_name }}"
                                 class="w-20 h-20 object-cover rounded-lg">
                            <div class="flex-1 min-w-0">
                                @if($product)
                                    <a href="{{ route('products.show', $product->slug) }}"
                                       class="text-sm font-semibold text-gray-900 hover:text-red-600">
                                        {{ $item->product_name }}
                                    </a>
                                @else
                                    <p class="text-sm font-semibold text-gray-900">{{ $item->product_name }}</p>
                                @endif
                                <div class="flex flex-wrap gap-2 mt-1">
                                    @if($item->size)
                                        <span class="text-xs bg-gray-100 px-2 py-0.5 rounded">Size: {{ $item->size }}</span>
                                    @endif
                                    @if($item->color)
                                        <span class="text-xs bg-gray-100 px-2 py-0.5 rounded">Màu: {{ $item->color }}</span>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-500 mt-1">x{{ $item->quantity }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-gray-900">{{ number_format($item->subtotal, 0, ',', '.') }}₫</p>
                                <p class="text-xs text-gray-500">{{ number_format($item->price, 0, ',', '.') }}₫ / cái</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Shipping Address --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">
                    <i class="fas fa-shipping-fast mr-2 text-red-600"></i>Địa chỉ giao hàng
                </h2>
                @if($order->shipping_address)
                    <div class="space-y-2">
                        @if(isset($order->shipping_address['recipient_name']))
                            <p class="text-sm">
                                <i class="fas fa-user text-gray-400 mr-2 w-4"></i>
                                <span class="font-semibold">{{ $order->shipping_address['recipient_name'] }}</span>
                            </p>
                        @endif
                        @if(isset($order->shipping_address['phone']))
                            <p class="text-sm">
                                <i class="fas fa-phone text-gray-400 mr-2 w-4"></i>
                                <span>{{ $order->shipping_address['phone'] }}</span>
                            </p>
                        @endif
                        @if(isset($order->shipping_address['address']))
                            <p class="text-sm">
                                <i class="fas fa-map-marker-alt text-gray-400 mr-2 w-4"></i>
                                <span>{{ $order->shipping_address['address'] }}</span>
                            </p>
                        @endif
                    </div>
                @else
                    <p class="text-sm text-gray-500">Không có thông tin địa chỉ</p>
                @endif
            </div>

            {{-- Notes --}}
            @if($order->notes)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">
                        <i class="fas fa-sticky-note mr-2 text-red-600"></i>Ghi chú
                    </h2>
                    <p class="text-sm text-gray-700">{{ $order->notes }}</p>
                </div>
            @endif
        </div>

        {{-- Right: Summary --}}
        <div class="space-y-6">
            {{-- Payment Info --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">
                    <i class="fas fa-credit-card mr-2 text-red-600"></i>Thanh toán
                </h2>
                <div class="space-y-3">
                    <div>
                        <p class="text-xs text-gray-500">Phương thức</p>
                        <p class="text-sm font-semibold text-gray-900">
                            @if($order->payment_method === 'cod')
                                <i class="fas fa-money-bill-wave text-green-600 mr-1"></i> Thanh toán khi nhận hàng
                            @elseif($order->payment_method === 'bank_transfer')
                                <i class="fas fa-university text-blue-600 mr-1"></i> Chuyển khoản ngân hàng
                            @elseif($order->payment_method === 'e_wallet')
                                <i class="fas fa-wallet text-purple-600 mr-1"></i> Ví điện tử
                            @else
                                {{ $order->payment_method }}
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Trạng thái</p>
                        @if($order->payment_status === 'unpaid')
                            <span class="px-3 py-1 text-xs font-semibold text-orange-600 bg-orange-100 rounded-full">Chưa thanh toán</span>
                        @elseif($order->payment_status === 'paid')
                            <span class="px-3 py-1 text-xs font-semibold text-green-600 bg-green-100 rounded-full">
                                <i class="fas fa-check mr-1"></i> Đã thanh toán
                            </span>
                        @elseif($order->payment_status === 'refunded')
                            <span class="px-3 py-1 text-xs font-semibold text-gray-600 bg-gray-100 rounded-full">Đã hoàn tiền</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Order Summary --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">
                    <i class="fas fa-calculator mr-2 text-red-600"></i>Tổng kết đơn hàng
                </h2>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Tạm tính:</span>
                        <span class="font-semibold">{{ number_format($order->subtotal, 0, ',', '.') }}₫</span>
                    </div>
                    @if($order->discount_amount > 0)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Giảm giá:</span>
                            <span class="font-semibold text-green-600">-{{ number_format($order->discount_amount, 0, ',', '.') }}₫</span>
                        </div>
                    @endif
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Phí vận chuyển:</span>
                        <span class="font-semibold">
                            @if($order->shipping_fee > 0)
                                {{ number_format($order->shipping_fee, 0, ',', '.') }}₫
                            @else
                                <span class="text-green-600">Miễn phí</span>
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between text-lg font-bold pt-3 border-t border-gray-200">
                        <span>Tổng cộng:</span>
                        <span class="text-red-600">{{ number_format($order->total, 0, ',', '.') }}₫</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection