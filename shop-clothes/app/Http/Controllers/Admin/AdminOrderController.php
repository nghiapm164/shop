<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\OrderStatusUpdatedMail;
use App\Models\Order;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AdminOrderController extends Controller
{
    /**
     * Display a listing of orders with filtering and search.
     */
    public function index(Request $request): View
    {
        $query = Order::with('user')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_code', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($sub) use ($search) {
                        $sub->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => Order::count(),
            'pending' => Order::where('status', Order::STATUS_PENDING)->count(),
            'confirmed' => Order::where('status', Order::STATUS_CONFIRMED)->count(),
            'processing' => Order::where('status', Order::STATUS_PROCESSING)->count(),
            'shipped' => Order::where('status', Order::STATUS_SHIPPED)->count(),
            'delivered' => Order::where('status', Order::STATUS_DELIVERED)->count(),
            'cancelled' => Order::where('status', Order::STATUS_CANCELLED)->count(),
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order): View
    {
        $order->load([
            'user',
            'items.productVariant.product.images',
            'items.productVariant.size',
            'items.productVariant.color',
        ]);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update order status.
     */
    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:' . implode(',', [
                Order::STATUS_PENDING,
                Order::STATUS_CONFIRMED,
                Order::STATUS_PROCESSING,
                Order::STATUS_SHIPPED,
                Order::STATUS_DELIVERED,
                Order::STATUS_CANCELLED,
                Order::STATUS_REFUNDED,
            ]),
        ]);

        $newStatus = $request->status;
        $oldStatus = $order->status;

        $allowedTransitions = $this->getAllowedTransitions($oldStatus);
        if (!in_array($newStatus, $allowedTransitions)) {
            return back()->with('error', "Không thể chuyển từ trạng thái \"{$this->getStatusLabel($oldStatus)}\" sang \"{$this->getStatusLabel($newStatus)}\".");
        }

        $order->update(['status' => $newStatus]);

        // Send status update email
        try {
            Mail::to($order->user->email)->send(new OrderStatusUpdatedMail($order, $oldStatus, $newStatus));
        } catch (\Exception $e) {
            Log::warning('Failed to send order status email: ' . $e->getMessage());
        }

        // Auto-update payment status when delivered with COD
        if ($newStatus === Order::STATUS_DELIVERED && $order->payment_method === 'cod') {
            $order->update(['payment_status' => Order::PAYMENT_STATUS_PAID]);
        }

        // Restore stock when cancelled or refunded
        if (in_array($newStatus, [Order::STATUS_CANCELLED, Order::STATUS_REFUNDED])) {
            $this->restoreStock($order);
        }

        return back()->with('success', "Đã cập nhật trạng thái đơn hàng {$order->order_code} thành \"{$this->getStatusLabel($newStatus)}\".");
    }

    /**
     * Update payment status.
     */
    public function updatePaymentStatus(Request $request, Order $order): RedirectResponse
    {
        $request->validate([
            'payment_status' => 'required|in:' . implode(',', [
                Order::PAYMENT_STATUS_UNPAID,
                Order::PAYMENT_STATUS_PAID,
                Order::PAYMENT_STATUS_REFUNDED,
            ]),
        ]);

        $order->update(['payment_status' => $request->payment_status]);

        return back()->with('success', "Đã cập nhật trạng thái thanh toán đơn hàng {$order->order_code}.");
    }

    /**
     * Print order invoice.
     */
    public function print(Order $order): View
    {
        $order->load([
            'user',
            'items.productVariant.product',
            'items.productVariant.size',
            'items.productVariant.color',
        ]);

        return view('admin.orders.print', compact('order'));
    }

    /**
     * Get allowed status transitions.
     */
    private function getAllowedTransitions(string $currentStatus): array
    {
        return match ($currentStatus) {
            Order::STATUS_PENDING => [Order::STATUS_CONFIRMED, Order::STATUS_CANCELLED],
            Order::STATUS_CONFIRMED => [Order::STATUS_PROCESSING, Order::STATUS_CANCELLED],
            Order::STATUS_PROCESSING => [Order::STATUS_SHIPPED, Order::STATUS_CANCELLED],
            Order::STATUS_SHIPPED => [Order::STATUS_DELIVERED],
            Order::STATUS_DELIVERED => [Order::STATUS_REFUNDED],
            Order::STATUS_CANCELLED => [],
            Order::STATUS_REFUNDED => [],
            default => [],
        };
    }

    /**
     * Get human readable status label.
     */
    private function getStatusLabel(string $status): string
    {
        return Order::getStatusLabel($status);
    }

    /**
     * Restore stock for cancelled/refunded orders.
     */
    private function restoreStock(Order $order): void
    {
        foreach ($order->items as $item) {
            if ($item->product_variant_id) {
                $variant = $item->productVariant;
                if ($variant) {
                    $variant->increment('stock_quantity', $item->quantity);
                }
            }
        }
    }
}