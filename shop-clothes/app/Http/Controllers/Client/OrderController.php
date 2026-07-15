<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Mail\OrderStatusUpdatedMail;
use App\Models\Order;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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

    // HUY_DON_HANG (Hàm xử lý hủy đơn hàng)
    /**
     * Cancel an order (client-side).
     * Hàm này cho phép khách hàng tự hủy đơn hàng của mình
     */
    public function cancel(Order $order): RedirectResponse
    {
        // 1. Kiểm tra bảo mật: Chỉ chủ nhân của đơn hàng mới được quyền hủy
        if ($order->user_id !== auth()->id()) {
            throw new AuthorizationException('Bạn không có quyền hủy đơn hàng này.');
        }

        // 2. Chỉ cho phép hủy khi đơn hàng đang ở trạng thái Chờ xử lý
        // Nếu đã xác nhận hoặc giao cho đơn vị vận chuyển thì không thể hủy
        if ($order->status !== Order::STATUS_PENDING) {
            return back()->with('error', 'Không thể hủy đơn hàng ở trạng thái hiện tại.');
        }

        $oldStatus = $order->status;
        // 3. Cập nhật trạng thái đơn hàng thành "Đã hủy"
        $order->update(['status' => Order::STATUS_CANCELLED]);

        // 4. Hoàn lại số lượng tồn kho cho các sản phẩm trong đơn hàng
        // Restore stock
        foreach ($order->items as $item) {
            if ($item->product_variant_id) {
                $variant = $item->productVariant;
                if ($variant) {
                    $variant->increment('stock_quantity', $item->quantity);
                }
            }
        }

        // 5. Gửi email thông báo hủy đơn hàng cho khách
        // Send cancellation email to customer
        try {
            $order->load('user');
            if ($order->user && $order->user->email) {
                Mail::to($order->user->email)->send(new OrderStatusUpdatedMail($order, $oldStatus, Order::STATUS_CANCELLED));
            }
        } catch (\Exception $e) {
            Log::warning('Failed to send order cancellation email: ' . $e->getMessage());
        }

        return back()->with('success', 'Đã hủy đơn hàng ' . $order->order_code . ' thành công.');
    }
}
