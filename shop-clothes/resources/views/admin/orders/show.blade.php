@extends('layouts.admin')

@section('title', 'Đơn hàng #' . $order->order_code . ' - Admin')
@section('page-title', 'Chi tiết đơn hàng')

@section('content')
<div class="space-y-5">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.orders.index') }}"
               class="w-10 h-10 flex items-center justify-center rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-600 transition-colors">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="text-xl font-bold text-gray-900">#{{ $order->order_code }}</h2>
                <p class="text-xs text-gray-400 mt-0.5">Đặt lúc {{ $order->created_at->format('d/m/Y H:i') }}</p>
            </div>
            {{-- Status Badge --}}
            @switch($order->status)
                @case('pending')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-blue-700 bg-blue-50 rounded-xl">
                        <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span> Chờ xử lý
                    </span>
                    @break
                @case('confirmed')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-sky-700 bg-sky-50 rounded-xl">
                        <span class="w-2 h-2 rounded-full bg-sky-500"></span> Đã xác nhận
                    </span>
                    @break
                @case('processing')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-amber-700 bg-amber-50 rounded-xl">
                        <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span> Đang xử lý
                    </span>
                    @break
                @case('shipped')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-violet-700 bg-violet-50 rounded-xl">
                        <span class="w-2 h-2 rounded-full bg-violet-500 animate-pulse"></span> Đang giao
                    </span>
                    @break
                @case('delivered')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-green-700 bg-green-50 rounded-xl">
                        <span class="w-2 h-2 rounded-full bg-green-500"></span> Đã giao
                    </span>
                    @break
                @case('cancelled')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-red-700 bg-red-50 rounded-xl">
                        <span class="w-2 h-2 rounded-full bg-red-500"></span> Đã hủy
                    </span>
                    @break
                @case('refunded')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-gray-700 bg-gray-100 rounded-xl">
                        <span class="w-2 h-2 rounded-full bg-gray-400"></span> Hoàn tiền
                    </span>
                    @break
            @endswitch
        </div>
        <a href="{{ route('admin.orders.print', $order) }}" target="_blank"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-900 text-white rounded-xl hover:bg-black text-sm font-semibold shadow-sm transition-all">
            <i class="fas fa-print"></i> In hóa đơn
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        {{-- LEFT COLUMN --}}
        <div class="lg:col-span-2 space-y-5">
            {{-- Order Items --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-base font-bold text-gray-900">
                        <i class="fas fa-box-open mr-2 text-red-500"></i>Sản phẩm trong đơn
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50/80">
                                <th class="text-left py-3 px-5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Sản phẩm</th>
                                <th class="text-left py-3 px-5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Phân loại</th>
                                <th class="text-right py-3 px-5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Đơn giá</th>
                                <th class="text-center py-3 px-5 text-xs font-semibold text-gray-500 uppercase tracking-wider">SL</th>
                                <th class="text-right py-3 px-5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($order->items as $item)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="py-3 px-5">
                                        <div class="flex items-center gap-3">
                                            @php
                                                $variant = $item->productVariant;
                                                $product = $variant?->product;
                                                $imageUrl = $product?->image_url ?? asset('images/product-placeholder.svg');
                                            @endphp
                                            <img src="{{ $imageUrl }}" alt="{{ $item->product_name }}"
                                                 class="w-12 h-12 object-cover rounded-xl border border-gray-100">
                                            <div>
                                                <p class="text-sm font-semibold text-gray-900">{{ $item->product_name }}</p>
                                                <p class="text-xs text-gray-400 mt-0.5">SKU: {{ $product->sku ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-5">
                                        <div class="flex flex-wrap gap-1">
                                            @if($item->size)
                                                <span class="inline-block bg-gray-100 px-2 py-0.5 rounded-md text-xs text-gray-600">{{ $item->size }}</span>
                                            @endif
                                            @if($item->color)
                                                <span class="inline-block bg-gray-100 px-2 py-0.5 rounded-md text-xs text-gray-600">{{ $item->color }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-3 px-5 text-right text-sm text-gray-600">
                                        {{ number_format($item->price, 0, ',', '.') }}₫
                                    </td>
                                    <td class="py-3 px-5 text-center">
                                        <span class="inline-flex items-center justify-center w-8 h-8 bg-gray-100 rounded-lg text-sm font-semibold text-gray-700">
                                            {{ $item->quantity }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-5 text-right text-sm font-bold text-gray-900">
                                        {{ number_format($item->subtotal, 0, ',', '.') }}₫
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Order Summary --}}
                <div class="px-6 py-4 bg-gray-50/80 border-t border-gray-100">
                    <div class="flex justify-end">
                        <div class="w-80 space-y-2.5">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Tạm tính:</span>
                                <span class="font-semibold text-gray-700">{{ number_format($order->subtotal, 0, ',', '.') }}₫</span>
                            </div>
                            @if($order->discount_amount > 0)
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Giảm giá:</span>
                                    <span class="font-semibold text-green-600">-{{ number_format($order->discount_amount, 0, ',', '.') }}₫</span>
                                </div>
                            @endif
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Phí vận chuyển:</span>
                                @if($order->shipping_fee > 0)
                                    <span class="font-semibold text-gray-700">{{ number_format($order->shipping_fee, 0, ',', '.') }}₫</span>
                                @else
                                    <span class="font-semibold text-green-600">Miễn phí</span>
                                @endif
                            </div>
                            <div class="flex justify-between text-lg font-extrabold pt-3 border-t border-gray-200">
                                <span class="text-gray-900">Tổng cộng:</span>
                                <span class="text-red-600">{{ number_format($order->total, 0, ',', '.') }}₫</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Customer Info --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-base font-bold text-gray-900 mb-4">
                    <i class="fas fa-user-circle mr-2 text-red-500"></i>Thông tin khách hàng
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                            <i class="fas fa-user text-blue-500 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-[11px] text-gray-400 uppercase tracking-wider">Họ tên</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $order->user->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                        <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center">
                            <i class="fas fa-envelope text-purple-500 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-[11px] text-gray-400 uppercase tracking-wider">Email</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $order->user->email ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                        <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center">
                            <i class="fas fa-phone text-green-500 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-[11px] text-gray-400 uppercase tracking-wider">Điện thoại</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $order->user->phone ?? ($order->shipping_address['phone'] ?? 'N/A') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                        <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center">
                            <i class="fas fa-calendar text-amber-500 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-[11px] text-gray-400 uppercase tracking-wider">Ngày đặt</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $order->created_at->format('d/m/Y H:i:s') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Shipping Address --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-base font-bold text-gray-900 mb-4">
                    <i class="fas fa-truck mr-2 text-red-500"></i>Địa chỉ giao hàng
                </h3>
                @if($order->shipping_address)
                    <div class="bg-gray-50 rounded-xl p-4 space-y-2">
                        @if(isset($order->shipping_address['recipient_name']))
                            <p class="text-sm">
                                <i class="fas fa-user w-5 text-gray-400"></i>
                                <span class="font-semibold text-gray-900 ml-2">{{ $order->shipping_address['recipient_name'] }}</span>
                            </p>
                        @endif
                        @if(isset($order->shipping_address['phone']))
                            <p class="text-sm">
                                <i class="fas fa-phone w-5 text-gray-400"></i>
                                <span class="text-gray-700 ml-2">{{ $order->shipping_address['phone'] }}</span>
                            </p>
                        @endif
                        @if(isset($order->shipping_address['address']))
                            <p class="text-sm">
                                <i class="fas fa-map-marker-alt w-5 text-gray-400"></i>
                                <span class="text-gray-700 ml-2">{{ $order->shipping_address['address'] }}</span>
                            </p>
                        @endif
                    </div>
                @else
                    <p class="text-sm text-gray-400 italic">Không có thông tin địa chỉ</p>
                @endif
            </div>

            {{-- Order Notes --}}
            @if($order->notes)
                <div class="bg-amber-50 rounded-2xl border border-amber-200 p-5">
                    <h3 class="text-sm font-bold text-amber-800 mb-2">
                        <i class="fas fa-sticky-note mr-2"></i>Ghi chú đơn hàng
                    </h3>
                    <p class="text-sm text-amber-700">{{ $order->notes }}</p>
                </div>
            @endif
        </div>

        {{-- RIGHT COLUMN --}}
        <div class="space-y-5">
            {{-- Status Update --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <h3 class="text-base font-bold text-gray-900 mb-4">
                    <i class="fas fa-cog mr-2 text-red-500"></i>Cập nhật trạng thái
                </h3>

                @php
                    $allowedTransitions = match($order->status) {
                        'pending' => ['confirmed' => ['label' => 'Xác nhận đơn', 'icon' => 'fa-check', 'color' => 'bg-sky-600 hover:bg-sky-700'], 'cancelled' => ['label' => 'Hủy đơn', 'icon' => 'fa-times', 'color' => 'bg-red-600 hover:bg-red-700']],
                        'confirmed' => ['processing' => ['label' => 'Bắt đầu xử lý', 'icon' => 'fa-cog', 'color' => 'bg-amber-600 hover:bg-amber-700'], 'cancelled' => ['label' => 'Hủy đơn', 'icon' => 'fa-times', 'color' => 'bg-red-600 hover:bg-red-700']],
                        'processing' => ['shipped' => ['label' => 'Giao vận chuyển', 'icon' => 'fa-truck', 'color' => 'bg-violet-600 hover:bg-violet-700'], 'cancelled' => ['label' => 'Hủy đơn', 'icon' => 'fa-times', 'color' => 'bg-red-600 hover:bg-red-700']],
                        'shipped' => ['delivered' => ['label' => 'Xác nhận đã giao', 'icon' => 'fa-check-circle', 'color' => 'bg-green-600 hover:bg-green-700']],
                        'delivered' => ['refunded' => ['label' => 'Hoàn tiền', 'icon' => 'fa-undo', 'color' => 'bg-gray-600 hover:bg-gray-700']],
                        default => [],
                    };
                @endphp

                @if(!empty($allowedTransitions))
                    <form method="POST" action="{{ route('admin.orders.update-status', $order) }}">
                        @csrf
                        @method('PATCH')
                        <div class="space-y-2">
                            @foreach($allowedTransitions as $status => $meta)
                                <button type="submit" name="status" value="{{ $status }}"
                                        onclick="return confirm('Bạn có chắc chắn muốn {{ strtolower($meta['label']) }}?')"
                                        class="w-full flex items-center justify-center gap-2 px-4 py-3 {{ $meta['color'] }} text-white rounded-xl text-sm font-semibold shadow-sm transition-all">
                                    <i class="fas {{ $meta['icon'] }} text-xs"></i>
                                    {{ $meta['label'] }}
                                </button>
                            @endforeach
                        </div>
                    </form>
                @else
                    <div class="bg-gray-50 rounded-xl p-4 text-center">
                        <i class="fas fa-lock text-gray-300 text-xl mb-2"></i>
                        <p class="text-sm text-gray-400">Không có thao tác khả dụng</p>
                    </div>
                @endif
            </div>

            {{-- Payment Status --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <h3 class="text-base font-bold text-gray-900 mb-4">
                    <i class="fas fa-credit-card mr-2 text-red-500"></i>Thanh toán
                </h3>

                <div class="space-y-3 mb-4">
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                        <span class="text-xs text-gray-500 uppercase tracking-wider">Phương thức</span>
                        <span class="text-sm font-semibold text-gray-900">
                            @if($order->payment_method === 'cod')
                                <i class="fas fa-money-bill-wave mr-1 text-green-600"></i> COD
                            @elseif($order->payment_method === 'bank_transfer')
                                <i class="fas fa-university mr-1 text-blue-600"></i> Chuyển khoản
                            @elseif($order->payment_method === 'e_wallet')
                                <i class="fas fa-wallet mr-1 text-purple-600"></i> Ví điện tử
                            @else
                                {{ $order->payment_method }}
                            @endif
                        </span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                        <span class="text-xs text-gray-500 uppercase tracking-wider">Trạng thái</span>
                        @if($order->payment_status === 'unpaid')
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold text-orange-700 bg-orange-100 rounded-lg">
                                <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span> Chưa thanh toán
                            </span>
                        @elseif($order->payment_status === 'paid')
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-lg">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Đã thanh toán
                            </span>
                        @elseif($order->payment_status === 'refunded')
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold text-gray-700 bg-gray-100 rounded-lg">
                                <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Hoàn tiền
                            </span>
                        @endif
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.orders.update-payment-status', $order) }}">
                    @csrf
                    @method('PATCH')
                    <select name="payment_status"
                            class="w-full rounded-xl border-gray-200 shadow-sm focus:border-red-400 focus:ring-2 focus:ring-red-500/20 text-sm mb-3">
                        <option value="unpaid" {{ $order->payment_status === 'unpaid' ? 'selected' : '' }}>Chưa thanh toán</option>
                        <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                        <option value="refunded" {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>Đã hoàn tiền</option>
                    </select>
                    <button type="submit"
                            class="w-full px-4 py-2.5 bg-red-600 text-white rounded-xl hover:bg-red-700 text-sm font-semibold shadow-sm transition-all">
                        Cập nhật thanh toán
                    </button>
                </form>
            </div>

            {{-- Order Info --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <h3 class="text-base font-bold text-gray-900 mb-4">
                    <i class="fas fa-info-circle mr-2 text-red-500"></i>Thông tin đơn hàng
                </h3>
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 bg-blue-50 rounded-xl flex items-center justify-center">
                            <i class="fas fa-hashtag text-blue-500 text-xs"></i>
                        </div>
                        <div>
                            <p class="text-[11px] text-gray-400 uppercase tracking-wider">Mã đơn</p>
                            <p class="text-sm font-bold text-gray-900">{{ $order->order_code }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 bg-green-50 rounded-xl flex items-center justify-center">
                            <i class="fas fa-calendar-plus text-green-500 text-xs"></i>
                        </div>
                        <div>
                            <p class="text-[11px] text-gray-400 uppercase tracking-wider">Tạo lúc</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $order->created_at->format('d/m/Y H:i:s') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 bg-amber-50 rounded-xl flex items-center justify-center">
                            <i class="fas fa-sync-alt text-amber-500 text-xs"></i>
                        </div>
                        <div>
                            <p class="text-[11px] text-gray-400 uppercase tracking-wider">Cập nhật</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $order->updated_at->format('d/m/Y H:i:s') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection