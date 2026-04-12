<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Size;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::where('is_active', true)->get();
        $brands = Brand::where('is_active', true)->get();
        $sizes = Size::orderBy('id')->take(3)->get();
        $colors = Color::orderBy('id')->take(3)->get();

        if ($categories->isEmpty() || $brands->isEmpty() || $sizes->isEmpty() || $colors->isEmpty()) {
            return;
        }

        $products = [
            ['name' => 'Áo tập Dry-Fit Alpha', 'price' => 299000, 'sale_price' => 249000],
            ['name' => 'Quần short Flex Move', 'price' => 349000, 'sale_price' => null],
            ['name' => 'Áo khoác chạy bộ WindGuard', 'price' => 599000, 'sale_price' => 529000],
            ['name' => 'Quần jogger Tech Knit', 'price' => 459000, 'sale_price' => 399000],
            ['name' => 'Bộ đồ tập Training Set', 'price' => 799000, 'sale_price' => 699000],
            ['name' => 'Áo tank gym PowerLift', 'price' => 269000, 'sale_price' => null],
        ];

        foreach ($products as $index => $item) {
            $category = $categories[$index % $categories->count()];
            $brand = $brands[$index % $brands->count()];

            $slug = Str::slug($item['name']) . '-' . ($index + 1);
            $sku = 'SPT-' . str_pad((string) ($index + 1), 4, '0', STR_PAD_LEFT);

            $product = Product::updateOrCreate(
                ['sku' => $sku],
                [
                    'name' => $item['name'],
                    'slug' => $slug,
                    'description' => $item['name'] . ' với chất liệu thoáng khí, phù hợp tập luyện cường độ cao.',
                    'short_description' => 'Sản phẩm thể thao nam chất lượng cao.',
                    'category_id' => $category->id,
                    'brand_id' => $brand->id,
                    'price' => $item['price'],
                    'sale_price' => $item['sale_price'],
                    'is_featured' => $index % 2 === 0,
                    'is_active' => true,
                    'meta_title' => $item['name'],
                    'meta_description' => 'Mua ' . $item['name'] . ' chính hãng tại SportWear Shop.',
                ]
            );

            ProductVariant::where('product_id', $product->id)->delete();

            foreach ($sizes as $size) {
                foreach ($colors as $color) {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'size_id' => $size->id,
                        'color_id' => $color->id,
                        'stock_quantity' => rand(8, 40),
                        'additional_price' => rand(0, 3) * 10000,
                    ]);
                }
            }
        }
    }
}
