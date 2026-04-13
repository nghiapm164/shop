<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        $banners = [
            [
                'title' => 'Bộ sưu tập thể thao nam mới',
                'image' => 'images/banner-default-1.svg',
                'link' => '/products',
                'position' => 'home_top',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Flash Sale đến 50%',
                'image' => 'images/banner-default-2.svg',
                'link' => '/products',
                'position' => 'home_middle',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Miễn phí vận chuyển toàn quốc',
                'image' => 'images/banner-default-3.svg',
                'link' => '/products',
                'position' => 'home_bottom',
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($banners as $banner) {
            Banner::updateOrCreate(
                ['title' => $banner['title'], 'position' => $banner['position']],
                $banner
            );
        }
    }
}
