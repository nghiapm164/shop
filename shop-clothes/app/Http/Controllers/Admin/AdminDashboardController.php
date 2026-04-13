<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard
     */
    public function index(): View
    {
        // Get statistics
        $stats = $this->getQuickStats();
        
        // Get chart data
        $revenueChartData = $this->getRevenueChartData();
        $ordersByStatusData = $this->getOrdersByStatusData();
        
        // Get tables data
        $topProducts = $this->getTopProducts();
        $recentOrders = $this->getRecentOrders();

        return view('admin.dashboard', [
            'stats' => $stats,
            'revenueChartData' => json_encode($revenueChartData),
            'ordersByStatusData' => json_encode($ordersByStatusData),
            'topProducts' => $topProducts,
            'recentOrders' => $recentOrders,
        ]);
    }

    /**
     * Get quick statistics (today, this month, comparison)
     */
    private function getQuickStats(): array
    {
        $today = Carbon::now()->startOfDay();
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        // Revenue calculations
        $todayRevenue = Order::where('payment_status', 'paid')
            ->where('status', 'delivered')
            ->whereDate('created_at', $today)
            ->sum('total');

        $thisMonthRevenue = Order::where('payment_status', 'paid')
            ->where('status', 'delivered')
            ->whereBetween('created_at', [$thisMonth, Carbon::now()])
            ->sum('total');

        $lastMonthRevenue = Order::where('payment_status', 'paid')
            ->where('status', 'delivered')
            ->whereBetween('created_at', [$lastMonth, $lastMonthEnd])
            ->sum('total');

        // Revenue change percentage
        $revenueChangePercent = $lastMonthRevenue > 0 
            ? (($thisMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100 
            : 0;

        // New orders (pending + confirmed)
        $todayNewOrders = Order::whereIn('status', ['pending', 'confirmed'])
            ->whereDate('created_at', $today)
            ->count();

        $thisMonthNewOrders = Order::whereIn('status', ['pending', 'confirmed'])
            ->whereBetween('created_at', [$thisMonth, Carbon::now()])
            ->count();

        $lastMonthNewOrders = Order::whereIn('status', ['pending', 'confirmed'])
            ->whereBetween('created_at', [$lastMonth, $lastMonthEnd])
            ->count();

        $ordersChangePercent = $lastMonthNewOrders > 0 
            ? (($thisMonthNewOrders - $lastMonthNewOrders) / $lastMonthNewOrders) * 100 
            : 0;

        // New customers (registered today vs this month)
        $todayNewCustomers = User::whereDate('created_at', $today)
            ->where('role', '!=', 'admin')
            ->where('role', '!=', 'super_admin')
            ->count();

        $thisMonthNewCustomers = User::whereBetween('created_at', [$thisMonth, Carbon::now()])
            ->where('role', '!=', 'admin')
            ->where('role', '!=', 'super_admin')
            ->count();

        $lastMonthNewCustomers = User::whereBetween('created_at', [$lastMonth, $lastMonthEnd])
            ->where('role', '!=', 'admin')
            ->where('role', '!=', 'super_admin')
            ->count();

        $customersChangePercent = $lastMonthNewCustomers > 0 
            ? (($thisMonthNewCustomers - $lastMonthNewCustomers) / $lastMonthNewCustomers) * 100 
            : 0;

        // Low stock products (stock < 5)
        $lowStockCount = Product::join('product_variants', 'products.id', '=', 'product_variants.product_id')
            ->where('product_variants.stock_quantity', '<', 5)
            ->distinct('products.id')
            ->count();

        return [
            'today_revenue' => $todayRevenue,
            'this_month_revenue' => $thisMonthRevenue,
            'last_month_revenue' => $lastMonthRevenue,
            'revenue_change_percent' => round($revenueChangePercent, 2),
            'today_new_orders' => $todayNewOrders,
            'this_month_new_orders' => $thisMonthNewOrders,
            'last_month_new_orders' => $lastMonthNewOrders,
            'orders_change_percent' => round($ordersChangePercent, 2),
            'today_new_customers' => $todayNewCustomers,
            'this_month_new_customers' => $thisMonthNewCustomers,
            'last_month_new_customers' => $lastMonthNewCustomers,
            'customers_change_percent' => round($customersChangePercent, 2),
            'low_stock_count' => $lowStockCount,
        ];
    }

    /**
     * Get revenue data for the last 30 days for chart
     */
    private function getRevenueChartData(): array
    {
        $days = 30;
        $data = [];
        $labels = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->startOfDay();
            $labels[] = $date->format('d/m');

            $revenue = Order::where('payment_status', 'paid')
                ->where('status', 'delivered')
                ->whereDate('created_at', $date)
                ->sum('total');

            $data[] = $revenue;
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }

    /**
     * Get orders data grouped by status
     */
    private function getOrdersByStatusData(): array
    {
        $statuses = [
            'pending' => ['label' => 'Chờ xử lý', 'color' => '#3B82F6'],
            'confirmed' => ['label' => 'Đã xác nhận', 'color' => '#0EA5E9'],
            'processing' => ['label' => 'Đang chuẩn bị', 'color' => '#F59E0B'],
            'shipped' => ['label' => 'Đang giao', 'color' => '#8B5CF6'],
            'delivered' => ['label' => 'Đã giao', 'color' => '#10B981'],
            'cancelled' => ['label' => 'Đã hủy', 'color' => '#EF4444'],
            'refunded' => ['label' => 'Hoàn tiền', 'color' => '#6B7280'],
        ];

        $data = [];
        $labels = [];
        $colors = [];

        foreach ($statuses as $status => $meta) {
            $count = Order::where('status', $status)->count();
            if ($count > 0) {
                $labels[] = $meta['label'];
                $data[] = $count;
                $colors[] = $meta['color'];
            }
        }

        return [
            'labels' => $labels,
            'data' => $data,
            'colors' => $colors,
        ];
    }

    /**
     * Get top 10 products this month
     */
    private function getTopProducts(): Collection
    {
        $thisMonth = Carbon::now()->startOfMonth();

        return OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('product_variants', 'order_items.product_variant_id', '=', 'product_variants.id')
            ->join('products', 'product_variants.product_id', '=', 'products.id')
            ->leftJoin('product_images', 'products.id', '=', 'product_images.product_id')
            ->where('orders.payment_status', 'paid')
            ->where('orders.status', 'delivered')
            ->whereBetween('orders.created_at', [$thisMonth, Carbon::now()])
            ->groupBy('products.id', 'products.name')
            ->selectRaw('
                products.id,
                products.name,
                MAX(CASE WHEN product_images.is_primary = 1 THEN product_images.image_path ELSE NULL END) as image_path,
                SUM(order_items.quantity) as total_quantity,
                SUM(order_items.subtotal) as total_revenue
            ')
            ->orderByDesc('total_quantity')
            ->limit(10)
            ->get();
    }

    /**
     * Get recent 10 orders
     */
    private function getRecentOrders(): Collection
    {
        return Order::with('user')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();
    }
}
