<?php

namespace Database\Seeders;

use App\Models\FlashSale;
use App\Models\Product;
use Illuminate\Database\Seeder;

class FlashSaleSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::query()
            ->where('is_active', true)
            ->get(['id', 'name', 'price', 'sale_price']);

        if ($products->isEmpty()) {
            return;
        }

        $selectedProducts = $products->shuffle()->take(min(24, $products->count()));

        foreach ($selectedProducts as $index => $product) {
            $basePrice = (float) ($product->sale_price ?? $product->price);
            $discountPercent = random_int(10, 35);
            $flashPrice = (int) round(($basePrice * (100 - $discountPercent) / 100) / 1000) * 1000;

            if ($flashPrice >= $basePrice) {
                $flashPrice = max(1000, (int) $basePrice - 1000);
            }

            FlashSale::updateOrCreate(
                ['product_id' => $product->id],
                [
                    'title' => 'Flash Sale ' . ($index + 1),
                    'flash_price' => $flashPrice,
                    'start_at' => now()->subHours(random_int(1, 18)),
                    'end_at' => now()->addDays(random_int(2, 7))->addHours(random_int(1, 12)),
                    'sort_order' => $index + 1,
                    'is_active' => true,
                ]
            );
        }
    }
}
