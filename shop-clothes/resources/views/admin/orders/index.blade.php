@extends('layouts.admin')

@section('page-title', 'Quản lý đơn hàng')

@section('content')
<div class="space-y-6">
    {{-- Status Filter Tabs --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.orders.index') }}"
               class="px-4 py-2 rounded-full text-sm font-semibold transition-colors {{ !request('status') ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Tất cả ({{ $stats['total'] }})
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}"
               class="px-4 py-2 rounded-full text-sm font-semibold transition-colors {{ request('status') === 'pending' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Chờ xử lý ({{ $stats['pending'] }})
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'confirmed']) }}"
               class="px-4 py-2 rounded-full text-sm font-semibold transition-colors {{ request('status') === 'confirmed' ? 'bg-sky-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Đã xác nhận ({{ $stats['confirmed'] }})
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}"
               class="px-4 py-2 rounded-full text-sm font-semibold transition-colors {{ request('status') === 'processing' ? 'bg-amber-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Đang xử lý ({{ $stats['processing'] }})
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'shipped']) }}"
               class="px-4 py-2 rounded-full text-sm font-semibold transition-colors {{ request('status') === 'shipped' ? 'bg-purple-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Đang giao ({{ $stats['shipped'] }})
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'delivered']) }}"
               class="px-4 py-2 rounded-full text-sm font-semibold transition-colors {{ request('status') === 'delivered' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Đã giao ({{ $stats['delivered'] }})
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}"
               class="px-4 py-2 rounded-full text-sm font-semibold transition-colors {{ request('status') === 'cancelled' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Đã hủy ({{ $stats['cancelled'] }})
            </a>
        </div>
    </div>

    {{-- Search & Filters --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="flex flex-wrap gap-4 items-end">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif

            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Tìm kiếm</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Mã đơn, tên khách hàng, email..."
                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Từ ngày</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                       class="rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Đến ngày</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                       class="rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Thanh toán</label>
                <select name="payment_status" class="rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm">
                    <option value="">Tất cả</option>
                    <option value="unpaid" {{ request('payment_status') === 'unpaid' ? 'selected' : '' }}>Chưa thanh toán</option>
                    <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                    <option value="refunded" {{ request('payment_status') === 'refunded' ? 'selected' : '' }}>Đã hoàn tiền</option>
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm font-semibold">
                    <i class="fas fa-search mr-1"></i> Tìm
                </button>
                <a href="{{ route('admin.orders.index') }}"
                   class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 text-sm font-semibold">
                    <i class="fas fa-redo mr-1"></i> Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Orders Table --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Mã đơn</th>
                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Khách hàng</th>
                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Tổng tiền</th>
                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Thanh toán</th>
                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Trạng thái</th>
                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Ngày đặt</th>
                        <th class="text-right py-3 px-4 text-sm font-semibold text-gray-700">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                            <td class="py-3 px-4">
                                <a href="{{ route('admin.orders.show', $order) }}"
                                   class="text-sm font-bold text-red-600 hover:underline">
                                    {{ $order->order_code }}
                                </a>
                            </td>
                            <td class="py-3 px-4">
                                <p class="text-sm font-semibold text-gray-900">{{ $order->user->name ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-500">{{ $order->user->email ?? '' }}</p>
                            </td>
                            <td class="py-3 px-4">
                                <span class="text-sm font-bold text-gray-900">{{ number_format($order->total, 0, ',', '.') }}₫</span>
                            </td>
                            <td class="py-3 px-4">
                                @if($order->payment_status === 'unpaid')
                                    <span class="px-2 py-1 text-xs font-semibold text-orange-600 bg-orange-100 rounded-full">
                                        <i class="fas fa-clock mr-1"></i> Chưa thanh toán
                                    </span>
                                @elseif($order->payment_status === 'paid')
                                    <span class="px-2 py-1 text-xs font-semibold text-green-600 bg-green-100 rounded-full">
                                        <i class="fas fa-check mr-1"></i> Đã thanh toán
                                    </span>
                                @elseif($order->payment_status === 'refunded')
                                    <span class="px-2 py-1 text-xs font-semibold text-gray-600 bg-gray-100 rounded-full">
                                        <i class="fas fa-undo mr-1"></i> Đã hoàn tiền
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                @if($order->status === 'pending')
                                    <span class="px-2 py-1 text-xs font-semibold text-blue-600 bg-blue-100 rounded-full">Chờ xử lý</span>
                                @elseif($order->status === 'confirmed')
                                    <span class="px-2 py-1 text-xs font-semibold text-sky-600 bg-sky-100 rounded-full">Đã xác nhận</span>
                                @elseif($order->status === 'processing')
                                    <span class="px-2 py-1 text-xs font-semibold text-amber-600 bg-amber-100 rounded-full">Đang xử lý</span>
                                @elseif($order->status === 'shipped')
                                    <span class="px-2 py-1 text-xs font-semibold text-purple-600 bg-purple-100 rounded-full">Đang giao</span>
                                @elseif($order->status === 'delivered')
                                    <span class="px-2 py-1 text-xs font-semibold text-green-600 bg-green-100 rounded-full">Đã giao</span>
                                @elseif($order->status === 'cancelled')
                                    <span class="px-2 py-1 text-xs font-semibold text-red-600 bg-red-100 rounded-full">Đã hủy</span>
                                @elseif($order->status === 'refunded')
                                    <span class="px-2 py-1 text-xs font-semibold text-gray-600 bg-gray-100 rounded-full">Hoàn tiền</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-sm text-gray-600">
                                {{ $order->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="py-3 px-4 text-right">
                                <a href="{{ route('admin.orders.show', $order) }}"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-semibold text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                    <i class="fas fa-eye"></i> Chi tiết
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center">
                                <i class="fas fa-shopping-bag text-4xl text-gray-300 mb-4"></i>
                                <p class="text-gray-500 mt-2">Không tìm thấy đơn hàng nào</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($orders->hasPages())
            <div class="px-4 py-3 border-t border-gray-200">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection