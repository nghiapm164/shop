<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        $coupons = [
            [
                'code' => 'WELCOME10',
                'type' => 'percent',
                'value' => 10,
                'min_order_amount' => 300000,
                'max_discount' => 100000,
                'usage_limit' => 500,
                'used_count' => 0,
                'start_date' => now()->subDays(7)->toDateString(),
                'end_date' => now()->addMonths(3)->toDateString(),
                'is_active' => true,
            ],
            [
                'code' => 'FREESHIP50',
                'type' => 'fixed',
                'value' => 50000,
                'min_order_amount' => 499000,
                'max_discount' => null,
                'usage_limit' => 300,
                'used_count' => 0,
                'start_date' => now()->subDays(2)->toDateString(),
                'end_date' => now()->addMonths(1)->toDateString(),
                'is_active' => true,
            ],
            [
                'code' => 'GYM25',
                'type' => 'percent',
                'value' => 25,
                'min_order_amount' => 1000000,
                'max_discount' => 250000,
                'usage_limit' => 200,
                'used_count' => 0,
                'start_date' => now()->toDateString(),
                'end_date' => now()->addMonths(2)->toDateString(),
                'is_active' => true,
            ],
        ];

        foreach ($coupons as $coupon) {
            Coupon::updateOrCreate(['code' => $coupon['code']], $coupon);
        }
    }
}
