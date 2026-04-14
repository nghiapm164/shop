<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\Attributes\Computed;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Size;

class ProductFilter extends Component
{
    #[Url]
    public $keyword = '';

    #[Url]
    public $category = '';

    #[Url]
    public $brand = [];

    #[Url]
    public $size = [];

    #[Url]
    public $color = [];

    #[Url]
    public $price_min = 0;

    #[Url]
    public $price_max = 10000000;

    #[Url]
    public $sort = 'newest';

    #[Url]
    public $collection = '';

    #[Url]
    public $page = 1;

    public $view_type = 'grid';
    public $per_page = 12;

    public function mount(): void
    {
        $this->applyCollectionPreset();
    }

    public function updatedCollection(): void
    {
        $this->applyCollectionPreset();
        $this->page = 1;
    }

    public function resetFilters()
    {
        $this->keyword = '';
        $this->category = '';
        $this->brand = [];
        $this->size = [];
        $this->color = [];
        $this->price_min = 0;
        $this->price_max = 10000000;
        $this->sort = 'newest';
        $this->collection = '';
        $this->page = 1;
    }

    private function applyCollectionPreset(): void
    {
        if ($this->collection === 'best_sellers' && $this->sort === 'newest') {
            $this->sort = 'popularity';
        }
    }

    public function toggleBrand($brandId)
    {
        if (in_array($brandId, $this->brand)) {
            $this->brand = array_filter($this->brand, fn($id) => $id !== $brandId);
        } else {
            $this->brand[] = $brandId;
        }
        $this->page = 1;
    }

    public function toggleSize($sizeId)
    {
        if (in_array($sizeId, $this->size)) {
            $this->size = array_filter($this->size, fn($id) => $id !== $sizeId);
        } else {
            $this->size[] = $sizeId;
        }
        $this->page = 1;
    }

    public function toggleColor($colorId)
    {
        if (in_array($colorId, $this->color)) {
            $this->color = array_filter($this->color, fn($id) => $id !== $colorId);
        } else {
            $this->color[] = $colorId;
        }
        $this->page = 1;
    }

    public function setSort($sortValue)
    {
        $this->sort = $sortValue;
        $this->page = 1;
    }

    public function setViewType($type)
    {
        $this->view_type = $type;
    }

    #[Computed]
    public function categories()
    {
        return Category::where('is_active', true)
            ->with('children')
            ->whereNull('parent_id')
            ->get();
    }

    #[Computed]
    public function brands()
    {
        return Brand::where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function sizes()
    {
        return Size::query()
            ->orderByRaw("CASE UPPER(code)
                WHEN 'XS' THEN 1
                WHEN 'S' THEN 2
                WHEN 'M' THEN 3
                WHEN 'L' THEN 4
                WHEN 'XL' THEN 5
                WHEN 'XXL' THEN 6
                WHEN 'XXXL' THEN 7
                ELSE 99
            END")
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function colors()
    {
        return Color::all();
    }

    #[Computed]
    public function products()
    {
        $query = Product::where('is_active', true);

        if ($this->collection === 'flash_sale') {
            $query->whereHas('flashSales', function ($saleQuery) {
                $saleQuery->running();
            });
        }

        // Keyword search
        if ($this->keyword) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->keyword}%")
                    ->orWhere('description', 'like', "%{$this->keyword}%")
                    ->orWhere('sku', 'like', "%{$this->keyword}%");
            });
        }

        // Category filter
        if ($this->category) {
            $query->where('category_id', $this->category)
                ->orWhereHas('category', function ($q) {
                    $q->where('parent_id', $this->category);
                });
        }

        // Brand filter
        if (!empty($this->brand)) {
            $query->whereIn('brand_id', $this->brand);
        }

        // Price filter
        $query->whereBetween('price', [$this->price_min, $this->price_max]);

        // Size filter via variants
        if (!empty($this->size)) {
            $query->whereHas('variants', function ($q) {
                $q->whereIn('size_id', $this->size);
            });
        }

        // Color filter via variants
        if (!empty($this->color)) {
            $query->whereHas('variants', function ($q) {
                $q->whereIn('color_id', $this->color);
            });
        }

        // Sorting
        switch ($this->sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'popularity':
                $query->withSum('orderItems as sold_quantity', 'quantity')
                    ->orderByDesc('sold_quantity')
                    ->orderByDesc('created_at');
                break;
            case 'rating':
                $query->selectRaw('products.*, (SELECT COALESCE(AVG(rating), 0) FROM reviews WHERE reviews.product_id = products.id) as avg_rating')
                    ->orderByDesc('avg_rating');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        return $query->with('category', 'brand', 'variants.color', 'variants.size', 'reviews')
            ->paginate($this->per_page, page: $this->page);
    }

    #[Computed]
    public function totalProducts()
    {
        return Product::where('is_active', true)
            ->when($this->collection === 'flash_sale', function ($q) {
                $q->whereHas('flashSales', function ($saleQuery) {
                    $saleQuery->running();
                });
            })
            ->when($this->keyword, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('name', 'like', "%{$this->keyword}%")
                        ->orWhere('description', 'like', "%{$this->keyword}%")
                        ->orWhere('sku', 'like', "%{$this->keyword}%");
                });
            })
            ->when($this->category, function ($q) {
                $q->where('category_id', $this->category)
                    ->orWhereHas('category', function ($sub) {
                        $sub->where('parent_id', $this->category);
                    });
            })
            ->when(!empty($this->brand), function ($q) {
                $q->whereIn('brand_id', $this->brand);
            })
            ->whereBetween('price', [$this->price_min, $this->price_max])
            ->when(!empty($this->size), function ($q) {
                $q->whereHas('variants', function ($sub) {
                    $sub->whereIn('size_id', $this->size);
                });
            })
            ->when(!empty($this->color), function ($q) {
                $q->whereHas('variants', function ($sub) {
                    $sub->whereIn('color_id', $this->color);
                });
            })
            ->count();
    }

    public function render()
    {
        return view('livewire.product-filter', [
            'products' => $this->products,
            'categories' => $this->categories,
            'brands' => $this->brands,
            'sizes' => $this->sizes,
            'colors' => $this->colors,
            'totalProducts' => $this->totalProducts,
            'collectionLabel' => match ($this->collection) {
                'flash_sale' => 'Deal nóng trong ngày',
                'new_arrivals' => 'Hàng mới lên kệ',
                'best_sellers' => 'Top bán chạy',
                default => 'Tất cả sản phẩm',
            },
            'hasActiveFilters' => !empty($this->brand) || !empty($this->size) || 
                                  !empty($this->color) || $this->category || 
                                  $this->keyword || $this->price_min > 0 || 
                                  $this->price_max < 10000000,
        ]);
    }
}
