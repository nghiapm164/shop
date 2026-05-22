<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderPlacedMail extends Mailable
{
    use Queueable, SerializesModels;

    public Order $order;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Xác nhận đơn hàng ' . $this->order->order_code . ' - ShopGym',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            htmlString: $this->buildHtml(),
        );
    }

    /**
     * Build the HTML content.
     */
    private function buildHtml(): string
    {
        $order = $this->order;
        $order->load(['items', 'user']);
        $appName = config('app.name', 'ShopGym');

        $itemsHtml = '';
        foreach ($order->items as $item) {
            $variant = $item->size || $item->color ? " ({$item->size} - {$item->color})" : '';
            $itemsHtml .= "
            <tr>
                <td style='padding:12px 16px;border-bottom:1px solid #eee;font-size:14px;'>{$item->product_name}{$variant}</td>
                <td style='padding:12px 16px;border-bottom:1px solid #eee;text-align:center;font-size:14px;'>{$item->quantity}</td>
                <td style='padding:12px 16px;border-bottom:1px solid #eee;text-align:right;font-size:14px;font-weight:600;'>" . number_format($item->subtotal, 0, ',', '.') . "₫</td>
            </tr>";
        }

        $shippingAddress = $order->shipping_address;
        $addressLine = $shippingAddress['address'] ?? 'N/A';
        $recipientName = $shippingAddress['recipient_name'] ?? $order->user->name ?? 'N/A';
        $phone = $shippingAddress['phone'] ?? 'N/A';

        $paymentMethodLabel = match ($order->payment_method) {
            'cod' => 'Thanh toán khi nhận hàng (COD)',
            'bank_transfer' => 'Chuyển khoản ngân hàng',
            'e_wallet' => 'Ví điện tử (VNPay)',
            default => $order->payment_method,
        };

        return "
        <!DOCTYPE html>
        <html>
        <head><meta charset='UTF-8'></head>
        <body style='font-family:Arial,sans-serif;background:#f4f4f4;margin:0;padding:20px;'>
            <div style='max-width:600px;margin:0 auto;background:#fff;border-radius:8px;overflow:hidden;'>
                <div style='background:#dc2626;padding:24px;text-align:center;'>
                    <h1 style='color:#fff;margin:0;font-size:24px;'>🏋️ {$appName}</h1>
                    <p style='color:#fecaca;margin:8px 0 0;font-size:14px;'>Cảm ơn bạn đã đặt hàng!</p>
                </div>
                
                <div style='padding:24px;'>
                    <h2 style='color:#111;font-size:18px;margin:0 0 16px;'>Xác nhận đơn hàng #{$order->order_code}</h2>
                    <p style='color:#666;font-size:14px;line-height:1.6;'>
                        Xin chào <strong>{$recipientName}</strong>,<br>
                        Đơn hàng của bạn đã được tiếp nhận và đang được xử lý.
                    </p>
                    
                    <div style='background:#f8fafc;border-radius:8px;padding:16px;margin:20px 0;'>
                        <p style='margin:0 0 8px;font-size:13px;color:#666;'><strong>Mã đơn:</strong> #{$order->order_code}</p>
                        <p style='margin:0 0 8px;font-size:13px;color:#666;'><strong>Ngày đặt:</strong> {$order->created_at->format('d/m/Y H:i')}</p>
                        <p style='margin:0 0 8px;font-size:13px;color:#666;'><strong>Thanh toán:</strong> {$paymentMethodLabel}</p>
                        <p style='margin:0;font-size:13px;color:#666;'><strong>Giao đến:</strong> {$addressLine}</p>
                    </div>
                    
                    <table style='width:100%;border-collapse:collapse;margin:20px 0;'>
                        <thead>
                            <tr style='background:#f8fafc;'>
                                <th style='padding:12px 16px;text-align:left;font-size:12px;color:#666;text-transform:uppercase;border-bottom:2px solid #eee;'>Sản phẩm</th>
                                <th style='padding:12px 16px;text-align:center;font-size:12px;color:#666;text-transform:uppercase;border-bottom:2px solid #eee;'>SL</th>
                                <th style='padding:12px 16px;text-align:right;font-size:12px;color:#666;text-transform:uppercase;border-bottom:2px solid #eee;'>Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>{$itemsHtml}</tbody>
                    </table>
                    
                    <div style='text-align:right;padding:16px 0;border-top:2px solid #eee;'>
                        <p style='margin:0 0 6px;font-size:14px;color:#666;'>Tạm tính: " . number_format($order->subtotal, 0, ',', '.') . "₫</p>" .
                        ($order->discount_amount > 0 ? "<p style='margin:0 0 6px;font-size:14px;color:#16a34a;'>Giảm giá: -" . number_format($order->discount_amount, 0, ',', '.') . "₫</p>" : "") .
                        "<p style='margin:0 0 12px;font-size:14px;color:#666;'>Phí ship: " . ($order->shipping_fee > 0 ? number_format($order->shipping_fee, 0, ',', '.') . '₫' : 'Miễn phí') . "</p>
                        <p style='margin:0;font-size:20px;font-weight:700;color:#dc2626;'>Tổng: " . number_format($order->total, 0, ',', '.') . "₫</p>
                    </div>
                </div>
                
                <div style='background:#f8fafc;padding:16px;text-align:center;border-top:1px solid #eee;'>
                    <p style='margin:0;font-size:12px;color:#999;'>© " . date('Y') . " {$appName} - Cảm ơn bạn đã tin tưởng!</p>
                </div>
            </div>
        </body>
        </html>";
    }
}