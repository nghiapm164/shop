<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Banner;

class BannerSlider extends Component
{
    public $banners = [];
    public $currentIndex = 0;

    public function mount()
    {
        $this->banners = Banner::where('is_active', true)
            ->whereIn('position', ['home_top', 'home_middle', 'home_bottom'])
            ->orderBy('sort_order')
            ->get()
            ->map(function (Banner $banner, $key) {
                $mockImages = [
                    'https://images.unsplash.com/photo-1483721310020-03333e577078?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80', // Gym/Fitness
                    'https://images.unsplash.com/photo-1517836357463-d25dfeac3438?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80', // Gym
                    'https://images.unsplash.com/photo-1556817411-31ae72fa3ea8?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80'  // Sport
                ];

                return [
                    'id' => $banner->id,
                    'title' => $banner->title,
                    'subtitle' => 'Khám phá ưu đãi mới nhất từ SportWear Shop',
                    'image_url' => $mockImages[$key % 3], // Temporary mock images from Unsplash
                    'link' => $banner->link ?: '#',
                    'cta_text' => 'Xem ngay',
                ];
            })
            ->values()
            ->toArray();

        if (empty($this->banners)) {
            $this->banners = $this->getDefaultBanners();
        }
    }

    public function nextBanner()
    {
        if (count($this->banners) > 0) {
            $this->currentIndex = ($this->currentIndex + 1) % count($this->banners);
        }
    }

    public function goToBanner($index)
    {
        if ($index >= 0 && $index < count($this->banners)) {
            $this->currentIndex = $index;
        }
    }

    private function getDefaultBanners()
    {
        return [
            [
                'id' => 1,
                'title' => 'Bộ Sưu Tập Thể Thao Mới',
                'subtitle' => 'Khám phá bộ sưu tập mới nhất của chúng tôi với phong cách athleisure năng động',
                'image_url' => 'https://images.unsplash.com/photo-1483721310020-03333e577078?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80',
                'link' => '#',
                'cta_text' => 'Mua sắm ngay',
            ],
            [
                'id' => 2,
                'title' => 'Giảm Giá Lên Đến 50%',
                'subtitle' => 'Áp dụng cho các sản phẩm quần áo tập luyện mùa hè được chọn',
                'image_url' => 'https://images.unsplash.com/photo-1517836357463-d25dfeac3438?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80',
                'link' => '#',
                'cta_text' => 'Xem sale',
            ],
            [
                'id' => 3,
                'title' => 'Freeship Toàn Quốc',
                'subtitle' => 'Miễn phí vận chuyển cho tất cả đơn hàng từ 299.000₫',
                'image_url' => 'https://images.unsplash.com/photo-1556817411-31ae72fa3ea8?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80',
                'link' => '#',
                'cta_text' => 'Mua ngay',
            ],
        ];
    }

    public function render()
    {
        return view('livewire.banner-slider', [
            'banners' => $this->banners,
            'currentBanner' => $this->banners[$this->currentIndex] ?? null,
            'totalBanners' => count($this->banners),
        ]);
    }
}
