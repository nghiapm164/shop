<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * Create VNPay payment URL and redirect.
     */
    public function createVnpayPayment(Request $request)
    {
        $orderCode = $request->get('order_code');
        $order = Order::where('order_code', $orderCode)->firstOrFail();

        if ($order->payment_status === Order::PAYMENT_STATUS_PAID) {
            return redirect()->route('order.success', ['code' => $order->order_code]);
        }

        $vnp_TmnCode = config('vnpay.vnp_TmnCode');
        $vnp_HashSecret = config('vnpay.vnp_HashSecret');
        $vnp_Url = config('vnpay.vnp_Url');
        $vnp_Returnurl = config('vnpay.vnp_ReturnUrl');

        if (empty($vnp_TmnCode) || empty($vnp_HashSecret)) {
            return redirect()->route('order.success', ['code' => $order->order_code])
                ->with('error', 'Cấu hình VNPay chưa hoàn tất. Vui lòng liên hệ quản trị viên.');
        }

        $vnp_TxnRef = $order->order_code;
        $vnp_OrderInfo = 'Thanh toan don hang ' . $order->order_code;
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = $order->total * 100;
        $vnp_Locale = 'vn';
        $vnp_IpAddr = $request->ip();
        $vnp_CreateDate = now()->format('YmdHis');
        $vnp_ExpireDate = now()->addMinutes(15)->format('YmdHis');

        $inputData = [
            'vnp_Version' => '2.1.0',
            'vnp_Command' => 'pay',
            'vnp_TmnCode' => $vnp_TmnCode,
            'vnp_Amount' => $vnp_Amount,
            'vnp_CurrCode' => 'VND',
            'vnp_TxnRef' => $vnp_TxnRef,
            'vnp_OrderInfo' => $vnp_OrderInfo,
            'vnp_OrderType' => $vnp_OrderType,
            'vnp_Locale' => $vnp_Locale,
            'vnp_ReturnUrl' => $vnp_Returnurl,
            'vnp_IpAddr' => $vnp_IpAddr,
            'vnp_CreateDate' => $vnp_CreateDate,
            'vnp_ExpireDate' => $vnp_ExpireDate,
        ];

        ksort($inputData);

        $query = '';
        $hashdata = '';
        $i = 0;
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . '=' . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . '=' . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . '=' . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . '?' . $query;
        $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
        $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;

        return redirect($vnp_Url);
    }

    /**
     * Handle VNPay callback/return.
     */
    public function vnpayCallback(Request $request)
    {
        $vnp_HashSecret = config('vnpay.vnp_HashSecret');

        $inputData = [];
        foreach ($request->query() as $key => $value) {
            if (substr($key, 0, 4) === 'vnp_') {
                $inputData[$key] = $value;
            }
        }

        $vnp_SecureHash = $inputData['vnp_SecureHash'] ?? '';
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);

        $hashdata = '';
        $i = 0;
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . '=' . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . '=' . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
        $orderCode = $request->query('vnp_TxnRef');
        $responseCode = $request->query('vnp_ResponseCode');

        if ($secureHash === $vnp_SecureHash) {
            $order = Order::where('order_code', $orderCode)->first();

            if (!$order) {
                return redirect()->route('home')->with('error', 'Không tìm thấy đơn hàng.');
            }

            if ($responseCode === '00') {
                // Payment successful
                $order->update([
                    'payment_status' => Order::PAYMENT_STATUS_PAID,
                ]);

                // Clear cart
                session()->forget('cart');
                session()->forget('coupon_id');

                return redirect()->route('order.success', ['code' => $order->order_code])
                    ->with('success', 'Thanh toán VNPay thành công!');
            } else {
                // Payment failed
                Log::warning('VNPay payment failed', [
                    'order_code' => $orderCode,
                    'response_code' => $responseCode,
                ]);

                return redirect()->route('order.success', ['code' => $order->order_code])
                    ->with('error', 'Thanh toán không thành công. Mã lỗi: ' . $responseCode);
            }
        }

        Log::warning('VNPay callback hash mismatch', ['order_code' => $orderCode]);

        return redirect()->route('order.success', ['code' => $orderCode])
            ->with('error', 'Chữ ký không hợp lệ. Vui lòng liên hệ hỗ trợ.');
    }

    /**
     * Handle VNPay IPN (server-to-server notification).
     */
    public function vnpayIpn(Request $request)
    {
        $vnp_HashSecret = config('vnpay.vnp_HashSecret');

        $inputData = [];
        foreach ($request->all() as $key => $value) {
            if (substr($key, 0, 4) === 'vnp_') {
                $inputData[$key] = $value;
            }
        }

        $vnp_SecureHash = $inputData['vnp_SecureHash'] ?? '';
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);

        $hashdata = '';
        $i = 0;
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . '=' . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . '=' . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);

        if ($secureHash === $vnp_SecureHash) {
            $orderCode = $request->get('vnp_TxnRef');
            $order = Order::where('order_code', $orderCode)->first();

            if (!$order) {
                return response()->json(['RspCode' => '01', 'Message' => 'Order not found']);
            }

            $amount = $request->get('vnp_Amount') / 100;
            if ($amount != $order->total) {
                return response()->json(['RspCode' => '04', 'Message' => 'Invalid Amount']);
            }

            if ($order->payment_status === Order::PAYMENT_STATUS_PAID) {
                return response()->json(['RspCode' => '02', 'Message' => 'Order already confirmed']);
            }

            $responseCode = $request->get('vnp_ResponseCode');
            if ($responseCode === '00') {
                $order->update(['payment_status' => Order::PAYMENT_STATUS_PAID]);
                return response()->json(['RspCode' => '00', 'Message' => 'Confirm Success']);
            } else {
                return response()->json(['RspCode' => '00', 'Message' => 'Confirm Success']);
            }
        }

        return response()->json(['RspCode' => '97', 'Message' => 'Invalid Checksum']);
    }
}