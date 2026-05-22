@extends('layouts.admin')

@section('page-title', 'Chi tiết đơn hàng ' . $order->order_code)

@section('content')
<div class="space-y-6">
    {{-- Back Button & Actions --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.orders.index') }}"
           class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 font-semibold">
            <i class="fas fa-arrow-left"></i> Quay lại danh sách
        </a>
        <div class="flex gap-2">
            <a href="{{ route('admin.orders.print', $order) }}" target="_blank"
               class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 text-sm font-semibold">
                <i class="fas fa-print mr-1"></i> In hóa đơn
            </a>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left Column: Order Info --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Order Items --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900">
                        <i class="fas fa-box mr-2 text-red-600"></i>Sản phẩm trong đơn
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200">
                                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Sản phẩm</th>
                                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Phân loại</th>
                                <th class="text-right py-3 px-4 text-sm font-semibold text-gray-700">Đơn giá</th>
                                <th class="text-center py-3 px-4 text-sm font-semibold text-gray-700">SL</th>
                                <th class="text-right py-3 px-4 text-sm font-semibold text-gray-700">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr class="border-b border-gray-100">
                                    <td class="py-3 px-4">
                                        <div class="flex items-center gap-3">
                                            @php
                                                $variant = $item->productVariant;
                                                $product = $variant?->product;
                                                $imageUrl = $product?->image_url ?? asset('images/product-placeholder.svg');
                                            @endphp
                                            <img src="{{ $imageUrl }}" alt="{{ $item->product_name }}"
                                                 class="w-12 h-12 object-cover rounded">
                                            <div>
                                                <p class="text-sm font-semibold text-gray-900">{{ $item->product_name }}</p>
                                                <p class="text-xs text-gray-500">SKU: {{ $product->sku ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="text-sm text-gray-700">
                                            @if($item->size)
                                                <span class="inline-block bg-gray-100 px-2 py-0.5 rounded text-xs mr-1">{{ $item->size }}</span>
                                            @endif
                                            @if($item->color)
                                                <span class="inline-block bg-gray-100 px-2 py-0.5 rounded text-xs">{{ $item->color }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 text-right text-sm text-gray-700">
                                        {{ number_format($item->price, 0, ',', '.') }}₫
                                    </td>
                                    <td class="py-3 px-4 text-center text-sm text-gray-700">
                                        {{ $item->quantity }}
                                    </td>
                                    <td class="py-3 px-4 text-right text-sm font-bold text-gray-900">
                                        {{ number_format($item->subtotal, 0, ',', '.') }}₫
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Order Summary --}}
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <div class="flex justify-end">
                        <div class="w-72 space-y-2">
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
                            <div class="flex justify-between text-lg font-bold pt-2 border-t border-gray-300">
                                <span>Tổng cộng:</span>
                                <span class="text-red-600">{{ number_format($order->total, 0, ',', '.') }}₫</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Customer Info --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">
                    <i class="fas fa-user mr-2 text-red-600"></i>Thông tin khách hàng
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Họ tên</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $order->user->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $order->user->email ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Điện thoại</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $order->user->phone ?? ($order->shipping_address['phone'] ?? 'N/A') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Ngày đặt</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $order->created_at->format('d/m/Y H:i:s') }}</p>
                    </div>
                </div>
            </div>

            {{-- Shipping Address --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">
                    <i class="fas fa-shipping-fast mr-2 text-red-600"></i>Địa chỉ giao hàng
                </h3>
                @if($order->shipping_address)
                    <div class="space-y-2">
                        @if(isset($order->shipping_address['recipient_name']))
                            <p class="text-sm">
                                <span class="text-gray-500">Người nhận:</span>
                                <span class="font-semibold text-gray-900">{{ $order->shipping_address['recipient_name'] }}</span>
                            </p>
                        @endif
                        @if(isset($order->shipping_address['phone']))
                            <p class="text-sm">
                                <span class="text-gray-500">Điện thoại:</span>
                                <span class="font-semibold text-gray-900">{{ $order->shipping_address['phone'] }}</span>
                            </p>
                        @endif
                        @if(isset($order->shipping_address['address']))
                            <p class="text-sm">
                                <span class="text-gray-500">Địa chỉ:</span>
                                <span class="font-semibold text-gray-900">{{ $order->shipping_address['address'] }}</span>
                            </p>
                        @endif
                    </div>
                @else
                    <p class="text-sm text-gray-500">Không có thông tin địa chỉ</p>
                @endif
            </div>

            {{-- Order Notes --}}
            @if($order->notes)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">
                        <i class="fas fa-sticky-note mr-2 text-red-600"></i>Ghi chú đơn hàng
                    </h3>
                    <p class="text-sm text-gray-700">{{ $order->notes }}</p>
                </div>
            @endif
        </div>

        {{-- Right Column: Status Management --}}
        <div class="space-y-6">
            {{-- Order Status Update --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">
                    <i class="fas fa-cog mr-2 text-red-600"></i>Cập nhật trạng thái
                </h3>

                {{-- Current Status --}}
                <div class="mb-4">
                    <p class="text-sm text-gray-500 mb-1">Trạng thái hiện tại:</p>
                    @if($order->status === 'pending')
                        <span class="px-3 py-1 text-sm font-semibold text-blue-600 bg-blue-100 rounded-full">Chờ xử lý</span>
                    @elseif($order->status === 'confirmed')
                        <span class="px-3 py-1 text-sm font-semibold text-sky-600 bg-sky-100 rounded-full">Đã xác nhận</span>
                    @elseif($order->status === 'processing')
                        <span class="px-3 py-1 text-sm font-semibold text-amber-600 bg-amber-100 rounded-full">Đang xử lý</span>
                    @elseif($order->status === 'shipped')
                        <span class="px-3 py-1 text-sm font-semibold text-purple-600 bg-purple-100 rounded-full">Đang giao</span>
                    @elseif($order->status === 'delivered')
                        <span class="px-3 py-1 text-sm font-semibold text-green-600 bg-green-100 rounded-full">Đã giao</span>
                    @elseif($order->status === 'cancelled')
                        <span class="px-3 py-1 text-sm font-semibold text-red-600 bg-red-100 rounded-full">Đã hủy</span>
                    @elseif($order->status === 'refunded')
                        <span class="px-3 py-1 text-sm font-semibold text-gray-600 bg-gray-100 rounded-full">Hoàn tiền</span>
                    @endif
                </div>

                {{-- Status Update Form --}}
                @php
                    $allowedTransitions = match($order->status) {
                        'pending' => ['confirmed' => 'Xác nhận', 'cancelled' => 'Hủy đơn'],
                        'confirmed' => ['processing' => 'Bắt đầu xử lý', 'cancelled' => 'Hủy đơn'],
                        'processing' => ['shipped' => 'Giao cho vận chuyển', 'cancelled' => 'Hủy đơn'],
                        'shipped' => ['delivered' => 'Xác nhận đã giao'],
                        'delivered' => ['refunded' => 'Hoàn tiền'],
                        default => [],
                    };
                @endphp

                @if(!empty($allowedTransitions))
                    <form method="POST" action="{{ route('admin.orders.update-status', $order) }}">
                        @csrf
                        @method('PATCH')
                        <div class="space-y-2">
                            @foreach($allowedTransitions as $status => $label)
                                @php
                                    $btnClass = match($status) {
                                        'confirmed' => 'bg-sky-600 hover:bg-sky-700',
                                        'processing' => 'bg-amber-600 hover:bg-amber-700',
                                        'shipped' => 'bg-purple-600 hover:bg-purple-700',
                                        'delivered' => 'bg-green-600 hover:bg-green-700',
                                        'cancelled' => 'bg-red-600 hover:bg-red-700',
                                        'refunded' => 'bg-gray-600 hover:bg-gray-700',
                                        default => 'bg-gray-600 hover:bg-gray-700',
                                    };
                                @endphp
                                <button type="submit" name="status" value="{{ $status }}"
                                        onclick="return confirm('Bạn có chắc chắn muốn {{ strtolower($label) }} đơn hàng này?')"
                                        class="w-full px-4 py-2 {{ $btnClass }} text-white rounded-lg text-sm font-semibold transition-colors">
                                    {{ $label }}
                                </button>
                            @endforeach
                        </div>
                    </form>
                @else
                    <p class="text-sm text-gray-500 italic">Không có thao tác khả dụng cho trạng thái hiện tại.</p>
                @endif
            </div>

            {{-- Payment Status Update --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">
                    <i class="fas fa-credit-card mr-2 text-red-600"></i>Trạng thái thanh toán
                </h3>

                <div class="mb-4">
                    <p class="text-sm text-gray-500 mb-1">Phương thức:</p>
                    <p class="text-sm font-semibold text-gray-900">
                        @if($order->payment_method === 'cod')
                            <i class="fas fa-money-bill-wave mr-1 text-green-600"></i> Thanh toán khi nhận hàng (COD)
                        @elseif($order->payment_method === 'bank_transfer')
                            <i class="fas fa-university mr-1 text-blue-600"></i> Chuyển khoản ngân hàng
                        @elseif($order->payment_method === 'e_wallet')
                            <i class="fas fa-wallet mr-1 text-purple-600"></i> Ví điện tử
                        @else
                            {{ $order->payment_method }}
                        @endif
                    </p>
                </div>

                <div class="mb-4">
                    <p class="text-sm text-gray-500 mb-1">Trạng thái thanh toán:</p>
                    @if($order->payment_status === 'unpaid')
                        <span class="px-3 py-1 text-sm font-semibold text-orange-600 bg-orange-100 rounded-full">Chưa thanh toán</span>
                    @elseif($order->payment_status === 'paid')
                        <span class="px-3 py-1 text-sm font-semibold text-green-600 bg-green-100 rounded-full">Đã thanh toán</span>
                    @elseif($order->payment_status === 'refunded')
                        <span class="px-3 py-1 text-sm font-semibold text-gray-600 bg-gray-100 rounded-full">Đã hoàn tiền</span>
                    @endif
                </div>

                <form method="POST" action="{{ route('admin.orders.update-payment-status', $order) }}">
                    @csrf
                    @method('PATCH')
                    <select name="payment_status"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm mb-3">
                        <option value="unpaid" {{ $order->payment_status === 'unpaid' ? 'selected' : '' }}>Chưa thanh toán</option>
                        <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                        <option value="refunded" {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>Đã hoàn tiền</option>
                    </select>
                    <button type="submit"
                            class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm font-semibold">
                        Cập nhật thanh toán
                    </button>
                </form>
            </div>

            {{-- Order Timeline --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">
                    <i class="fas fa-clock mr-2 text-red-600"></i>Thông tin đơn hàng
                </h3>
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-hashtag text-blue-600 text-xs"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Mã đơn hàng</p>
                            <p class="text-sm font-bold text-gray-900">{{ $order->order_code }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-calendar text-green-600 text-xs"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Ngày tạo</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $order->created_at->format('d/m/Y H:i:s') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-amber-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-sync text-amber-600 text-xs"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Cập nhật lần cuối</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $order->updated_at->format('d/m/Y H:i:s') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection