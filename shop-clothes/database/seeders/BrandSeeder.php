<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            'SportWear Pro',
            'ActiveMan',
            'RunFast',
            'GymCore',
            'StreetFit',
            'UrbanMotion',
        ];

        foreach ($brands as $index => $name) {
            Brand::updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'description' => 'Thương hiệu ' . $name,
                    'is_active' => true,
                    'logo' => null,
                ]
            );
        }
    }
}
