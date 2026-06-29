<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderLookupController extends Controller
{
    /**
     * Display the order lookup form.
     */
    public function index(): View
    {
        return view('orders.lookup');
    }

    /**
     * Search for an order by order code and verify with email or phone.
     */
    public function search(Request $request)
    {
        $request->validate([
            'order_code' => ['required', 'string', 'max:50'],
            'contact' => ['required', 'string', 'max:100'],
        ], [
            'order_code.required' => 'Vui lòng nhập mã đơn hàng',
            'contact.required' => 'Vui lòng nhập email hoặc số điện thoại',
        ]);

        $orderCode = strtoupper(trim($request->order_code));
        $contact = trim($request->contact);

        $order = Order::where('order_code', $orderCode)
            ->with([
                'items.productVariant.product.images',
                'items.productVariant.size',
                'items.productVariant.color',
                'user',
            ])
            ->first();

        if (!$order) {
            return back()
                ->withInput($request->only('order_code'))
                ->withErrors(['order_code' => 'Không tìm thấy đơn hàng với mã "' . $orderCode . '". Vui lòng kiểm tra lại mã đơn hàng.']);
        }

        // Verify by email or phone
        $matchesEmail = $order->user && strtolower($order->user->email) === strtolower($contact);
        $matchesPhone = $order->shipping_address &&
            isset($order->shipping_address['phone']) &&
            preg_replace('/[^0-9]/', '', $order->shipping_address['phone']) === preg_replace('/[^0-9]/', '', $contact);

        // Also check if contact matches the last 4 digits of order code (lenient fallback)
        if (!$matchesEmail && !$matchesPhone) {
            return back()
                ->withInput($request->only('order_code'))
                ->withErrors(['contact' => 'Thông tin không khớp với đơn hàng. Vui lòng kiểm tra lại email hoặc số điện thoại đã dùng khi đặt hàng.']);
        }

        return view('orders.lookup-result', compact('order'));
    }
}
