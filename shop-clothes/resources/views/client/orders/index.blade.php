@extends('layouts.app')

@section('title', 'Đơn hàng của tôi')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">
                <svg class="w-7 h-7 inline mr-2 text-red-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                Đơn hàng của tôi
            </h1>
            <p class="text-gray-500 mt-1 text-sm">Theo dõi trạng thái đơn hàng của bạn</p>
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
                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35" stroke-linecap="round"/></svg>
                Tìm
            </button>
            @if(request('search'))
                <a href="{{ route('client.orders.index', request('status') ? ['status' => request('status')] : []) }}"
                   class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 text-sm font-semibold">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    Xóa
                </a>
            @endif
        </form>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
            {{ session('error') }}
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
                                <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Đã thanh toán
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
                        &middot; <svg class="w-3.5 h-3.5 inline text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25"/></svg> COD
                    @elseif($order->payment_method === 'bank_transfer')
                        &middot; <svg class="w-3.5 h-3.5 inline text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18"/></svg> Chuyển khoản
                    @elseif($order->payment_method === 'e_wallet')
                        &middot; <svg class="w-3.5 h-3.5 inline text-purple-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a2.25 2.25 0 00-2.25-2.25H15a3 3 0 11-6 0H5.25A2.25 2.25 0 003 12m18 0v6a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18v-6m18 0V9"/></svg> Ví điện tử
                    @endif
                </p>
                <div class="flex gap-2">
                    <a href="{{ route('client.orders.show', $order) }}"
                       class="px-4 py-2 text-sm font-semibold text-red-600 border border-red-600 rounded-lg hover:bg-red-50 transition-colors">
                        Xem chi tiết
                    </a>
                    @if($order->status === 'pending')
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
            <svg class="w-14 h-14 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Chưa có đơn hàng nào</h3>
            <p class="text-gray-500 mb-6">Bạn chưa đặt đơn hàng nào. Hãy bắt đầu mua sắm ngay!</p>
            <a href="{{ route('shop.index') }}"
               class="inline-flex items-center px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 font-semibold transition-colors">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12"/></svg>
                Mua sắm ngay
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
