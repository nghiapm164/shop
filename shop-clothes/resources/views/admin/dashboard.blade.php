@extends('layouts.admin')

@section('title', 'Tổng quan - Admin')
@section('page-title', 'Tổng quan')

@section('content')
<div class="space-y-6">
    {{-- Welcome Banner --}}
    <div class="bg-gradient-to-r from-red-600 via-red-500 to-orange-500 rounded-2xl p-6 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/4"></div>
        <div class="absolute bottom-0 left-1/2 w-40 h-40 bg-white/5 rounded-full translate-y-1/2"></div>
        <div class="relative z-10">
            <h2 class="text-2xl font-bold">Xin chào, {{ auth()->user()->name }}! 👋</h2>
            <p class="text-white/80 mt-1 text-sm">Đây là tổng quan hoạt động cửa hàng hôm nay.</p>
            <div class="flex flex-wrap gap-4 mt-4">
                <div class="bg-white/15 backdrop-blur-sm rounded-xl px-4 py-2">
                    <p class="text-white/70 text-xs">Doanh thu hôm nay</p>
                    <p class="text-lg font-bold">{{ number_format($stats['today_revenue'], 0, ',', '.') }}₫</p>
                </div>
                <div class="bg-white/15 backdrop-blur-sm rounded-xl px-4 py-2">
                    <p class="text-white/70 text-xs">Đơn hàng hôm nay</p>
                    <p class="text-lg font-bold">{{ $stats['today_new_orders'] }}</p>
                </div>
                <div class="bg-white/15 backdrop-blur-sm rounded-xl px-4 py-2">
                    <p class="text-white/70 text-xs">Khách mới hôm nay</p>
                    <p class="text-lg font-bold">{{ $stats['today_new_customers'] }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
        {{-- Revenue Card --}}
        <div class="stat-card bg-white rounded-2xl shadow-sm border border-gray-100 p-5 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-red-50 rounded-full -translate-y-1/2 translate-x-1/2"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-11 h-11 rounded-xl bg-red-50 flex items-center justify-center">
                        <i class="fas fa-chart-line text-red-500 text-lg"></i>
                    </div>
                    @if ($stats['revenue_change_percent'] >= 0)
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-green-50 text-green-600 rounded-lg text-xs font-semibold">
                            <i class="fas fa-arrow-up text-[10px]"></i> {{ $stats['revenue_change_percent'] }}%
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-red-50 text-red-600 rounded-lg text-xs font-semibold">
                            <i class="fas fa-arrow-down text-[10px]"></i> {{ abs($stats['revenue_change_percent']) }}%
                        </span>
                    @endif
                </div>
                <p class="text-sm text-gray-500 font-medium">Doanh thu tháng</p>
                <p class="text-2xl font-extrabold text-gray-900 mt-1">{{ number_format($stats['this_month_revenue'], 0, ',', '.') }}₫</p>
                <p class="text-xs text-gray-400 mt-2">Tháng trước: {{ number_format($stats['last_month_revenue'], 0, ',', '.') }}₫</p>
            </div>
        </div>

        {{-- Orders Card --}}
        <div class="stat-card bg-white rounded-2xl shadow-sm border border-gray-100 p-5 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-blue-50 rounded-full -translate-y-1/2 translate-x-1/2"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center">
                        <i class="fas fa-shopping-bag text-blue-500 text-lg"></i>
                    </div>
                    @if ($stats['orders_change_percent'] >= 0)
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-green-50 text-green-600 rounded-lg text-xs font-semibold">
                            <i class="fas fa-arrow-up text-[10px]"></i> {{ $stats['orders_change_percent'] }}%
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-red-50 text-red-600 rounded-lg text-xs font-semibold">
                            <i class="fas fa-arrow-down text-[10px]"></i> {{ abs($stats['orders_change_percent']) }}%
                        </span>
                    @endif
                </div>
                <p class="text-sm text-gray-500 font-medium">Đơn hàng tháng</p>
                <p class="text-2xl font-extrabold text-gray-900 mt-1">{{ $stats['this_month_new_orders'] }}</p>
                <p class="text-xs text-gray-400 mt-2">Tháng trước: {{ $stats['last_month_new_orders'] }} đơn</p>
            </div>
        </div>

        {{-- Customers Card --}}
        <div class="stat-card bg-white rounded-2xl shadow-sm border border-gray-100 p-5 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-purple-50 rounded-full -translate-y-1/2 translate-x-1/2"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-11 h-11 rounded-xl bg-purple-50 flex items-center justify-center">
                        <i class="fas fa-users text-purple-500 text-lg"></i>
                    </div>
                    @if ($stats['customers_change_percent'] >= 0)
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-green-50 text-green-600 rounded-lg text-xs font-semibold">
                            <i class="fas fa-arrow-up text-[10px]"></i> {{ $stats['customers_change_percent'] }}%
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-red-50 text-red-600 rounded-lg text-xs font-semibold">
                            <i class="fas fa-arrow-down text-[10px]"></i> {{ abs($stats['customers_change_percent']) }}%
                        </span>
                    @endif
                </div>
                <p class="text-sm text-gray-500 font-medium">Khách hàng mới</p>
                <p class="text-2xl font-extrabold text-gray-900 mt-1">{{ $stats['this_month_new_customers'] }}</p>
                <p class="text-xs text-gray-400 mt-2">Tháng trước: {{ $stats['last_month_new_customers'] }} khách</p>
            </div>
        </div>

        {{-- Low Stock Card --}}
        <div class="stat-card bg-white rounded-2xl shadow-sm border {{ $stats['low_stock_count'] > 0 ? 'border-red-200' : 'border-gray-100' }} p-5 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 {{ $stats['low_stock_count'] > 0 ? 'bg-red-50' : 'bg-amber-50' }} rounded-full -translate-y-1/2 translate-x-1/2"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-11 h-11 rounded-xl {{ $stats['low_stock_count'] > 0 ? 'bg-red-50' : 'bg-amber-50' }} flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle {{ $stats['low_stock_count'] > 0 ? 'text-red-500' : 'text-amber-500' }} text-lg"></i>
                    </div>
                    @if ($stats['low_stock_count'] > 0)
                        <a href="{{ route('admin.products.index') }}" class="text-xs text-red-600 font-semibold hover:underline">
                            Xem <i class="fas fa-arrow-right text-[10px] ml-1"></i>
                        </a>
                    @endif
                </div>
                <p class="text-sm text-gray-500 font-medium">Tồn kho thấp</p>
                <p class="text-2xl font-extrabold {{ $stats['low_stock_count'] > 0 ? 'text-red-600' : 'text-gray-900' }} mt-1">{{ $stats['low_stock_count'] }}</p>
                <p class="text-xs text-gray-400 mt-2">Sản phẩm còn ít hơn 5 cái</p>
            </div>
        </div>
    </div>

    {{-- Charts Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Revenue Chart --}}
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-base font-bold text-gray-900">Biểu đồ doanh thu</h3>
                    <p class="text-xs text-gray-400 mt-0.5">30 ngày gần nhất</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-red-500"></span>
                    <span class="text-xs text-gray-500">Doanh thu</span>
                </div>
            </div>
            <div class="relative h-72">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        {{-- Orders by Status --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="mb-6">
                <h3 class="text-base font-bold text-gray-900">Đơn hàng</h3>
                <p class="text-xs text-gray-400 mt-0.5">Phân bổ theo trạng thái</p>
            </div>
            <div class="relative h-56">
                <canvas id="ordersByStatusChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Tables Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Recent Orders --}}
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <h3 class="text-base font-bold text-gray-900">Đơn hàng gần đây</h3>
                    <p class="text-xs text-gray-400 mt-0.5">10 đơn hàng mới nhất</p>
                </div>
                <a href="{{ route('admin.orders.index') }}" 
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors">
                    Xem tất cả <i class="fas fa-arrow-right text-[10px]"></i>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50/80">
                            <th class="text-left py-3 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Mã đơn</th>
                            <th class="text-left py-3 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Khách hàng</th>
                            <th class="text-left py-3 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tổng tiền</th>
                            <th class="text-left py-3 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Trạng thái</th>
                            <th class="text-left py-3 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Thời gian</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse ($recentOrders as $order)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="py-3 px-6">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="text-sm font-bold text-red-600 hover:text-red-700 hover:underline">
                                        {{ $order->order_code }}
                                    </a>
                                </td>
                                <td class="py-3 px-6">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center text-xs font-bold text-gray-500">
                                            {{ strtoupper(mb_substr($order->user->name ?? 'N', 0, 1)) }}
                                        </div>
                                        <span class="text-sm text-gray-700">{{ $order->user->name ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td class="py-3 px-6">
                                    <span class="text-sm font-bold text-gray-900">{{ number_format($order->total, 0, ',', '.') }}₫</span>
                                </td>
                                <td class="py-3 px-6">
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
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Đang chuẩn bị
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
                                <td class="py-3 px-6 text-xs text-gray-500">{{ $order->created_at->format('d/m H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center">
                                    <i class="fas fa-inbox text-4xl text-gray-200 mb-3"></i>
                                    <p class="text-sm text-gray-400">Chưa có đơn hàng</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Top Products --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <h3 class="text-base font-bold text-gray-900">Sản phẩm bán chạy</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Top tháng này</p>
                </div>
            </div>

            <div class="divide-y divide-gray-50 max-h-[420px] overflow-y-auto">
                @forelse ($topProducts as $index => $product)
                    <div class="flex items-center gap-3 px-5 py-3 hover:bg-gray-50/50 transition-colors">
                        <span class="flex-shrink-0 w-6 h-6 rounded-lg {{ $index < 3 ? 'bg-red-50 text-red-600' : 'bg-gray-100 text-gray-400' }} flex items-center justify-center text-xs font-bold">
                            {{ $index + 1 }}
                        </span>
                        <img
                            src="{{ $product->image_path ? asset('storage/' . $product->image_path) : asset('images/placeholder.jpg') }}"
                            alt="{{ $product->name }}"
                            class="w-10 h-10 rounded-lg object-cover border border-gray-100">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900 truncate">{{ $product->name }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">
                                <i class="fas fa-box mr-1"></i>{{ $product->total_quantity }} đã bán
                            </p>
                        </div>
                        <span class="text-sm font-bold text-red-600 whitespace-nowrap">
                            {{ number_format($product->total_revenue / 1000000, 1) }}M₫
                        </span>
                    </div>
                @empty
                    <div class="py-12 text-center">
                        <i class="fas fa-chart-line text-4xl text-gray-200 mb-3"></i>
                        <p class="text-sm text-gray-400">Chưa có dữ liệu</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Revenue Chart
    const revenueData = @json(json_decode($revenueChartData, true));
    
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const gradient = revenueCtx.createLinearGradient(0, 0, 0, 280);
    gradient.addColorStop(0, 'rgba(220, 38, 38, 0.15)');
    gradient.addColorStop(1, 'rgba(220, 38, 38, 0)');

    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: revenueData.labels,
            datasets: [{
                label: 'Doanh thu (₫)',
                data: revenueData.data,
                borderColor: '#DC2626',
                backgroundColor: gradient,
                borderWidth: 2.5,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#DC2626',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 0,
                pointHoverRadius: 6,
                pointHoverBorderWidth: 3,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(17, 24, 39, 0.9)',
                    titleColor: '#fff',
                    bodyColor: '#d1d5db',
                    padding: 12,
                    cornerRadius: 12,
                    titleFont: { size: 12, weight: '600' },
                    bodyFont: { size: 12 },
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return '💰 ' + new Intl.NumberFormat('vi-VN').format(context.raw) + '₫';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    border: { display: false },
                    grid: { color: 'rgba(0, 0, 0, 0.04)' },
                    ticks: {
                        font: { size: 11 },
                        color: '#9ca3af',
                        callback: function(value) {
                            if (value >= 1000000) return (value / 1000000).toFixed(0) + 'M';
                            if (value >= 1000) return (value / 1000).toFixed(0) + 'K';
                            return value;
                        }
                    }
                },
                x: {
                    border: { display: false },
                    grid: { display: false },
                    ticks: {
                        font: { size: 10 },
                        color: '#9ca3af',
                        maxRotation: 0,
                        autoSkip: true,
                        maxTicksLimit: 10
                    }
                }
            }
        }
    });

    // Orders by Status Chart
    const ordersByStatusData = @json(json_decode($ordersByStatusData, true));
    
    const statusCtx = document.getElementById('ordersByStatusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ordersByStatusData.labels,
            datasets: [{
                data: ordersByStatusData.data,
                backgroundColor: ordersByStatusData.colors,
                borderColor: '#fff',
                borderWidth: 3,
                hoverBorderWidth: 0,
                hoverOffset: 8,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '68%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        pointStyle: 'circle',
                        padding: 16,
                        font: { size: 11 },
                        color: '#6b7280'
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(17, 24, 39, 0.9)',
                    padding: 12,
                    cornerRadius: 12,
                    titleFont: { size: 12, weight: '600' },
                    bodyFont: { size: 12 },
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const pct = ((context.raw / total) * 100).toFixed(1);
                            return ' ' + context.label + ': ' + context.raw + ' (' + pct + '%)';
                        }
                    }
                }
            }
        }
    });
</script>
@endsection