<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận đơn hàng #{{ $order->order_code }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f8fafc;
            color: #334155;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            padding: 20px 0;
        }
        .logo {
            font-size: 28px;
            font-weight: 900;
            color: #0f172a;
            text-decoration: none;
            letter-spacing: -1px;
        }
        .logo span {
            color: #ef4444;
        }
        .content {
            background-color: #ffffff;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border: 1px solid #e2e8f0;
        }
        h1 {
            font-size: 22px;
            font-weight: 700;
            color: #16a34a;
            margin-top: 0;
            text-align: center;
            border-bottom: 2px solid #f1f5f9;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .order-info {
            background-color: #f8fafc;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
        }
        .order-info p {
            margin: 5px 0;
            font-size: 15px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .table th {
            background-color: #f1f5f9;
            color: #475569;
            text-align: left;
            padding: 12px;
            font-size: 14px;
            border-bottom: 2px solid #e2e8f0;
        }
        .table td {
            padding: 12px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 15px;
        }
        .table .text-right {
            text-align: right;
        }
        .table .text-center {
            text-align: center;
        }
        .product-name {
            font-weight: 600;
            color: #0f172a;
            display: block;
        }
        .product-meta {
            font-size: 13px;
            color: #64748b;
        }
        .summary-row {
            font-size: 15px;
        }
        .summary-row td {
            border-bottom: none;
            padding: 8px 12px;
        }
        .total-row {
            font-weight: 700;
            font-size: 18px;
            color: #ef4444;
        }
        .button-wrapper {
            text-align: center;
            margin: 30px 0 10px;
        }
        .button {
            display: inline-block;
            background-color: #0f172a;
            color: #ffffff !important;
            font-weight: 600;
            font-size: 15px;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 8px;
        }
        .footer {
            text-align: center;
            padding: 25px 0;
            color: #64748b;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <a href="{{ config('app.url') }}" class="logo">
                MODE<span>SPORT</span>
            </a>
        </div>
        
        <div class="content">
            <h1>Cảm ơn bạn đã đặt hàng! 🎉</h1>
            
            <p>Xin chào <strong>{{ $order->user->name }}</strong>,</p>
            <p>Đơn hàng <strong>#{{ $order->order_code }}</strong> của bạn đã được tiếp nhận và đang trong quá trình xử lý. Dưới đây là chi tiết đơn hàng của bạn:</p>
            
            <div class="order-info">
                <p><strong>Mã đơn hàng:</strong> {{ $order->order_code }}</p>
                <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                <p><strong>Người nhận:</strong> {{ $order->shipping_address['recipient_name'] }}</p>
                <p><strong>Điện thoại:</strong> {{ $order->shipping_address['phone'] }}</p>
                <p><strong>Địa chỉ:</strong> {{ $order->shipping_address['address'] }}</p>
                <p><strong>Phương thức thanh toán:</strong> 
                    @if($order->payment_method == 'cod')
                        Thanh toán khi nhận hàng (COD)
                    @elseif($order->payment_method == 'bank_transfer')
                        Chuyển khoản ngân hàng
                    @elseif($order->payment_method == 'e_wallet')
                        Thanh toán qua ví điện tử VNPay
                    @else
                        {{ $order->payment_method }}
                    @endif
                </p>
            </div>
            
            <table class="table">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th class="text-center">SL</th>
                        <th class="text-right">Giá</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                    <tr>
                        <td>
                            <span class="product-name">{{ $item->product_name }}</span>
                            <span class="product-meta">Màu: {{ $item->color }} - Size: {{ $item->size }}</span>
                        </td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-right">{{ number_format($item->price, 0, ',', '.') }}đ</td>
                    </tr>
                    @endforeach
                    
                    <tr class="summary-row" style="border-top: 2px solid #e2e8f0; margin-top: 10px;">
                        <td colspan="2" class="text-right">Tạm tính:</td>
                        <td class="text-right">{{ number_format($order->subtotal, 0, ',', '.') }}đ</td>
                    </tr>
                    @if($order->discount_amount > 0)
                    <tr class="summary-row">
                        <td colspan="2" class="text-right">Giảm giá:</td>
                        <td class="text-right" style="color: #16a34a;">-{{ number_format($order->discount_amount, 0, ',', '.') }}đ</td>
                    </tr>
                    @endif
                    <tr class="summary-row">
                        <td colspan="2" class="text-right">Phí vận chuyển:</td>
                        <td class="text-right">{{ number_format($order->shipping_fee, 0, ',', '.') }}đ</td>
                    </tr>
                    <tr class="summary-row total-row">
                        <td colspan="2" class="text-right">Tổng cộng:</td>
                        <td class="text-right">{{ number_format($order->total, 0, ',', '.') }}đ</td>
                    </tr>
                </tbody>
            </table>
            
            <div class="button-wrapper">
                <a href="{{ url('/user/orders') }}" class="button" target="_blank">Xem Chi Tiết Đơn Hàng</a>
            </div>
            
            <p style="margin-bottom: 0;">Trân trọng,<br>Đội ngũ <strong>MODESPORT</strong></p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} MODESPORT. Đã đăng ký bản quyền.</p>
        </div>
    </div>
</body>
</html>
