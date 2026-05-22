<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public Order $order;
    public string $oldStatus;
    public string $newStatus;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order, string $oldStatus, string $newStatus)
    {
        $this->order = $order;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Cập nhật đơn hàng ' . $this->order->order_code . ' - ' . Order::getStatusLabel($this->newStatus),
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
        $appName = config('app.name', 'ShopGym');
        $oldLabel = Order::getStatusLabel($this->oldStatus);
        $newLabel = Order::getStatusLabel($this->newStatus);
        $recipientName = $order->shipping_address['recipient_name'] ?? $order->user->name ?? 'N/A';

        // Status color
        $statusColor = match ($this->newStatus) {
            'confirmed' => '#0ea5e9',
            'processing' => '#f59e0b',
            'shipped' => '#8b5cf6',
            'delivered' => '#10b981',
            'cancelled' => '#ef4444',
            'refunded' => '#6b7280',
            default => '#3b82f6',
        };

        // Status icon
        $statusIcon = match ($this->newStatus) {
            'confirmed' => '✅',
            'processing' => '📦',
            'shipped' => '🚚',
            'delivered' => '🎉',
            'cancelled' => '❌',
            'refunded' => '💰',
            default => '📋',
        };

        $statusMessage = match ($this->newStatus) {
            'confirmed' => 'Đơn hàng của bạn đã được xác nhận và sẽ sớm được chuẩn bị.',
            'processing' => 'Đơn hàng của bạn đang được đóng gói cẩn thận.',
            'shipped' => 'Đơn hàng đã được giao cho đơn vị vận chuyển. Dự kiến giao trong 2-5 ngày.',
            'delivered' => 'Đơn hàng đã giao thành công. Cảm ơn bạn đã mua hàng!',
            'cancelled' => 'Đơn hàng đã bị hủy. Nếu bạn đã thanh toán, tiền sẽ được hoàn trả.',
            'refunded' => 'Tiền đã được hoàn trả. Vui lòng kiểm tra tài khoản.',
            default => "Trạng thái đơn hàng đã được cập nhật từ {$oldLabel} sang {$newLabel}.",
        };

        $viewOrderUrl = route('client.orders.show', $order->id);

        return "
        <!DOCTYPE html>
        <html>
        <head><meta charset='UTF-8'></head>
        <body style='font-family:Arial,sans-serif;background:#f4f4f4;margin:0;padding:20px;'>
            <div style='max-width:600px;margin:0 auto;background:#fff;border-radius:8px;overflow:hidden;'>
                <div style='background:{$statusColor};padding:24px;text-align:center;'>
                    <h1 style='color:#fff;margin:0;font-size:24px;'>🏋️ {$appName}</h1>
                    <p style='color:rgba(255,255,255,0.8);margin:8px 0 0;font-size:14px;'>Cập nhật đơn hàng</p>
                </div>
                
                <div style='padding:24px;'>
                    <div style='text-align:center;padding:20px 0;'>
                        <span style='font-size:48px;'>{$statusIcon}</span>
                        <h2 style='color:#111;font-size:20px;margin:16px 0 8px;'>{$newLabel}</h2>
                        <p style='color:#666;font-size:14px;line-height:1.6;margin:0;'>{$statusMessage}</p>
                    </div>
                    
                    <div style='background:#f8fafc;border-radius:8px;padding:16px;margin:20px 0;'>
                        <p style='margin:0 0 8px;font-size:13px;color:#666;'><strong>Mã đơn:</strong> #{$order->order_code}</p>
                        <p style='margin:0 0 8px;font-size:13px;color:#666;'><strong>Tổng tiền:</strong> " . number_format($order->total, 0, ',', '.') . "₫</p>
                        <p style='margin:0;font-size:13px;color:#666;'><strong>Trạng thái:</strong> 
                            <span style='display:inline-block;padding:2px 10px;border-radius:12px;background:{$statusColor};color:#fff;font-size:12px;font-weight:600;'>{$newLabel}</span>
                        </p>
                    </div>
                    
                    <div style='text-align:center;padding:20px 0;'>
                        <a href='{$viewOrderUrl}' style='display:inline-block;padding:12px 32px;background:#dc2626;color:#fff;border-radius:8px;font-weight:600;font-size:14px;text-decoration:none;'>
                            Xem đơn hàng
                        </a>
                    </div>
                </div>
                
                <div style='background:#f8fafc;padding:16px;text-align:center;border-top:1px solid #eee;'>
                    <p style='margin:0;font-size:12px;color:#999;'>© " . date('Y') . " {$appName}</p>
                </div>
            </div>
        </body>
        </html>";
    }
}