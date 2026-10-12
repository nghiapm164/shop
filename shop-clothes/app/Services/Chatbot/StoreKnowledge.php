<?php

namespace App\Services\Chatbot;

use App\Models\Category;
use App\Models\Coupon;
use App\Support\ProductAttributes;

/**
 * Trả lời các câu hỏi chung về website. Mọi nội dung lấy từ config/chatbot.php
 * (đồng bộ với trang chính sách & logic đặt hàng) hoặc từ database.
 */
class StoreKnowledge
{
    public function answer(string $topic): ?array
    {
        return match ($topic) {
            'promo' => $this->promo(),
            'catalog' => $this->catalog(),
            'size_chart' => null, // xử lý bởi SizeAdvisor
            default => $this->faq($topic),
        };
    }

    private function faq(string $topic): ?array
    {
        $text = config("chatbot.faq.{$topic}");
        if (!$text) {
            return null;
        }

        $link = config("chatbot.faq_links.{$topic}");

        return [
            'text' => $text,
            'link' => $link ? ['label' => $link['label'], 'url' => route($link['route'])] : null,
        ];
    }

    private function promo(): array
    {
        $coupons = Coupon::valid()
            ->where(fn ($q) => $q->whereNull('usage_limit')->orWhereColumn('used_count', '<', 'usage_limit'))
            ->orderByDesc('value')
            ->limit(5)
            ->get();

        if ($coupons->isEmpty()) {
            return ['text' => 'Hiện shop chưa có mã giảm giá nào đang áp dụng. Mình gửi bạn các sản phẩm đang được giảm giá nhé:', 'link' => null];
        }

        $lines = ['Các mã giảm giá đang áp dụng:'];
        foreach ($coupons as $coupon) {
            $value = $coupon->type === 'percent'
                ? rtrim(rtrim(number_format((float) $coupon->value, 2, ',', '.'), '0'), ',') . '%'
                : ChatbotService::money((int) $coupon->value);
            $line = "- **{$coupon->code}**: giảm {$value}";
            if ((float) $coupon->min_order_amount > 0) {
                $line .= ' cho đơn từ ' . ChatbotService::money((int) $coupon->min_order_amount);
            }
            if ($coupon->type === 'percent' && (float) $coupon->max_discount > 0) {
                $line .= ', tối đa ' . ChatbotService::money((int) $coupon->max_discount);
            }
            $line .= ' (HSD: ' . $coupon->end_date?->format('d/m/Y') . ')';
            $lines[] = $line;
        }
        $lines[] = 'Nhập mã ở bước thanh toán để được giảm giá. Dưới đây là một số sản phẩm đang sale:';

        return ['text' => implode("\n", $lines), 'link' => null];
    }

    private function catalog(): array
    {
        $roots = Category::active()->whereNull('parent_id')->with(['children' => fn ($q) => $q->active()])->orderBy('sort_order')->get();

        $lines = ['Shop chuyên trang phục và phụ kiện thể thao, gồm các danh mục:'];
        foreach ($roots as $root) {
            $children = $root->children->pluck('name')->implode(', ');
            $lines[] = "- **{$root->name}**" . ($children ? ": {$children}" : '');
        }
        $lines[] = 'Các môn thể thao: ' . implode(', ', ProductAttributes::SPORTS) . '.';

        return ['text' => implode("\n", $lines), 'link' => ['label' => 'Xem tất cả sản phẩm', 'url' => route('shop.index')]];
    }
}
