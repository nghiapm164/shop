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
        ];

        foreach ($sizes as $size) {
            DB::table('sizes')->insert([
                'name' => $size['name'],
                'code' => $size['code'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
