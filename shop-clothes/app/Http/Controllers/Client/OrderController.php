<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display the client's order history.
     */
    public function index(Request $request): View
    {
        $query = Order::where('user_id', auth()->id())
            ->with('items.productVariant.product.images')
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('order_code', 'like', "%{$search}%");
        }

        $orders = $query->paginate(10)->withQueryString();

        // Summary counts
        $counts = [
            'all' => Order::where('user_id', auth()->id())->count(),
            'pending' => Order::where('user_id', auth()->id())->where('status', Order::STATUS_PENDING)->count(),
            'confirmed' => Order::where('user_id', auth()->id())->where('status', Order::STATUS_CONFIRMED)->count(),
            'processing' => Order::where('user_id', auth()->id())->where('status', Order::STATUS_PROCESSING)->count(),
            'shipped' => Order::where('user_id', auth()->id())->where('status', Order::STATUS_SHIPPED)->count(),
            'delivered' => Order::where('user_id', auth()->id())->where('status', Order::STATUS_DELIVERED)->count(),
            'cancelled' => Order::where('user_id', auth()->id())->where('status', Order::STATUS_CANCELLED)->count(),
        ];

        return view('client.orders.index', compact('orders', 'counts'));
    }

    /**
     * Display a specific order detail for the client.
     */
    public function show(Order $order): View
    {
        if ($order->user_id !== auth()->id()) {
            throw new AuthorizationException('Bạn không có quyền xem đơn hàng này.');
        }

        $order->load([
            'items.productVariant.product.images',
            'items.productVariant.size',
            'items.productVariant.color',
        ]);

        return view('client.orders.show', compact('order'));
    }

    /**
     * Cancel an order (client-side).
     */
    public function cancel(Order $order): RedirectResponse
    {
        if ($order->user_id !== auth()->id()) {
            throw new AuthorizationException('Bạn không có quyền hủy đơn hàng này.');
        }

        if (!in_array($order->status, [Order::STATUS_PENDING, Order::STATUS_CONFIRMED])) {
            return back()->with('error', 'Không thể hủy đơn hàng ở trạng thái hiện tại.');
        }

        $order->update(['status' => Order::STATUS_CANCELLED]);

        // Restore stock
        foreach ($order->items as $item) {
            if ($item->product_variant_id) {
                $variant = $item->productVariant;
                if ($variant) {
                    $variant->increment('stock_quantity', $item->quantity);
                }
            }
        }

        return back()->with('success', 'Đã hủy đơn hàng ' . $order->order_code . ' thành công.');
    }
}