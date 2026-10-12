<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sizes = [
            ['name' => 'Extra Small', 'code' => 'XS'],
            ['name' => 'Small', 'code' => 'S'],
            ['name' => 'Medium', 'code' => 'M'],
            ['name' => 'Large', 'code' => 'L'],
            ['name' => 'Extra Large', 'code' => 'XL'],
            ['name' => 'Double Extra Large', 'code' => 'XXL'],
            ['name' => 'Triple Extra Large', 'code' => 'XXXL'],
            // Size giày
            ['name' => 'Size 36', 'code' => '36'],
            ['name' => 'Size 37', 'code' => '37'],
            ['name' => 'Size 38', 'code' => '38'],
            ['name' => 'Size 39', 'code' => '39'],
            ['name' => 'Size 40', 'code' => '40'],
            ['name' => 'Size 41', 'code' => '41'],
            ['name' => 'Size 42', 'code' => '42'],
            ['name' => 'Size 43', 'code' => '43'],
            ['name' => 'Size 44', 'code' => '44'],
            ['name' => 'Size 45', 'code' => '45'],
            // Phụ kiện, dụng cụ tập
            ['name' => 'Free Size', 'code' => 'FREE'],
        ];

        foreach ($sizes as $size) {
            DB::table('sizes')->updateOrInsert(
                ['name' => $size['name']],
                [
                    'code' => $size['code'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
