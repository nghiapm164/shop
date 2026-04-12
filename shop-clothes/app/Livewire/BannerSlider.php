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
            ->map(function (Banner $banner) {
                return [
                    'id' => $banner->id,
                    'title' => $banner->title,
                    'subtitle' => 'Khám phá ưu đãi mới nhất từ SportWear Shop',
                    'image_url' => $banner->image_url,
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
                'title' => 'Quần áo thể thao chất lượng cao',
                'subtitle' => 'Khám phá bộ sưu tập mới nhất của chúng tôi',
                'image_url' => 'images/banner-default-1.svg',
                'link' => '#',
                'cta_text' => 'Mua sắm ngay',
            ],
            [
                'id' => 2,
                'title' => 'Giảm giá đến 50%',
                'subtitle' => 'Cho các sản phẩm được chọn',
                'image_url' => 'images/banner-default-2.svg',
                'link' => '#',
                'cta_text' => 'Xem sale',
            ],
            [
                'id' => 3,
                'title' => 'Miễn phí vận chuyển',
                'subtitle' => 'Cho đơn hàng từ 200.000₫',
                'image_url' => 'images/banner-default-3.svg',
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
