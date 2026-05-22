@extends('layouts.app')

@section('title', 'Đơn hàng của tôi')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">
            <i class="fas fa-shopping-bag mr-3 text-red-600"></i>Đơn hàng của tôi
        </h1>
        <p class="text-gray-500 mt-2">Theo dõi trạng thái đơn hàng của bạn</p>
    </div>

    {{-- Status Tabs --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('client.orders.index') }}"
               class="px-4 py-2 rounded-full text-sm font-semibold transition-colors {{ !request('status') ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Tất cả ({{ $counts['all'] }})
            </a>
            <a href="{{ route('client.orders.index', ['status' => 'pending']) }}"
               class="px-4 py-2 rounded-full text-sm font-semibold transition-colors {{ request('status') === 'pending' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Chờ xử lý ({{ $counts['pending'] }})
            </a>
            <a href="{{ route('client.orders.index', ['status' => 'confirmed']) }}"
               class="px-4 py-2 rounded-full text-sm font-semibold transition-colors {{ request('status') === 'confirmed' ? 'bg-sky-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Đã xác nhận ({{ $counts['confirmed'] }})
            </a>
            <a href="{{ route('client.orders.index', ['status' => 'processing']) }}"
               class="px-4 py-2 rounded-full text-sm font-semibold transition-colors {{ request('status') === 'processing' ? 'bg-amber-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Đang xử lý ({{ $counts['processing'] }})
            </a>
            <a href="{{ route('client.orders.index', ['status' => 'shipped']) }}"
               class="px-4 py-2 rounded-full text-sm font-semibold transition-colors {{ request('status') === 'shipped' ? 'bg-purple-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Đang giao ({{ $counts['shipped'] }})
            </a>
            <a href="{{ route('client.orders.index', ['status' => 'delivered']) }}"
               class="px-4 py-2 rounded-full text-sm font-semibold transition-colors {{ request('status') === 'delivered' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Đã giao ({{ $counts['delivered'] }})
            </a>
            <a href="{{ route('client.orders.index', ['status' => 'cancelled']) }}"
               class="px-4 py-2 rounded-full text-sm font-semibold transition-colors {{ request('status') === 'cancelled' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Đã hủy ({{ $counts['cancelled'] }})
            </a>
        </div>
    </div>

    {{-- Search --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
        <form method="GET" action="{{ route('client.orders.index') }}" class="flex gap-4">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Tìm theo mã đơn hàng..."
                   class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm">
            <button type="submit"
                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm font-semibold">
                <i class="fas fa-search mr-1"></i> Tìm
            </button>
            @if(request('search'))
                <a href="{{ route('client.orders.index', request('status') ? ['status' => request('status')] : []) }}"
                   class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 text-sm font-semibold">
                    <i class="fas fa-times mr-1"></i> Xóa
                </a>
            @endif
        </form>
    </div>

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

    {{-- Orders List --}}
    @forelse ($orders as $order)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-4 overflow-hidden">
            {{-- Order Header --}}
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div>
                        <p class="text-xs text-gray-500">Mã đơn hàng</p>
                        <p class="text-sm font-bold text-red-600">{{ $order->order_code }}</p>
                    </div>
                    <div class="h-8 w-px bg-gray-300"></div>
                    <div>
                        <p class="text-xs text-gray-500">Ngày đặt</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="h-8 w-px bg-gray-300"></div>
                    <div>
                        <p class="text-xs text-gray-500">Tổng tiền</p>
                        <p class="text-sm font-bold text-gray-900">{{ number_format($order->total, 0, ',', '.') }}₫</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    {{-- Status Badge --}}
                    @if($order->status === 'pending')
                        <span class="px-3 py-1 text-xs font-semibold text-blue-600 bg-blue-100 rounded-full">Chờ xử lý</span>
                    @elseif($order->status === 'confirmed')
                        <span class="px-3 py-1 text-xs font-semibold text-sky-600 bg-sky-100 rounded-full">Đã xác nhận</span>
                    @elseif($order->status === 'processing')
                        <span class="px-3 py-1 text-xs font-semibold text-amber-600 bg-amber-100 rounded-full">Đang xử lý</span>
                    @elseif($order->status === 'shipped')
                        <span class="px-3 py-1 text-xs font-semibold text-purple-600 bg-purple-100 rounded-full">Đang giao</span>
                    @elseif($order->status === 'delivered')
                        <span class="px-3 py-1 text-xs font-semibold text-green-600 bg-green-100 rounded-full">Đã giao</span>
                    @elseif($order->status === 'cancelled')
                        <span class="px-3 py-1 text-xs font-semibold text-red-600 bg-red-100 rounded-full">Đã hủy</span>
                    @elseif($order->status === 'refunded')
                        <span class="px-3 py-1 text-xs font-semibold text-gray-600 bg-gray-100 rounded-full">Hoàn tiền</span>
                    @endif

                    {{-- Payment Badge --}}
                    @if($order->payment_status === 'paid')
                        <span class="px-3 py-1 text-xs font-semibold text-green-600 bg-green-100 rounded-full">
                            <i class="fas fa-check mr-1"></i>Đã thanh toán
                        </span>
                    @elseif($order->payment_status === 'unpaid')
                        <span class="px-3 py-1 text-xs font-semibold text-orange-600 bg-orange-100 rounded-full">
                            Chưa thanh toán
                        </span>
                    @endif
                </div>
            </div>

            {{-- Order Items --}}
            <div class="px-6 py-4">
                @foreach($order->items->take(3) as $item)
                    @php
                        $variant = $item->productVariant;
                        $product = $variant?->product;
                        $imageUrl = $product?->image_url ?? asset('images/product-placeholder.svg');
                    @endphp
                    <div class="flex items-center gap-4 {{ !$loop->last ? 'pb-4 mb-4 border-b border-gray-100' : '' }}">
                        <img src="{{ $imageUrl }}" alt="{{ $item->product_name }}"
                             class="w-16 h-16 object-cover rounded-lg">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900 truncate">{{ $item->product_name }}</p>
                            <p class="text-xs text-gray-500 mt-1">
                                @if($item->size) {{ $item->size }} @endif
                                @if($item->size && $item->color) / @endif
                                @if($item->color) {{ $item->color }} @endif
                                &times; {{ $item->quantity }}
                            </p>
                        </div>
                        <p class="text-sm font-bold text-gray-900">{{ number_format($item->subtotal, 0, ',', '.') }}₫</p>
                    </div>
                @endforeach

                @if($order->items->count() > 3)
                    <p class="text-sm text-gray-500 mt-2 text-center">
                        và {{ $order->items->count() - 3 }} sản phẩm khác...
                    </p>
                @endif
            </div>

            {{-- Order Footer --}}
            <div class="px-6 py-3 bg-gray-50 border-t border-gray-200 flex items-center justify-between">
                <p class="text-sm text-gray-600">
                    {{ $order->items->count() }} sản phẩm
                    @if($order->payment_method === 'cod')
                        &middot; <i class="fas fa-money-bill-wave text-green-600"></i> COD
                    @elseif($order->payment_method === 'bank_transfer')
                        &middot; <i class="fas fa-university text-blue-600"></i> Chuyển khoản
                    @elseif($order->payment_method === 'e_wallet')
                        &middot; <i class="fas fa-wallet text-purple-600"></i> Ví điện tử
                    @endif
                </p>
                <div class="flex gap-2">
                    <a href="{{ route('client.orders.show', $order) }}"
                       class="px-4 py-2 text-sm font-semibold text-red-600 border border-red-600 rounded-lg hover:bg-red-50 transition-colors">
                        Xem chi tiết
                    </a>
                    @if(in_array($order->status, ['pending', 'confirmed']))
                        <form method="POST" action="{{ route('client.orders.cancel', $order) }}"
                              onsubmit="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')">
                            @csrf
                            <button type="submit"
                                    class="px-4 py-2 text-sm font-semibold text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors">
                                Hủy đơn
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
            <i class="fas fa-shopping-bag text-5xl text-gray-300 mb-4"></i>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Chưa có đơn hàng nào</h3>
            <p class="text-gray-500 mb-6">Bạn chưa đặt đơn hàng nào. Hãy bắt đầu mua sắm ngay!</p>
            <a href="{{ route('shop.index') }}"
               class="inline-flex items-center px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 font-semibold transition-colors">
                <i class="fas fa-shopping-bag mr-2"></i> Mua sắm ngay
            </a>
        </div>
    @endforelse

    {{-- Pagination --}}
    @if ($orders->hasPages())
        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection