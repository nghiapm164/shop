<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Size;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $totalProducts = 1000;

        $categories = Category::where('is_active', true)->orderBy('id')->get();
        $brands = Brand::where('is_active', true)->orderBy('id')->get();
        $sizes = Size::orderBy('id')->get();
        $colors = Color::orderBy('id')->get();

        if ($categories->isEmpty() || $brands->isEmpty() || $sizes->isEmpty() || $colors->isEmpty()) {
            return;
        }

        $collections = ['Core', 'Active', 'Motion', 'Performance', 'Athlete', 'Urban', 'Dynamic', 'Prime'];
        $fitStyles = ['Regular Fit', 'Slim Fit', 'Athletic Fit', 'Relaxed Fit'];
        $materials = ['vải thoáng khí', 'chất liệu co giãn 4 chiều', 'vải thấm hút mồ hôi', 'sợi tổng hợp bền nhẹ'];
        $highlights = ['thoải mái suốt ngày', 'tối ưu vận động', 'giữ form tốt', 'phù hợp tập luyện cường độ cao'];

        for ($index = 0; $index < $totalProducts; $index++) {
            $category = $categories[$index % $categories->count()];
            $brand = $brands[random_int(0, $brands->count() - 1)];
            $profile = $this->getCategoryProfile($category->name);

            $baseName = $profile['names'][random_int(0, count($profile['names']) - 1)];
            $collection = $collections[random_int(0, count($collections) - 1)];
            $fit = $fitStyles[random_int(0, count($fitStyles) - 1)];

            $name = $baseName . ' ' . $collection . ' ' . $fit;
            $price = $this->randomPrice($profile['price_min'], $profile['price_max']);
            $salePrice = $this->randomSalePrice($price);

            $sku = 'SPT-' . str_pad((string) ($index + 1), 6, '0', STR_PAD_LEFT);
            $slug = Str::slug($name . '-' . $sku);

            $material = $materials[random_int(0, count($materials) - 1)];
            $highlight = $highlights[random_int(0, count($highlights) - 1)];
            $shortDescription = $baseName . ' cho nam, ' . $material . ', thiết kế ' . Str::lower($fit) . '.';

            $description = implode(' ', [
                $name . ' thuộc dòng ' . $category->name . ' của ' . $brand->name . '.',
                'Sản phẩm sử dụng ' . $material . ', đường may chắc chắn và hoàn thiện tỉ mỉ.',
                'Thiết kế giúp ' . $highlight . ', dễ phối đồ và phù hợp nhiều nhu cầu thể thao hàng ngày.',
            ]);

            $product = Product::updateOrCreate(
                ['sku' => $sku],
                [
                    'name' => $name,
                    'slug' => $slug,
                    'description' => $description,
                    'short_description' => $shortDescription,
                    'category_id' => $category->id,
                    'brand_id' => $brand->id,
                    'price' => $price,
                    'sale_price' => $salePrice,
                    'is_featured' => random_int(1, 100) <= 12,
                    'is_active' => true,
                    'meta_title' => $name . ' | ' . $brand->name,
                    'meta_description' => 'Mua ' . $name . ' chính hãng. Giá tốt, nhiều size và màu, hỗ trợ đổi trả dễ dàng.',
                ]
            );

            ProductVariant::where('product_id', $product->id)->delete();
            ProductImage::where('product_id', $product->id)->delete();

            $sizeMin = min(3, $sizes->count());
            $sizeMax = min(5, $sizes->count());
            $colorMin = min(3, $colors->count());
            $colorMax = min(5, $colors->count());

            $sizeSelection = $this->randomSubset($sizes, random_int($sizeMin, $sizeMax));
            $colorSelection = $this->randomSubset($colors, random_int($colorMin, $colorMax));

            $variantRows = [];
            foreach ($sizeSelection as $size) {
                foreach ($colorSelection as $color) {
                    $variantRows[] = [
                        'product_id' => $product->id,
                        'size_id' => $size->id,
                        'color_id' => $color->id,
                        'stock_quantity' => random_int(4, 60),
                        'additional_price' => random_int(0, 4) * 5000,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            ProductVariant::insert($variantRows);

            ProductImage::insert([
                [
                    'product_id' => $product->id,
                    'image_path' => $this->buildImageUrl($profile['image_keywords'], ($index * 2) + 1, false),
                    'alt_text' => $name,
                    'sort_order' => 1,
                    'is_primary' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'product_id' => $product->id,
                    'image_path' => $this->buildImageUrl($profile['image_keywords'], ($index * 2) + 2, true),
                    'alt_text' => $name . ' góc chụp thứ hai',
                    'sort_order' => 2,
                    'is_primary' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }
    }

    private function randomPrice(int $min, int $max): int
    {
        $price = random_int($min, $max);

        return (int) (round($price / 1000) * 1000);
    }

    private function randomSalePrice(int $price): ?int
    {
        if (random_int(1, 100) > 45) {
            return null;
        }

        $discountPercent = random_int(8, 30);
        $sale = (int) ($price * (100 - $discountPercent) / 100);

        return (int) (round($sale / 1000) * 1000);
    }

    private function randomSubset(Collection $items, int $count): Collection
    {
        $subset = $items->random($count);

        if (!($subset instanceof Collection)) {
            return collect([$subset]);
        }

        return $subset->values();
    }

    private function getCategoryProfile(string $categoryName): array
    {
        $normalized = Str::lower($categoryName);

        if (Str::contains($normalized, ['áo thun', 'áo chạy', 'áo khoác', 'áo'])) {
            return [
                'names' => ['Áo tập nam', 'Áo thể thao nam', 'Áo gym nam', 'Áo chạy bộ nam'],
                'price_min' => 229000,
                'price_max' => 799000,
                'image_keywords' => ['men', 'sportswear', 'gym', 'shirt'],
            ];
        }

        if (Str::contains($normalized, ['quần short', 'jogger', 'quần nỉ', 'quần'])) {
            return [
                'names' => ['Quần tập nam', 'Quần thể thao nam', 'Quần gym nam', 'Quần chạy bộ nam'],
                'price_min' => 269000,
                'price_max' => 899000,
                'image_keywords' => ['men', 'sportswear', 'gym', 'jogger', 'shorts'],
            ];
        }

        if (Str::contains($normalized, ['nón', 'túi', 'băng cổ tay', 'phụ kiện'])) {
            return [
                'names' => ['Phụ kiện thể thao', 'Nón thể thao', 'Túi tập gym', 'Băng bảo hộ thể thao'],
                'price_min' => 89000,
                'price_max' => 459000,
                'image_keywords' => ['sports', 'accessory', 'gym', 'cap', 'bag'],
            ];
        }

        return [
            'names' => ['Sản phẩm thể thao nam', 'Đồ thể thao nam', 'Trang phục tập luyện nam'],
            'price_min' => 199000,
            'price_max' => 799000,
            'image_keywords' => ['men', 'sportswear', 'fitness'],
        ];
    }

    private function buildImageUrl(array $keywords, int $seed, bool $isDetail): string
    {
        $keywordPart = collect($keywords)
            ->map(fn (string $item) => Str::lower(trim($item)))
            ->filter()
            ->implode('-');

        $seedKey = md5($keywordPart . '-' . $seed . ($isDetail ? '-detail' : '-main'));

        return 'https://picsum.photos/seed/' . $seedKey . '/900/1200';
    }
}
