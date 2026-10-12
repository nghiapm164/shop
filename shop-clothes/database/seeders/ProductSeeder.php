<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Size;
use App\Support\ProductAttributes;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * Tạo 100 sản phẩm thể thao với dữ liệu cố định (không ngẫu nhiên) để mỗi lần seed
 * đều cho kết quả giống nhau – chatbot AI và các kịch bản demo luôn ổn định.
 * Mỗi sản phẩm có đủ: danh mục, thương hiệu, giới tính, môn thể thao, mục đích sử dụng,
 * kiểu dáng, giá/giá khuyến mãi, mô tả, SEO, biến thể (size × màu, tồn kho) và ảnh theo màu.
 */
class ProductSeeder extends Seeder
{
    // Bộ size dùng chung
    private const MEN = ['S', 'M', 'L', 'XL', 'XXL'];
    private const MEN_PLUS = ['S', 'M', 'L', 'XL', 'XXL', 'XXXL'];
    private const WOMEN = ['XS', 'S', 'M', 'L', 'XL'];
    private const UNISEX = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];
    private const SHOE_MEN = ['39', '40', '41', '42', '43', '44', '45'];
    private const SHOE_WOMEN = ['36', '37', '38', '39', '40'];
    private const SHOE_UNISEX = ['37', '38', '39', '40', '41', '42', '43'];
    private const FREE = ['FREE'];

    public function run(): void
    {
        $categories = Category::with('parent')->get()->keyBy('name');
        $brands = Brand::pluck('id', 'name');
        $sizes = Size::pluck('id', 'code');
        $colors = Color::pluck('id', 'name');

        if ($categories->isEmpty() || $brands->isEmpty() || $sizes->isEmpty() || $colors->isEmpty()) {
            return;
        }

        foreach ($this->catalog() as $index => $row) {
            [$name, $categoryName, $brandName, $gender, $sport, $purpose, $style, $price, $salePrice, $colorNames, $sizeCodes] = $row;

            $category = $categories[$categoryName] ?? throw new RuntimeException("Không tìm thấy danh mục: {$categoryName}");
            $brandId = $brands[$brandName] ?? throw new RuntimeException("Không tìm thấy thương hiệu: {$brandName}");
            $kind = $this->kind($category);
            $sku = 'SPT-' . str_pad((string) ($index + 1), 6, '0', STR_PAD_LEFT);

            $product = Product::updateOrCreate(['sku' => $sku], [
                'name' => $name,
                'slug' => Str::slug($name),
                'short_description' => $this->shortDescription($kind, $gender, $sport, $purpose, $style),
                'description' => $this->description($kind, $name, $categoryName, $brandName, $gender, $sport, $purpose, $style),
                'category_id' => $category->id,
                'brand_id' => $brandId,
                'gender' => $gender,
                'sport' => $sport,
                'purpose' => $purpose,
                'style' => $style,
                'price' => $price,
                'sale_price' => $salePrice,
                'is_featured' => $index % 8 === 0,
                'is_active' => true,
                'view_count' => ($index * 73) % 950 + 30,
                'meta_title' => Str::limit($name . ' | ' . $brandName, 57),
                'meta_description' => Str::limit('Mua ' . $name . ' chính hãng ' . $brandName . ' tại MODESPORT. Giao hàng toàn quốc, đổi trả trong 7 ngày.', 157),
            ]);

            // Ngày tạo xen kẽ giữa các danh mục để mục "Hàng mới về" có đủ loại sản phẩm
            $product->forceFill(['created_at' => now()->subDays(($index * 37) % 100)->subMinutes($index)])->save();

            $this->syncVariants($product->id, $index, $kind, $sizeCodes, $colorNames, $sizes, $colors);

            ProductImage::where('product_id', $product->id)->delete();
            ProductImage::insert($this->imageRows($product->id, $sku, $name, $colorNames, $colors));
        }

        $this->classifyOtherProducts();
    }

    /**
     * Cập nhật biến thể theo (size, màu) thay vì xóa hết rồi tạo lại: order_items tham chiếu
     * product_variant_id (cascade) nên xóa biến thể sẽ làm mất chi tiết các đơn hàng đã có.
     */
    private function syncVariants(int $productId, int $index, string $kind, array $sizeCodes, array $colorNames, Collection $sizes, Collection $colors): void
    {
        $keepIds = [];

        foreach (array_values($sizeCodes) as $sizeIndex => $sizeCode) {
            foreach (array_values($colorNames) as $colorIndex => $colorName) {
                if (!isset($sizes[$sizeCode], $colors[$colorName])) {
                    throw new RuntimeException("Thiếu size {$sizeCode} hoặc màu {$colorName} – hãy chạy SizeSeeder/ColorSeeder trước.");
                }

                // Một vài biến thể hết hàng cho giống thực tế
                $stock = ($index + $sizeIndex * 3 + $colorIndex * 5) % 23 === 0
                    ? 0
                    : 5 + ($index * 7 + $sizeIndex * 5 + $colorIndex * 3) % 46;

                $keepIds[] = ProductVariant::updateOrCreate(
                    ['product_id' => $productId, 'size_id' => $sizes[$sizeCode], 'color_id' => $colors[$colorName]],
                    [
                        'stock_quantity' => $stock,
                        'additional_price' => $kind === 'apparel' && in_array($sizeCode, ['XXL', 'XXXL'], true) ? 20000 : 0,
                    ]
                )->id;
            }
        }

        // Biến thể cũ không còn trong danh mục: xóa nếu chưa có đơn hàng, ngược lại chỉ cho hết hàng
        $stale = ProductVariant::where('product_id', $productId)->whereNotIn('id', $keepIds);
        (clone $stale)->whereDoesntHave('orderItems')->delete();
        (clone $stale)->update(['stock_quantity' => 0]);
    }

    private function imageRows(int $productId, string $sku, string $name, array $colorNames, Collection $colors): array
    {
        $now = now();
        $rows = [];
        $sortOrder = 1;

        foreach (array_values($colorNames) as $colorIndex => $colorName) {
            foreach (['main', 'detail'] as $angle) {
                $rows[] = [
                    'product_id' => $productId,
                    'color_id' => $colors[$colorName],
                    'image_path' => 'https://picsum.photos/seed/' . md5("{$sku}-{$colorName}-{$angle}") . '/900/1200',
                    'alt_text' => $name . ' - ' . ProductAttributes::colorVi($colorName) . ($angle === 'detail' ? ' (chi tiết)' : ''),
                    'sort_order' => $sortOrder++,
                    'is_primary' => $colorIndex === 0 && $angle === 'main',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        return $rows;
    }

    /**
     * Gán thuộc tính cho sản phẩm không thuộc danh mục trên (vd: do admin tạo trước đây).
     */
    private function classifyOtherProducts(): void
    {
        Product::with('category.parent')
            ->whereNull('gender')
            ->chunkById(100, function ($products) {
                foreach ($products as $product) {
                    $product->update(ProductAttributes::infer($product));
                }
            });
    }

    /** apparel | shoes | accessory | equipment – dựa vào danh mục */
    private function kind(Category $category): string
    {
        if (in_array($category->slug, ['tatvo', 'khan-tap'], true)) {
            return 'accessory';
        }

        $root = $category->parent ?? $category;

        return match ($root->slug) {
            'giay-the-thao' => 'shoes',
            'thiet-bi-tap' => 'equipment',
            'phu-kien' => 'accessory',
            default => 'apparel',
        };
    }

    private function shortDescription(string $kind, string $gender, ?string $sport, string $purpose, ?string $style): string
    {
        $parts = [];
        $parts[] = $gender === 'unisex' ? 'Unisex – dùng cho cả nam và nữ' : 'Dành cho ' . mb_strtolower(ProductAttributes::label('gender', $gender));
        if ($sport) {
            $parts[] = 'môn ' . mb_strtolower(ProductAttributes::label('sport', $sport));
        }
        $parts[] = mb_strtolower(ProductAttributes::label('purpose', $purpose));
        if ($style) {
            $parts[] = 'form ' . mb_strtolower(ProductAttributes::label('style', $style));
        }

        $feature = match ($kind) {
            'shoes' => 'Đế êm, bám tốt, nâng đỡ bàn chân.',
            'equipment' => 'Bền bỉ, an toàn, dễ sử dụng tại nhà và phòng tập.',
            'accessory' => 'Nhỏ gọn, tiện dụng, thấm hút tốt và bền đẹp.',
            default => 'Vải thoáng khí, thấm hút mồ hôi nhanh, co giãn 4 chiều.',
        };

        return Str::ucfirst(implode(', ', $parts)) . '. ' . $feature;
    }

    private function description(string $kind, string $name, string $category, string $brand, string $gender, ?string $sport, string $purpose, ?string $style): string
    {
        $target = $gender === 'unisex' ? 'cả nam và nữ' : mb_strtolower(ProductAttributes::label('gender', $gender));
        $sportText = $sport ? ' môn ' . mb_strtolower(ProductAttributes::label('sport', $sport)) : ' thể thao';
        $use = match ($kind) {
            'shoes' => 'mang',
            'apparel' => 'mặc',
            default => 'sử dụng',
        };
        $purposeText = match ($purpose) {
            'thi-dau' => 'thi đấu, giúp bạn tự tin bứt phá thành tích',
            'hang-ngay' => "{$use} hằng ngày: dạo phố, đi học hay đi làm đều phù hợp",
            default => 'tập luyện thường xuyên với cường độ từ nhẹ đến cao',
        };

        $sentences = [
            "{$name} là sản phẩm{$sportText} chính hãng {$brand} dành cho {$target}.",
            'Sản phẩm được làm từ ' . $this->material($kind, mb_strtolower($name . ' ' . $category)) . ", phù hợp để {$purposeText}.",
        ];
        if ($style) {
            $sentences[] = 'Thiết kế ' . mb_strtolower(ProductAttributes::label('style', $style)) . ', tôn dáng và thoải mái khi vận động.';
        }
        $sentences[] = match ($kind) {
            'shoes' => 'Lưu ý: nên chọn đúng size chân thường mang; nếu chân bè có thể tăng nửa size.',
            'equipment' => 'Bảo quản nơi khô ráo, tránh ánh nắng trực tiếp để tăng tuổi thọ sản phẩm.',
            'accessory' => 'Giặt hoặc lau sạch sau khi sử dụng để giữ sản phẩm luôn mới.',
            default => 'Hướng dẫn giặt: giặt máy ở chế độ nhẹ, không dùng thuốc tẩy, phơi nơi thoáng mát.',
        };

        return implode(' ', $sentences);
    }

    /** Chất liệu theo từ khóa trong tên/danh mục sản phẩm (từ khóa '' là mặc định) */
    private function material(string $kind, string $text): string
    {
        $rules = [
            'shoes' => [
                'đá bóng' => 'upper da tổng hợp ôm chân và đế đinh bám sân',
                'bóng rổ' => 'thân giày cổ cao chắc chắn và đệm khí ở gót giảm chấn',
                'gym' => 'upper da tổng hợp và đế cao su phẳng giúp đứng vững khi nâng tạ',
                'training' => 'upper da tổng hợp và đế cao su phẳng giúp đứng vững khi nâng tạ',
                '' => 'upper vải dệt knit thoáng khí và đế giữa EVA siêu nhẹ',
            ],
            'accessory' => [
                'tất' => 'sợi cotton pha spandex mềm, thấm hút và kháng khuẩn',
                'khăn' => 'vải microfiber siêu thấm, nhanh khô',
                'túi' => 'vải dù polyester chống thấm nước',
                'balo' => 'vải dù polyester chống thấm nước',
                'băng' => 'sợi cotton dệt kim co giãn, thấm mồ hôi',
                '' => 'vải polyester thoáng khí, bền màu',
            ],
            'equipment' => [
                'tpe' => 'nhựa TPE an toàn, không mùi, chống trượt hai mặt',
                'thảm' => 'cao su tự nhiên bám sàn, đàn hồi tốt',
                'dây nhảy' => 'dây cáp thép bọc PVC và tay cầm chống trượt',
                'cao su' => 'thép đặc bọc cao su chống trầy sàn',
                '' => 'thép cao cấp sơn tĩnh điện, tay cầm chống trượt',
            ],
            'apparel' => [
                'nỉ' => 'vải nỉ bông cotton ấm áp, mềm mại',
                'hoodie' => 'vải nỉ bông cotton ấm áp, mềm mại',
                'giữ nhiệt' => 'vải giữ nhiệt co giãn, ôm sát cơ thể',
                'khoác gió' => 'vải dù chống gió, trượt nước nhẹ',
                'packable' => 'vải dù chống gió, trượt nước nhẹ',
                'lightweight' => 'vải dù siêu nhẹ chống gió',
                'track jacket' => 'vải polyester dệt kim mịn, đứng form',
                'mesh' => 'vải mesh lưới thoáng khí',
                'ba lỗ' => 'vải mesh lưới thoáng khí',
                'tank' => 'vải mesh lưới thoáng khí',
                'legging' => 'vải thun lạnh poly-spandex co giãn 4 chiều',
                'bra' => 'vải thun lạnh poly-spandex co giãn 4 chiều',
                'yoga' => 'vải thun lạnh poly-spandex co giãn 4 chiều',
                'lót' => 'sợi microfiber co giãn, kháng khuẩn',
                'polo' => 'vải cá sấu cotton pha polyester thoáng mát',
                '' => 'vải polyester thun lạnh thấm hút mồ hôi nhanh',
            ],
        ][$kind];

        foreach ($rules as $keyword => $material) {
            if ($keyword === '' || str_contains($text, $keyword)) {
                return $material;
            }
        }

        return end($rules);
    }

    /**
     * [tên, danh mục, thương hiệu, giới tính, môn, mục đích, kiểu dáng, giá, giá KM, màu, size]
     */
    private function catalog(): array
    {
        return [
            // ===== Áo bóng đá =====
            ['Áo bóng đá nam Strike Pro', 'Áo bóng đá', 'Umbro', 'nam', 'bong-da', 'thi-dau', 'athletic', 259000, null, ['Red', 'White', 'Black'], self::MEN],
            ['Áo bóng đá nam Club Home 2026', 'Áo bóng đá', 'Kappa', 'nam', 'bong-da', 'thi-dau', 'regular', 289000, 239000, ['Blue', 'White'], ['M', 'L', 'XL']],
            ['Áo bóng đá nam tập luyện DryFit', 'Áo bóng đá', 'Nike', 'nam', 'bong-da', 'tap-luyen', 'regular', 199000, null, ['Black', 'Yellow', 'Orange'], self::MEN],
            ['Áo bóng đá nam Elite Match', 'Áo bóng đá', 'Adidas', 'nam', 'bong-da', 'thi-dau', 'slim', 449000, null, ['White', 'Navy'], ['S', 'M', 'L', 'XL']],
            ['Áo thủ môn nam dài tay Keeper Grip', 'Áo bóng đá', 'Puma', 'nam', 'bong-da', 'thi-dau', 'regular', 359000, 319000, ['Green', 'Yellow'], ['M', 'L', 'XL', 'XXL']],
            ['Áo bóng đá nam Club Away 2026', 'Áo bóng đá', 'Kappa', 'nam', 'bong-da', 'thi-dau', 'regular', 289000, null, ['Navy', 'Orange'], self::MEN],
            ['Áo bóng đá nam Retro Classic 90', 'Áo bóng đá', 'Umbro', 'nam', 'bong-da', 'hang-ngay', 'relaxed', 329000, 279000, ['Green', 'White'], self::MEN],
            ['Áo bóng đá nữ Venus', 'Áo bóng đá', 'Nike', 'nu', 'bong-da', 'thi-dau', 'slim', 229000, null, ['White', 'Red'], ['XS', 'S', 'M', 'L']],
            ['Áo bóng đá nữ tập luyện Aurora', 'Áo bóng đá', 'Adidas', 'nu', 'bong-da', 'tap-luyen', 'regular', 199000, 169000, ['Purple', 'Black'], self::WOMEN],

            // ===== Áo chạy bộ =====
            ['Áo chạy bộ nam AirLite', 'Áo chạy bộ', 'Nike', 'nam', 'chay-bo', 'tap-luyen', 'slim', 249000, null, ['Black', 'White', 'Blue'], self::MEN],
            ['Áo chạy bộ nam ba lỗ Marathon Tank', 'Áo chạy bộ', 'Asics', 'nam', 'chay-bo', 'thi-dau', 'athletic', 189000, 159000, ['Black', 'Orange', 'Gray'], ['S', 'M', 'L', 'XL']],
            ['Áo chạy bộ nam dài tay giữ nhiệt ThermoRun', 'Áo chạy bộ', 'New Balance', 'nam', 'chay-bo', 'tap-luyen', 'slim', 389000, null, ['Black', 'Navy'], ['M', 'L', 'XL', 'XXL']],
            ['Áo chạy bộ nam Pace Mesh', 'Áo chạy bộ', 'RunFast', 'nam', 'chay-bo', 'tap-luyen', 'regular', 219000, null, ['Gray', 'Blue', 'Red'], self::MEN],
            ['Áo chạy bộ nam phản quang NightRun', 'Áo chạy bộ', 'RunFast', 'nam', 'chay-bo', 'tap-luyen', 'regular', 279000, 239000, ['Black', 'Yellow'], self::MEN],
            ['Áo chạy bộ nam Trail Explorer', 'Áo chạy bộ', 'Asics', 'nam', 'chay-bo', 'tap-luyen', 'relaxed', 329000, null, ['Green', 'Gray'], ['M', 'L', 'XL', 'XXL']],
            ['Áo chạy bộ nữ Breeze', 'Áo chạy bộ', 'Nike', 'nu', 'chay-bo', 'tap-luyen', 'slim', 229000, null, ['White', 'Purple', 'Black'], ['XS', 'S', 'M', 'L']],
            ['Áo chạy bộ nữ croptop Swift', 'Áo chạy bộ', 'Puma', 'nu', 'chay-bo', 'tap-luyen', 'slim', 199000, null, ['Black', 'Orange'], self::WOMEN],
            ['Áo chạy bộ nữ dài tay Glow', 'Áo chạy bộ', 'New Balance', 'nu', 'chay-bo', 'tap-luyen', 'regular', 289000, 249000, ['Purple', 'Navy'], self::WOMEN],
            ['Áo chạy bộ unisex Marathon Finisher', 'Áo chạy bộ', 'RunFast', 'unisex', 'chay-bo', 'thi-dau', 'regular', 179000, null, ['White', 'Blue'], self::UNISEX],

            // ===== Áo thun tập gym / yoga =====
            ['Áo tập gym nam Power Tee', 'Áo thun tập gym', 'GymCore', 'nam', 'gym', 'tap-luyen', 'athletic', 199000, null, ['Black', 'Gray', 'White'], self::MEN],
            ['Áo ba lỗ gym nam Iron Tank', 'Áo thun tập gym', 'Under Armour', 'nam', 'gym', 'tap-luyen', 'slim', 159000, null, ['Black', 'White', 'Red'], self::MEN],
            ['Áo tập gym nam dài tay Compression Pro', 'Áo thun tập gym', 'Under Armour', 'nam', 'gym', 'tap-luyen', 'slim', 299000, 259000, ['Black', 'Navy'], self::MEN],
            ['Áo tập gym nam oversize Pump Cover', 'Áo thun tập gym', 'GymCore', 'nam', 'gym', 'tap-luyen', 'relaxed', 239000, null, ['Gray', 'Black', 'Green'], self::MEN_PLUS],
            ['Áo polo thể thao nam Active', 'Áo thun tập gym', 'Reebok', 'nam', 'gym', 'hang-ngay', 'regular', 269000, null, ['Navy', 'White'], self::MEN],
            ['Áo bra tập gym nữ Core Support', 'Áo thun tập gym', 'Nike', 'nu', 'gym', 'tap-luyen', 'slim', 249000, null, ['Black', 'Purple'], ['XS', 'S', 'M', 'L']],
            ['Áo tank top gym nữ Flex', 'Áo thun tập gym', 'Reebok', 'nu', 'gym', 'tap-luyen', 'slim', 179000, 149000, ['White', 'Black', 'Red'], self::WOMEN],
            ['Áo thun tập gym nữ Daily Move', 'Áo thun tập gym', 'SportWear Pro', 'nu', 'gym', 'hang-ngay', 'relaxed', 189000, null, ['Gray', 'Purple', 'White'], self::WOMEN],
            ['Áo yoga nữ Lotus', 'Áo thun tập gym', 'Fila', 'nu', 'yoga', 'tap-luyen', 'relaxed', 239000, null, ['Purple', 'White'], ['XS', 'S', 'M', 'L']],
            ['Áo yoga nữ dài tay Balance', 'Áo thun tập gym', 'SportWear Pro', 'nu', 'yoga', 'tap-luyen', 'slim', 279000, null, ['Navy', 'Black'], self::WOMEN],

            // ===== Áo thể thao các môn khác =====
            ['Áo bóng rổ nam Dunk Jersey', 'Áo thể thao', 'Nike', 'nam', 'bong-ro', 'thi-dau', 'relaxed', 269000, null, ['Red', 'Blue', 'Black'], self::MEN],
            ['Áo bóng rổ nam Shooting Shirt', 'Áo thể thao', 'Under Armour', 'nam', 'bong-ro', 'tap-luyen', 'relaxed', 299000, null, ['White', 'Black'], self::MEN],
            ['Áo cầu lông nam SmashPro', 'Áo thể thao', 'Fila', 'nam', 'cau-long', 'thi-dau', 'regular', 219000, null, ['White', 'Blue', 'Yellow'], self::MEN],
            ['Áo cầu lông nữ Feather', 'Áo thể thao', 'Fila', 'nu', 'cau-long', 'tap-luyen', 'slim', 199000, null, ['Yellow', 'White'], ['XS', 'S', 'M', 'L']],
            ['Áo polo tennis nam Ace', 'Áo thể thao', 'Champion', 'nam', 'tennis', 'hang-ngay', 'regular', 349000, 299000, ['White', 'Navy'], ['M', 'L', 'XL', 'XXL']],
            ['Áo tennis nữ Court Queen', 'Áo thể thao', 'Fila', 'nu', 'tennis', 'thi-dau', 'slim', 319000, null, ['White', 'Purple'], self::WOMEN],
            ['Áo thun thể thao nam Everyday', 'Áo thể thao', 'Champion', 'nam', 'gym', 'hang-ngay', 'regular', 149000, null, ['White', 'Black', 'Gray', 'Navy'], self::MEN_PLUS],

            // ===== Áo khoác thể thao =====
            ['Áo khoác gió chạy bộ nam WindShield', 'Áo khoác thể thao', 'Adidas', 'nam', 'chay-bo', 'hang-ngay', 'regular', 459000, 399000, ['Black', 'Navy'], ['M', 'L', 'XL', 'XXL']],
            ['Hoodie thể thao unisex Daily', 'Áo khoác thể thao', 'Champion', 'unisex', 'gym', 'hang-ngay', 'relaxed', 399000, null, ['Black', 'Gray', 'Navy'], self::UNISEX],
            ['Áo khoác bóng đá nam Track Jacket', 'Áo khoác thể thao', 'Adidas', 'nam', 'bong-da', 'tap-luyen', 'regular', 499000, 449000, ['Navy', 'Black'], self::MEN],
            ['Áo khoác chạy bộ nữ Lightweight', 'Áo khoác thể thao', 'Nike', 'nu', 'chay-bo', 'hang-ngay', 'slim', 429000, null, ['Purple', 'Black'], self::WOMEN],
            ['Áo khoác nỉ tập gym nam Zip Up', 'Áo khoác thể thao', 'Puma', 'nam', 'gym', 'hang-ngay', 'regular', 459000, null, ['Gray', 'Black'], self::MEN],
            ['Áo khoác gió nữ Packable', 'Áo khoác thể thao', 'Kappa', 'nu', 'chay-bo', 'hang-ngay', 'relaxed', 389000, 339000, ['White', 'Orange'], self::WOMEN],

            // ===== Quần short =====
            ['Quần short bóng đá nam Pace', 'Quần short tập', 'Umbro', 'nam', 'bong-da', 'thi-dau', 'regular', 159000, null, ['Black', 'White', 'Navy', 'Red'], self::MEN],
            ['Quần short bóng đá nam Club 2026', 'Quần short tập', 'Kappa', 'nam', 'bong-da', 'thi-dau', 'regular', 179000, null, ['Blue', 'White'], self::MEN],
            ['Quần short chạy bộ nam 2 lớp Sprint', 'Quần short tập', 'Nike', 'nam', 'chay-bo', 'tap-luyen', 'athletic', 219000, null, ['Black', 'Gray', 'Navy'], self::MEN],
            ['Quần short chạy bộ nam Split 5 inch', 'Quần short tập', 'Asics', 'nam', 'chay-bo', 'thi-dau', 'slim', 179000, null, ['Black', 'Blue'], ['M', 'L', 'XL']],
            ['Quần short gym nam Flex', 'Quần short tập', 'GymCore', 'nam', 'gym', 'tap-luyen', 'regular', 189000, null, ['Black', 'Gray', 'Navy'], self::MEN],
            ['Quần short gym nam Power Lift', 'Quần short tập', 'Under Armour', 'nam', 'gym', 'tap-luyen', 'athletic', 239000, 199000, ['Black', 'Red'], self::MEN],
            ['Quần bóng rổ nam Court Short', 'Quần short tập', 'Nike', 'nam', 'bong-ro', 'thi-dau', 'relaxed', 229000, null, ['Red', 'Blue', 'Black'], self::MEN],
            ['Quần short cầu lông nam Rally', 'Quần short tập', 'Fila', 'nam', 'cau-long', 'thi-dau', 'regular', 169000, null, ['White', 'Black'], self::MEN],
            ['Quần short tennis nam Baseline', 'Quần short tập', 'Champion', 'nam', 'tennis', 'thi-dau', 'regular', 249000, null, ['White', 'Navy'], self::MEN],
            ['Quần short chạy bộ nữ Swift', 'Quần short tập', 'Nike', 'nu', 'chay-bo', 'tap-luyen', 'slim', 199000, null, ['Black', 'Purple'], ['XS', 'S', 'M', 'L']],
            ['Quần short gym nữ Biker', 'Quần short tập', 'Reebok', 'nu', 'gym', 'tap-luyen', 'slim', 189000, 159000, ['Black', 'Gray', 'Purple'], self::WOMEN],
            ['Quần váy tennis nữ Ace Skort', 'Quần short tập', 'Fila', 'nu', 'tennis', 'thi-dau', 'slim', 269000, null, ['White', 'Navy'], self::WOMEN],
            ['Quần short thể thao unisex Everyday', 'Quần short tập', 'Champion', 'unisex', 'gym', 'hang-ngay', 'relaxed', 159000, null, ['Black', 'Gray', 'Navy'], self::UNISEX],

            // ===== Quần dài / jogger / legging =====
            ['Quần jogger chạy bộ nam StreamFlex', 'Quần jogger', 'Adidas', 'nam', 'chay-bo', 'tap-luyen', 'regular', 329000, null, ['Black', 'Gray'], self::MEN],
            ['Quần jogger tập gym nam Urban', 'Quần jogger', 'GymCore', 'nam', 'gym', 'hang-ngay', 'relaxed', 289000, null, ['Black', 'Gray', 'Navy'], self::MEN],
            ['Quần dài bóng đá nam Training Pant', 'Quần jogger', 'Adidas', 'nam', 'bong-da', 'tap-luyen', 'slim', 349000, 299000, ['Black', 'Navy'], self::MEN],
            ['Quần legging giữ nhiệt nam Base Layer', 'Quần jogger', 'Under Armour', 'nam', 'chay-bo', 'tap-luyen', 'slim', 259000, null, ['Black'], self::MEN],
            ['Quần legging chạy bộ nữ Contour', 'Quần jogger', 'Nike', 'nu', 'chay-bo', 'tap-luyen', 'slim', 299000, 259000, ['Black', 'Navy'], self::WOMEN],
            ['Quần legging tập gym nữ Sculpt', 'Quần jogger', 'Under Armour', 'nu', 'gym', 'tap-luyen', 'slim', 279000, 239000, ['Black', 'Gray'], self::WOMEN],
            ['Quần yoga nữ Flow', 'Quần jogger', 'SportWear Pro', 'nu', 'yoga', 'tap-luyen', 'slim', 299000, null, ['Black', 'Navy', 'Purple'], ['XS', 'S', 'M', 'L']],
            ['Quần jogger nữ Cozy', 'Quần jogger', 'Puma', 'nu', 'gym', 'hang-ngay', 'relaxed', 279000, null, ['Gray', 'Black', 'Purple'], self::WOMEN],

            // ===== Quần nỉ =====
            ['Quần nỉ thể thao nam Classic', 'Quần nỉ', 'Champion', 'nam', 'gym', 'hang-ngay', 'regular', 299000, null, ['Gray', 'Black', 'Navy'], self::MEN_PLUS],
            ['Quần nỉ thể thao nữ Soft Fleece', 'Quần nỉ', 'Champion', 'nu', 'gym', 'hang-ngay', 'relaxed', 289000, 249000, ['Gray', 'Purple'], self::WOMEN],
            ['Quần nỉ ống suông unisex Street', 'Quần nỉ', 'StreetFit', 'unisex', 'gym', 'hang-ngay', 'relaxed', 319000, null, ['Black', 'Gray'], self::UNISEX],
            ['Quần nỉ bo gấu nam Winter Run', 'Quần nỉ', 'Puma', 'nam', 'chay-bo', 'hang-ngay', 'regular', 339000, null, ['Navy', 'Black'], self::MEN],

            // ===== Giày =====
            ['Giày chạy bộ nam CloudRun', 'Giày chạy bộ', 'Asics', 'nam', 'chay-bo', 'tap-luyen', null, 1290000, 1090000, ['Black', 'White'], self::SHOE_MEN],
            ['Giày chạy bộ nam Tempo Racer', 'Giày chạy bộ', 'Nike', 'nam', 'chay-bo', 'thi-dau', null, 1890000, null, ['Orange', 'White'], self::SHOE_MEN],
            ['Giày chạy bộ nữ Featherlight', 'Giày chạy bộ', 'New Balance', 'nu', 'chay-bo', 'tap-luyen', null, 1190000, null, ['White', 'Purple'], self::SHOE_WOMEN],
            ['Giày chạy bộ unisex Daily Trainer', 'Giày chạy bộ', 'RunFast', 'unisex', 'chay-bo', 'hang-ngay', null, 790000, 690000, ['Black', 'Gray', 'Blue'], self::SHOE_UNISEX],
            ['Giày tập gym nam Stable Lift', 'Giày tập gym', 'Reebok', 'nam', 'gym', 'tap-luyen', null, 1090000, null, ['Black', 'Red'], self::SHOE_MEN],
            ['Giày tập gym nữ Studio Flex', 'Giày tập gym', 'Reebok', 'nu', 'gym', 'tap-luyen', null, 890000, 790000, ['White', 'Purple'], self::SHOE_WOMEN],
            ['Giày training unisex CrossFit Pro', 'Giày tập gym', 'Under Armour', 'unisex', 'gym', 'tap-luyen', null, 1390000, null, ['Black', 'Gray'], self::SHOE_UNISEX],
            ['Giày bóng rổ nam Sky Dunk', 'Giày bóng rổ', 'Nike', 'nam', 'bong-ro', 'thi-dau', null, 1990000, 1690000, ['Red', 'Black', 'White'], self::SHOE_MEN],
            ['Giày bóng rổ nam Street Ball', 'Giày bóng rổ', 'Converse', 'nam', 'bong-ro', 'hang-ngay', null, 1290000, null, ['White', 'Black'], self::SHOE_MEN],
            ['Giày bóng rổ unisex Court Vision', 'Giày bóng rổ', 'Puma', 'unisex', 'bong-ro', 'tap-luyen', null, 1490000, null, ['Blue', 'White'], self::SHOE_UNISEX],
            ['Giày đá bóng sân cỏ nhân tạo nam Turf Strike', 'Giày thể thao', 'Adidas', 'nam', 'bong-da', 'thi-dau', null, 990000, 890000, ['Black', 'Orange', 'White'], self::SHOE_MEN],
            ['Giày đá bóng sân cỏ tự nhiên nam Elite FG', 'Giày thể thao', 'Nike', 'nam', 'bong-da', 'thi-dau', null, 1590000, null, ['Blue', 'Yellow'], self::SHOE_MEN],

            // ===== Phụ kiện =====
            ['Nón chạy bộ Air Cap', 'Nón thể thao', 'RunFast', 'unisex', 'chay-bo', 'tap-luyen', null, 159000, null, ['Black', 'White', 'Blue'], self::FREE],
            ['Nón lưỡi trai thể thao Classic', 'Nón thể thao', 'Champion', 'unisex', 'gym', 'hang-ngay', null, 189000, null, ['Black', 'Navy', 'Red'], self::FREE],
            ['Mũ tennis vành rộng Shade', 'Nón thể thao', 'Fila', 'unisex', 'tennis', 'tap-luyen', null, 199000, 169000, ['White', 'Yellow'], self::FREE],
            ['Túi trống tập gym Duffle 40L', 'Túi tập gym', 'GymCore', 'unisex', 'gym', 'tap-luyen', null, 459000, null, ['Black', 'Gray'], self::FREE],
            ['Balo thể thao chống nước Active 25L', 'Túi tập gym', 'Under Armour', 'unisex', 'gym', 'hang-ngay', null, 549000, 479000, ['Black', 'Navy'], self::FREE],
            ['Túi đựng giày bóng đá Boot Bag', 'Túi tập gym', 'Umbro', 'unisex', 'bong-da', 'tap-luyen', null, 149000, null, ['Black', 'Red'], self::FREE],
            ['Băng cổ tay thấm mồ hôi Pro Band (bộ 2 chiếc)', 'Băng cổ tay', 'Nike', 'unisex', 'tennis', 'tap-luyen', null, 89000, null, ['White', 'Black', 'Red'], self::FREE],
            ['Băng quấn cổ tay tập tạ Lift Wrap', 'Băng cổ tay', 'GymCore', 'unisex', 'gym', 'tap-luyen', null, 129000, null, ['Black', 'Red'], self::FREE],

            // ===== Đồ lót thể thao =====
            ['Quần lót boxer thể thao nam Dry (hộp 3 chiếc)', 'Quần lót thể thao', 'Under Armour', 'nam', 'gym', 'tap-luyen', 'slim', 299000, 249000, ['Black', 'Gray', 'Navy'], self::MEN],
            ['Quần lót thể thao nữ Seamless (hộp 3 chiếc)', 'Quần lót thể thao', 'Nike', 'nu', 'yoga', 'tap-luyen', 'slim', 229000, null, ['Black', 'Purple', 'White'], self::WOMEN],
            ['Tất chạy bộ cổ thấp Run Socks (3 đôi)', 'Tất/Vớ', 'Asics', 'unisex', 'chay-bo', 'tap-luyen', null, 129000, null, ['White', 'Black'], self::FREE],
            ['Tất bóng đá dài Club Socks', 'Tất/Vớ', 'Umbro', 'nam', 'bong-da', 'thi-dau', null, 99000, null, ['White', 'Red', 'Blue', 'Black'], self::FREE],
            ['Tất thể thao cổ cao Crew Socks (3 đôi)', 'Tất/Vớ', 'Champion', 'unisex', 'gym', 'hang-ngay', null, 119000, 99000, ['White', 'Gray', 'Black'], self::FREE],
            ['Khăn tập gym microfiber Cool Towel', 'Khăn tập', 'GymCore', 'unisex', 'gym', 'tap-luyen', null, 99000, null, ['Gray', 'Blue'], self::FREE],
            ['Khăn lạnh thể thao Ice Towel', 'Khăn tập', 'RunFast', 'unisex', 'chay-bo', 'tap-luyen', null, 79000, null, ['Blue', 'Green', 'Orange'], self::FREE],

            // ===== Thiết bị tập =====
            ['Tạ tay bọc cao su 5kg (cặp)', 'Tạ tay', 'GymCore', 'unisex', 'gym', 'tap-luyen', null, 390000, null, ['Black'], self::FREE],
            ['Tạ tay điều chỉnh 2-10kg', 'Tạ tay', 'SportWear Pro', 'unisex', 'gym', 'tap-luyen', null, 890000, 790000, ['Black', 'Red'], self::FREE],
            ['Dây nhảy tốc độ Speed Rope', 'Dây nhảy', 'Reebok', 'unisex', 'gym', 'tap-luyen', null, 129000, null, ['Black', 'Red'], self::FREE],
            ['Dây nhảy đếm số Smart Rope', 'Dây nhảy', 'SportWear Pro', 'unisex', 'gym', 'tap-luyen', null, 249000, 219000, ['Black', 'Blue'], self::FREE],
            ['Thảm yoga TPE chống trượt 6mm', 'Thảm yoga', 'SportWear Pro', 'unisex', 'yoga', 'tap-luyen', null, 299000, null, ['Purple', 'Blue', 'Green'], self::FREE],
            ['Thảm yoga cao su định tuyến 5mm', 'Thảm yoga', 'Reebok', 'unisex', 'yoga', 'tap-luyen', null, 549000, 469000, ['Black', 'Gray'], self::FREE],
        ];
    }
}
