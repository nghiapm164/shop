<?php

namespace App\Support;

use App\Models\Product;
use Illuminate\Support\Str;

/**
 * Danh sách giá trị chuẩn cho các thuộc tính phục vụ tư vấn sản phẩm
 * (giới tính, môn thể thao, mục đích sử dụng, kiểu dáng).
 * Dùng chung cho form admin, validate và chatbot AI.
 */
class ProductAttributes
{
    public const GENDERS = [
        'nam' => 'Nam',
        'nu' => 'Nữ',
        'unisex' => 'Unisex',
    ];

    public const SPORTS = [
        'bong-da' => 'Bóng đá',
        'chay-bo' => 'Chạy bộ',
        'gym' => 'Gym / Thể hình',
        'yoga' => 'Yoga',
        'bong-ro' => 'Bóng rổ',
        'cau-long' => 'Cầu lông',
        'tennis' => 'Tennis',
    ];

    public const PURPOSES = [
        'tap-luyen' => 'Tập luyện',
        'thi-dau' => 'Thi đấu',
        'hang-ngay' => 'Mặc hằng ngày / dạo phố',
    ];

    public const STYLES = [
        'slim' => 'Ôm body (Slim Fit)',
        'regular' => 'Vừa vặn (Regular Fit)',
        'athletic' => 'Thể thao (Athletic Fit)',
        'relaxed' => 'Rộng rãi (Relaxed/Oversize)',
    ];

    /** Tên màu trong bảng colors => tên tiếng Việt */
    public const COLOR_VI = [
        'Black' => 'Đen',
        'White' => 'Trắng',
        'Red' => 'Đỏ',
        'Blue' => 'Xanh dương',
        'Green' => 'Xanh lá',
        'Yellow' => 'Vàng',
        'Gray' => 'Xám',
        'Navy' => 'Xanh navy',
        'Orange' => 'Cam',
        'Purple' => 'Tím',
    ];

    public static function label(string $group, ?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $map = match ($group) {
            'gender' => self::GENDERS,
            'sport' => self::SPORTS,
            'purpose' => self::PURPOSES,
            'style' => self::STYLES,
            default => [],
        };

        return $map[$value] ?? $value;
    }

    public static function colorVi(?string $name): ?string
    {
        return $name === null ? null : (self::COLOR_VI[$name] ?? $name);
    }

    /**
     * Suy luận thuộc tính từ tên / danh mục cho các sản phẩm cũ chưa được gán.
     */
    public static function infer(Product $product): array
    {
        $name = Str::lower(Str::ascii($product->name));
        $text = Str::lower(Str::ascii(implode(' ', [
            $product->name,
            $product->category?->name,
            $product->category?->parent?->name,
        ])));

        $gender = match (true) {
            (bool) preg_match('/\b(nu|women|female)\b/', $text) => 'nu',
            (bool) preg_match('/\b(nam|men|male)\b/', $text) => 'nam',
            default => 'unisex',
        };

        // Ưu tiên môn thể thao trong tên sản phẩm, sau đó mới đến danh mục
        $sport = self::inferSport($name) ?? self::inferSport($text);

        $style = match (true) {
            str_contains($text, 'slim') => 'slim',
            str_contains($text, 'athletic') => 'athletic',
            str_contains($text, 'relaxed') || str_contains($text, 'oversize') => 'relaxed',
            str_contains($text, 'regular') => 'regular',
            default => null,
        };

        $purpose = str_contains($text, 'thi dau') ? 'thi-dau' : 'tap-luyen';

        return compact('gender', 'sport', 'purpose', 'style');
    }

    private static function inferSport(string $text): ?string
    {
        return match (true) {
            str_contains($text, 'bong da') || str_contains($text, 'football') => 'bong-da',
            str_contains($text, 'chay bo') || str_contains($text, 'running') => 'chay-bo',
            str_contains($text, 'bong ro') => 'bong-ro',
            str_contains($text, 'yoga') => 'yoga',
            str_contains($text, 'cau long') => 'cau-long',
            str_contains($text, 'tennis') => 'tennis',
            str_contains($text, 'gym') || str_contains($text, 'ta tay') || str_contains($text, 'the hinh') => 'gym',
            default => null,
        };
    }
}
