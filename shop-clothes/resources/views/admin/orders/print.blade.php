<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa đơn {{ $order->order_code }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 14px; color: #333; padding: 20px; }
        .invoice-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 3px solid #dc2626; }
        .company-info h1 { font-size: 24px; color: #dc2626; margin-bottom: 5px; }
        .company-info p { font-size: 12px; color: #666; }
        .invoice-info { text-align: right; }
        .invoice-info h2 { font-size: 20px; color: #333; margin-bottom: 10px; }
        .invoice-info p { font-size: 13px; color: #666; }
        .section { margin-bottom: 20px; }
        .section h3 { font-size: 14px; color: #dc2626; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 1px; }
        .customer-info { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
        .info-item label { font-size: 12px; color: #999; display: block; margin-bottom: 2px; }
        .info-item p { font-size: 14px; font-weight: 600; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        thead th { background: #f8f9fa; padding: 10px 12px; text-align: left; font-size: 12px; color: #666; text-transform: uppercase; border-bottom: 2px solid #dee2e6; }
        tbody td { padding: 10px 12px; border-bottom: 1px solid #eee; font-size: 13px; }
        tbody tr:last-child td { border-bottom: 2px solid #dee2e6; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .totals { display: flex; justify-content: flex-end; }
        .totals-box { width: 300px; }
        .totals-row { display: flex; justify-content: space-between; padding: 6px 0; font-size: 13px; }
        .totals-row.total { font-size: 18px; font-weight: 700; color: #dc2626; border-top: 2px solid #333; padding-top: 10px; margin-top: 5px; }
        .footer { margin-top: 40px; padding-top: 20px; border-top: 1px solid #ddd; text-align: center; font-size: 12px; color: #999; }
        .status-badge { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; }
        .status-pending { background: #dbeafe; color: #2563eb; }
        .status-confirmed { background: #e0f2fe; color: #0284c7; }
        .status-processing { background: #fef3c7; color: #d97706; }
        .status-shipped { background: #ede9fe; color: #7c3aed; }
        .status-delivered { background: #dcfce7; color: #16a34a; }
        .status-cancelled { background: #fee2e2; color: #dc2626; }
        .status-refunded { background: #f3f4f6; color: #6b7280; }
        @media print {
            body { padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 20px; text-align: right;">
        <button onclick="window.print()" style="padding: 8px 20px; background: #dc2626; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">
            🖨️ In hóa đơn
        </button>
        <button onclick="window.close()" style="padding: 8px 20px; background: #6b7280; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; margin-left: 8px;">
            Đóng
        </button>
    </div>

    <div class="invoice-header">
        <div class="company-info">
            <h1>🏋️ ShopGym</h1>
            <p>Shop quần áo thể thao chuyên nghiệp</p>
            <p>Địa chỉ: Hà Nội, Việt Nam</p>
            <p>Điện thoại: 0123 456 789</p>
        </div>
        <div class="invoice-info">
            <h2>HÓA ĐƠN BÁN HÀNG</h2>
            <p><strong>Mã đơn:</strong> {{ $order->order_code }}</p>
            <p><strong>Ngày:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
            <p>
                <span class="status-badge status-{{ $order->status }}">
                    {{ \App\Models\Order::getStatusLabel($order->status) }}
                </span>
            </p>
        </div>
    </div>

    <div class="section">
        <h3>Thông tin khách hàng</h3>
        <div class="customer-info">
            <div class="info-item">
                <label>Họ tên</label>
                <p>{{ $order->user->name ?? 'N/A' }}</p>
            </div>
            <div class="info-item">
                <label>Email</label>
                <p>{{ $order->user->email ?? 'N/A' }}</p>
            </div>
            <div class="info-item">
                <label>Điện thoại</label>
                <p>{{ $order->shipping_address['phone'] ?? $order->user->phone ?? 'N/A' }}</p>
            </div>
            <div class="info-item">
                <label>Địa chỉ giao hàng</label>
                <p>{{ $order->shipping_address['address'] ?? 'N/A' }}</p>
            </div>
        </div>
    </div>

    <div class="section">
        <h3>Chi tiết đơn hàng</h3>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Sản phẩm</th>
                    <th>Phân loại</th>
                    <th class="text-right">Đơn giá</th>
                    <th class="text-center">SL</th>
                    <th class="text-right">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $item->product_name }}</strong></td>
                        <td>
                            {{ $item->size ?? '' }}{{ $item->size && $item->color ? ' - ' : '' }}{{ $item->color ?? '' }}
                        </td>
                        <td class="text-right">{{ number_format($item->price, 0, ',', '.') }}₫</td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-right"><strong>{{ number_format($item->subtotal, 0, ',', '.') }}₫</strong></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="totals">
        <div class="totals-box">
            <div class="totals-row">
                <span>Tạm tính:</span>
                <span>{{ number_format($order->subtotal, 0, ',', '.') }}₫</span>
            </div>
            @if($order->discount_amount > 0)
                <div class="totals-row">
                    <span>Giảm giá:</span>
                    <span>-{{ number_format($order->discount_amount, 0, ',', '.') }}₫</span>
                </div>
            @endif
            <div class="totals-row">
                <span>Phí vận chuyển:</span>
                <span>{{ $order->shipping_fee > 0 ? number_format($order->shipping_fee, 0, ',', '.') . '₫' : 'Miễn phí' }}</span>
            </div>
            <div class="totals-row total">
                <span>Tổng cộng:</span>
                <span>{{ number_format($order->total, 0, ',', '.') }}₫</span>
            </div>
        </div>
    </div>

    <div class="section" style="margin-top: 30px;">
        <div class="customer-info">
            <div class="info-item" style="text-align: center;">
                <label>Người mua</label>
                <p style="margin-top: 40px; border-top: 1px dashed #999; padding-top: 5px;">(Ký tên)</p>
            </div>
            <div class="info-item" style="text-align: center;">
                <label>Người bán</label>
                <p style="margin-top: 40px; border-top: 1px dashed #999; padding-top: 5px;">(Ký tên)</p>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>Cảm ơn bạn đã mua hàng tại ShopGym!</p>
        <p>Hóa đơn được in lúc {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>