@extends('layouts.admin')

@section('title', 'Quản lý đơn hàng - Admin')
@section('page-title', 'Quản lý đơn hàng')

@section('content')
<div class="space-y-5">
    {{-- Status Filter Tabs --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.orders.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold transition-all {{ !request('status') ? 'bg-gray-900 text-white shadow-md' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                <i class="fas fa-list text-xs"></i> Tất cả
                <span class="px-2 py-0.5 rounded-lg text-xs {{ !request('status') ? 'bg-white/20' : 'bg-gray-200' }}">{{ $stats['total'] }}</span>
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold transition-all {{ request('status') === 'pending' ? 'bg-blue-600 text-white shadow-md' : 'bg-blue-50 text-blue-700 hover:bg-blue-100' }}">
                <i class="fas fa-clock text-xs"></i> Chờ xử lý
                <span class="px-2 py-0.5 rounded-lg text-xs {{ request('status') === 'pending' ? 'bg-white/20' : 'bg-blue-100' }}">{{ $stats['pending'] }}</span>
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'confirmed']) }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold transition-all {{ request('status') === 'confirmed' ? 'bg-sky-600 text-white shadow-md' : 'bg-sky-50 text-sky-700 hover:bg-sky-100' }}">
                <i class="fas fa-check text-xs"></i> Đã xác nhận
                <span class="px-2 py-0.5 rounded-lg text-xs {{ request('status') === 'confirmed' ? 'bg-white/20' : 'bg-sky-100' }}">{{ $stats['confirmed'] }}</span>
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold transition-all {{ request('status') === 'processing' ? 'bg-amber-600 text-white shadow-md' : 'bg-amber-50 text-amber-700 hover:bg-amber-100' }}">
                <i class="fas fa-cog text-xs"></i> Đang xử lý
                <span class="px-2 py-0.5 rounded-lg text-xs {{ request('status') === 'processing' ? 'bg-white/20' : 'bg-amber-100' }}">{{ $stats['processing'] }}</span>
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'shipped']) }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold transition-all {{ request('status') === 'shipped' ? 'bg-violet-600 text-white shadow-md' : 'bg-violet-50 text-violet-700 hover:bg-violet-100' }}">
                <i class="fas fa-truck text-xs"></i> Đang giao
                <span class="px-2 py-0.5 rounded-lg text-xs {{ request('status') === 'shipped' ? 'bg-white/20' : 'bg-violet-100' }}">{{ $stats['shipped'] }}</span>
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'delivered']) }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold transition-all {{ request('status') === 'delivered' ? 'bg-green-600 text-white shadow-md' : 'bg-green-50 text-green-700 hover:bg-green-100' }}">
                <i class="fas fa-check-circle text-xs"></i> Đã giao
                <span class="px-2 py-0.5 rounded-lg text-xs {{ request('status') === 'delivered' ? 'bg-white/20' : 'bg-green-100' }}">{{ $stats['delivered'] }}</span>
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold transition-all {{ request('status') === 'cancelled' ? 'bg-red-600 text-white shadow-md' : 'bg-red-50 text-red-700 hover:bg-red-100' }}">
                <i class="fas fa-times-circle text-xs"></i> Đã hủy
                <span class="px-2 py-0.5 rounded-lg text-xs {{ request('status') === 'cancelled' ? 'bg-white/20' : 'bg-red-100' }}">{{ $stats['cancelled'] }}</span>
            </a>
        </div>
    </div>

    {{-- Search & Filters --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="flex flex-col lg:flex-row gap-3 items-end">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif

            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">Tìm kiếm</label>
                <div class="relative">
                    <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Mã đơn, tên khách hàng, email..."
                           class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-400 transition-all">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">Từ ngày</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                       class="border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-400 transition-all">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">Đến ngày</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                       class="border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-400 transition-all">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">Thanh toán</label>
                <select name="payment_status" class="border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-400">
                    <option value="">Tất cả</option>
                    <option value="unpaid" {{ request('payment_status') === 'unpaid' ? 'selected' : '' }}>Chưa thanh toán</option>
                    <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                    <option value="refunded" {{ request('payment_status') === 'refunded' ? 'selected' : '' }}>Đã hoàn tiền</option>
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit"
                        class="px-5 py-2.5 bg-red-600 text-white rounded-xl hover:bg-red-700 text-sm font-semibold shadow-sm transition-all">
                    <i class="fas fa-search mr-1"></i> Tìm
                </button>
                <a href="{{ route('admin.orders.index') }}"
                   class="px-4 py-2.5 bg-gray-100 text-gray-600 rounded-xl hover:bg-gray-200 text-sm font-semibold transition-all">
                    <i class="fas fa-redo"></i>
                </a>
            </div>
        </form>
    </div>

    {{-- Orders Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50/80">
                        <th class="text-left py-3 px-5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Mã đơn</th>
                        <th class="text-left py-3 px-5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Khách hàng</th>
                        <th class="text-left py-3 px-5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tổng tiền</th>
                        <th class="text-left py-3 px-5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Thanh toán</th>
                        <th class="text-left py-3 px-5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Trạng thái</th>
                        <th class="text-left py-3 px-5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Ngày đặt</th>
                        <th class="text-right py-3 px-5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($orders as $order)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="py-3.5 px-5">
                                <a href="{{ route('admin.orders.show', $order) }}"
                                   class="text-sm font-bold text-red-600 hover:text-red-700 hover:underline">
                                    #{{ $order->order_code }}
                                </a>
                            </td>
                            <td class="py-3.5 px-5">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-9 h-9 rounded-xl bg-gray-100 flex items-center justify-center text-xs font-bold text-gray-500">
                                        {{ strtoupper(mb_substr($order->user->name ?? 'N', 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">{{ $order->user->name ?? 'N/A' }}</p>
                                        <p class="text-xs text-gray-400">{{ $order->user->email ?? '' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-5">
                                <span class="text-sm font-bold text-gray-900">{{ number_format($order->total, 0, ',', '.') }}₫</span>
                            </td>
                            <td class="py-3.5 px-5">
                                @if($order->payment_status === 'unpaid')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold text-orange-700 bg-orange-50 rounded-lg">
                                        <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span> Chưa thanh toán
                                    </span>
                                @elseif($order->payment_status === 'paid')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold text-green-700 bg-green-50 rounded-lg">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Đã thanh toán
                                    </span>
                                @elseif($order->payment_status === 'refunded')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold text-gray-700 bg-gray-100 rounded-lg">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Đã hoàn tiền
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-5">
                                @switch($order->status)
                                    @case('pending')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold text-blue-700 bg-blue-50 rounded-lg">
                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Chờ xử lý
                                        </span>
                                        @break
                                    @case('confirmed')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold text-sky-700 bg-sky-50 rounded-lg">
                                            <span class="w-1.5 h-1.5 rounded-full bg-sky-500"></span> Đã xác nhận
                                        </span>
                                        @break
                                    @case('processing')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold text-amber-700 bg-amber-50 rounded-lg">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Đang xử lý
                                        </span>
                                        @break
                                    @case('shipped')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold text-violet-700 bg-violet-50 rounded-lg">
                                            <span class="w-1.5 h-1.5 rounded-full bg-violet-500"></span> Đang giao
                                        </span>
                                        @break
                                    @case('delivered')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold text-green-700 bg-green-50 rounded-lg">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Đã giao
                                        </span>
                                        @break
                                    @case('cancelled')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold text-red-700 bg-red-50 rounded-lg">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Đã hủy
                                        </span>
                                        @break
                                    @case('refunded')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold text-gray-700 bg-gray-100 rounded-lg">
                                            <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Hoàn tiền
                                        </span>
                                        @break
                                    @default
                                        <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold text-gray-600 bg-gray-100 rounded-lg">{{ ucfirst($order->status) }}</span>
                                @endswitch
                            </td>
                            <td class="py-3.5 px-5 text-xs text-gray-500">
                                {{ $order->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="py-3.5 px-5 text-right">
                                <a href="{{ route('admin.orders.show', $order) }}"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-semibold text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors">
                                    <i class="fas fa-eye text-xs"></i> Chi tiết
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 rounded-2xl bg-gray-100 flex items-center justify-center mb-3">
                                        <i class="fas fa-shopping-bag text-2xl text-gray-300"></i>
                                    </div>
                                    <p class="text-sm font-medium text-gray-500">Không tìm thấy đơn hàng</p>
                                    <p class="text-xs text-gray-400 mt-1">Thử thay đổi bộ lọc tìm kiếm</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($orders->hasPages())
            <div class="px-5 py-3 border-t border-gray-100">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection