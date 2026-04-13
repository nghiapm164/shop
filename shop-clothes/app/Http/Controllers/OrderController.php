<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View;

class OrderController extends Controller
{
    /**
     * Display the order success page
     */
    public function success(string $code): View
    {
        $order = Order::where('order_code', $code)
            ->with('items.productVariant.product.images')
            ->firstOrFail();

        // Check if the order belongs to the authenticated user
        if ($order->user_id !== auth()->id()) {
            throw new AuthorizationException('Bạn không có quyền xem đơn hàng này');
        }

        return view('orders.success', compact('order'));
    }

    /**
     * Display the order detail page
     */
    public function show(string $code): View
    {
        $order = Order::where('order_code', $code)
            ->with('items.productVariant.product.images', 'user')
            ->firstOrFail();

        // Check if the order belongs to the authenticated user
        if ($order->user_id !== auth()->id()) {
            throw new AuthorizationException('Bạn không có quyền xem đơn hàng này');
        }

        return view('orders.show', compact('order'));
    }
}
