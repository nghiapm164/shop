@extends('layouts.admin')

@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Revenue Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-semibold">Doanh thu tháng này</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">
                        {{ number_format($stats['this_month_revenue'], 0, ',', '.') }}₫
                    </p>
                    <p class="text-xs text-gray-500 mt-2">Hôm nay: {{ number_format($stats['today_revenue'], 0, ',', '.') }}₫</p>
                </div>
                <div class="text-red-600 text-5xl opacity-20">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
            
            <!-- Change indicator -->
            <div class="mt-4 pt-4 border-t border-gray-200">
                @if ($stats['revenue_change_percent'] >= 0)
                    <span class="text-green-600 text-sm font-semibold">
                        <i class="fas fa-arrow-up"></i> +{{ $stats['revenue_change_percent'] }}%
                    </span>
                    <span class="text-xs text-gray-500 ml-2">so với tháng trước</span>
                @else
                    <span class="text-red-600 text-sm font-semibold">
                        <i class="fas fa-arrow-down"></i> {{ $stats['revenue_change_percent'] }}%
                    </span>
                    <span class="text-xs text-gray-500 ml-2">so với tháng trước</span>
                @endif
            </div>
        </div>

        <!-- New Orders Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-semibold">Đơn hàng mới tháng này</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['this_month_new_orders'] }}</p>
                    <p class="text-xs text-gray-500 mt-2">Hôm nay: {{ $stats['today_new_orders'] }} đơn</p>
                </div>
                <div class="text-blue-600 text-5xl opacity-20">
                    <i class="fas fa-shopping-bag"></i>
                </div>
            </div>

            <!-- Change indicator -->
            <div class="mt-4 pt-4 border-t border-gray-200">
                @if ($stats['orders_change_percent'] >= 0)
                    <span class="text-green-600 text-sm font-semibold">
                        <i class="fas fa-arrow-up"></i> +{{ $stats['orders_change_percent'] }}%
                    </span>
                    <span class="text-xs text-gray-500 ml-2">so với tháng trước</span>
                @else
                    <span class="text-red-600 text-sm font-semibold">
                        <i class="fas fa-arrow-down"></i> {{ $stats['orders_change_percent'] }}%
                    </span>
                    <span class="text-xs text-gray-500 ml-2">so với tháng trước</span>
                @endif
            </div>
        </div>

        <!-- New Customers Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-semibold">Khách hàng mới tháng này</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['this_month_new_customers'] }}</p>
                    <p class="text-xs text-gray-500 mt-2">Hôm nay: {{ $stats['today_new_customers'] }} khách</p>
                </div>
                <div class="text-purple-600 text-5xl opacity-20">
                    <i class="fas fa-users"></i>
                </div>
            </div>

            <!-- Change indicator -->
            <div class="mt-4 pt-4 border-t border-gray-200">
                @if ($stats['customers_change_percent'] >= 0)
                    <span class="text-green-600 text-sm font-semibold">
                        <i class="fas fa-arrow-up"></i> +{{ $stats['customers_change_percent'] }}%
                    </span>
                    <span class="text-xs text-gray-500 ml-2">so với tháng trước</span>
                @else
                    <span class="text-red-600 text-sm font-semibold">
                        <i class="fas fa-arrow-down"></i> {{ $stats['customers_change_percent'] }}%
                    </span>
                    <span class="text-xs text-gray-500 ml-2">so với tháng trước</span>
                @endif
            </div>
        </div>

        <!-- Low Stock Alert Card -->
        <div class="bg-white rounded-lg shadow-sm border {{ $stats['low_stock_count'] > 0 ? 'border-red-300 bg-red-50' : 'border-gray-200' }} p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm {{ $stats['low_stock_count'] > 0 ? 'text-red-600' : 'text-gray-600' }} font-semibold">Cảnh báo tồn kho</p>
                    <p class="text-3xl font-bold {{ $stats['low_stock_count'] > 0 ? 'text-red-600' : 'text-gray-900' }} mt-2">{{ $stats['low_stock_count'] }}</p>
                    <p class="text-xs text-gray-500 mt-2">Sản phẩm sắp hết (< 5 cái)</p>
                </div>
                <div class="{{ $stats['low_stock_count'] > 0 ? 'text-red-600' : 'text-yellow-600' }} text-5xl opacity-20">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
            </div>

            <!-- Alert -->
            @if ($stats['low_stock_count'] > 0)
                <div class="mt-4 pt-4 border-t border-red-300">
                    <a href="{{ route('admin.products.index') ?? '#' }}" class="text-red-600 text-sm font-semibold hover:underline">
                        Xem chi tiết <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            @else
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <span class="text-green-600 text-sm font-semibold">
                        <i class="fas fa-check"></i> Tất cả sản phẩm đều có đủ tồn kho
                    </span>
                </div>
            @endif
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Revenue Chart (takes 2 columns) -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-6">Doanh thu 30 ngày gần nhất</h3>
            <div class="relative h-80">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Orders by Status Chart -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-6">Đơn hàng theo trạng thái</h3>
            <div class="relative h-80">
                <canvas id="ordersByStatusChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Tables Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Orders -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-900">Đơn hàng mới nhất</h3>
                <a href="{{ route('admin.orders.index') ?? '#' }}" class="text-red-600 hover:text-red-700 text-sm font-semibold">
                    Xem tất cả <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-left py-3 px-4 text-sm font-semibold text-gray-900">Mã đơn</th>
                            <th class="text-left py-3 px-4 text-sm font-semibold text-gray-900">Khách hàng</th>
                            <th class="text-left py-3 px-4 text-sm font-semibold text-gray-900">Tổng tiền</th>
                            <th class="text-left py-3 px-4 text-sm font-semibold text-gray-900">Trạng thái</th>
                            <th class="text-left py-3 px-4 text-sm font-semibold text-gray-900">Thời gian</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentOrders as $order)
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="py-3 px-4 text-sm font-semibold text-gray-900">{{ $order->order_code }}</td>
                                <td class="py-3 px-4 text-sm text-gray-700">{{ $order->user->name }}</td>
                                <td class="py-3 px-4 text-sm font-semibold text-red-600">
                                    {{ number_format($order->total, 0, ',', '.') }}₫
                                </td>
                                <td class="py-3 px-4 text-sm">
                                    @switch($order->status)
                                        @case('pending')
                                            <span class="px-2 py-1 text-xs font-semibold text-blue-600 bg-blue-100 rounded-full">Chờ xử lý</span>
                                            @break
                                        @case('processing')
                                            <span class="px-2 py-1 text-xs font-semibold text-amber-600 bg-amber-100 rounded-full">Đang chuẩn bị</span>
                                            @break
                                        @case('shipped')
                                            <span class="px-2 py-1 text-xs font-semibold text-purple-600 bg-purple-100 rounded-full">Đang giao</span>
                                            @break
                                        @case('completed')
                                            <span class="px-2 py-1 text-xs font-semibold text-green-600 bg-green-100 rounded-full">Hoàn thành</span>
                                            @break
                                        @case('cancelled')
                                            <span class="px-2 py-1 text-xs font-semibold text-red-600 bg-red-100 rounded-full">Đã hủy</span>
                                            @break
                                    @endswitch
                                </td>
                                <td class="py-3 px-4 text-sm text-gray-600">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 px-4 text-center text-gray-500">
                                    <i class="fas fa-inbox text-3xl opacity-30 mb-4"></i>
                                    <p class="mt-2">Chưa có đơn hàng</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Top Products -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-900">Sản phẩm bán chạy</h3>
                <span class="text-xs text-gray-500">(Tháng này)</span>
            </div>

            <div class="space-y-4 max-h-96 overflow-y-auto">
                @forelse ($topProducts as $product)
                    <div class="flex gap-3 pb-4 border-b border-gray-200 last:border-0 hover:bg-gray-50 p-2 rounded transition-colors">
                        <img
                            src="{{ asset('storage/' . $product->image_path) }}"
                            alt="{{ $product->name }}"
                            class="w-12 h-12 object-cover rounded">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900 truncate">{{ $product->name }}</p>
                            <p class="text-xs text-gray-600 mt-1">
                                <i class="fas fa-box mr-1"></i>
                                {{ $product->total_quantity }} bán
                            </p>
                            <p class="text-xs text-red-600 font-semibold mt-1">
                                {{ number_format($product->total_revenue, 0, ',', '.') }}₫
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="py-8 text-center">
                        <i class="fas fa-chart-line text-3xl text-gray-300 mb-4"></i>
                        <p class="text-sm text-gray-500">Chưa có dữ liệu</p>
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
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: revenueData.labels,
            datasets: [{
                label: 'Doanh thu (₫)',
                data: revenueData.data,
                borderColor: '#DC2626',
                backgroundColor: 'rgba(220, 38, 38, 0.05)',
                borderWidth: 2,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#DC2626',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 15,
                    }
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: { size: 12 },
                    bodyFont: { size: 12 },
                    callbacks: {
                        label: function(context) {
                            return 'Doanh thu: ' + number_format(context.raw) + '₫';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)',
                    },
                    ticks: {
                        callback: function(value) {
                            if (value >= 1000000) {
                                return (value / 1000000).toFixed(0) + 'M';
                            }
                            return value;
                        }
                    }
                },
                x: {
                    grid: {
                        display: false,
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
                borderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 15,
                        font: { size: 12 }
                    }
                }
            }
        }
    });

    // Helper function to format numbers
    function number_format(num) {
        return new Intl.NumberFormat('vi-VN').format(num);
    }
</script>
@endsection
