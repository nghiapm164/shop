# 🚀 Implementation Guide - Layout & Components

## Step 1: Update Routes

### File: `routes/web.php`

```php
<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', HomeController::class)->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');

// Auth Routes (from Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
    
    // Add admin controllers here
});

require __DIR__.'/auth.php';
```

---

## Step 2: Create Controllers

### File: `app/Http/Controllers/HomeController.php`

```php
<?php

namespace App\Http\Controllers;

use App\Models\Product;

class HomeController extends Controller
{
    public function __invoke()
    {
        $featuredProducts = Product::where('is_featured', true)
            ->with('category', 'colors', 'reviews')
            ->limit(8)
            ->get();

        $bestSellers = Product::withCount('orders')
            ->orderByDesc('orders_count')
            ->limit(4)
            ->get();

        return view('home', [
            'featuredProducts' => $featuredProducts,
            'bestSellers' => $bestSellers,
        ]);
    }
}
```

### File: `app/Http/Controllers/ProductController.php`

```php
<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::with('category', 'colors', 'reviews')
            ->paginate(12);

        return view('products.index', [
            'products' => $products,
        ]);
    }

    public function show(Product $product): View
    {
        $product->load('category', 'colors', 'reviews', 'specifications');
        $related = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();

        return view('products.show', [
            'product' => $product,
            'related' => $related,
        ]);
    }
}
```

---

## Step 3: Create Product Index View

### File: `resources/views/products/index.blade.php`

```blade
@extends('layouts.app')

@section('meta_title', 'Sản phẩm - SportWear Shop')
@section('meta_description', 'Khám phá bộ sưu tập quần áo thể thao nam')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <x-breadcrumb :items="[['label' => 'Sản phẩm']]" />

    <!-- Page Title -->
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Tất cả sản phẩm</h1>

    <!-- Sidebar + Products -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Sidebar Filters -->
        <div class="hidden lg:block">
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <!-- Price Filter -->
                <div class="mb-6">
                    <h3 class="font-bold text-gray-900 mb-3">Giá</h3>
                    <div class="space-y-2">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" class="w-4 h-4 rounded border-gray-300"> 
                            <span class="text-sm text-gray-600">Under 500k</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" class="w-4 h-4 rounded border-gray-300"> 
                            <span class="text-sm text-gray-600">500k - 1M</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" class="w-4 h-4 rounded border-gray-300"> 
                            <span class="text-sm text-gray-600">Over 1M</span>
                        </label>
                    </div>
                </div>

                <!-- Category Filter -->
                <div class="mb-6">
                    <h3 class="font-bold text-gray-900 mb-3">Danh mục</h3>
                    <div class="space-y-2">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" class="w-4 h-4 rounded border-gray-300"> 
                            <span class="text-sm text-gray-600">Áo thun</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" class="w-4 h-4 rounded border-gray-300"> 
                            <span class="text-sm text-gray-600">Quần shorts</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" class="w-4 h-4 rounded border-gray-300"> 
                            <span class="text-sm text-gray-600">Quần dài</span>
                        </label>
                    </div>
                </div>

                <!-- In Stock Filter -->
                <div>
                    <h3 class="font-bold text-gray-900 mb-3">Kho</h3>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" class="w-4 h-4 rounded border-gray-300" checked> 
                        <span class="text-sm text-gray-600">Còn hàng</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="lg:col-span-3">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @forelse($products as $product)
                    <x-product-card :product="$product" />
                @empty
                    <div class="col-span-full py-12 text-center text-gray-500">
                        <p class="text-lg">Không tìm thấy sản phẩm nào</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($products->hasPages())
                <x-pagination :paginator="$products" />
            @endif
        </div>
    </div>
</div>
@endsection
```

---

## Step 4: Create Product Show View (Detail Page)

### File: `resources/views/products/show.blade.php`

```blade
@extends('layouts.app')

@section('meta_title', $product->name . ' - SportWear Shop')
@section('meta_description', Str::limit($product->description, 150))
@section('og_image', asset($product->image_url))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <x-breadcrumb :items="[
        ['label' => 'Sản phẩm', 'url' => route('products.index')],
        ['label' => $product->category->name, 'url' => '#'],
        ['label' => $product->name],
    ]" />

    <!-- Product Detail -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-8">
        <!-- Images -->
        <div>
            <div class="bg-gray-100 rounded-lg overflow-hidden h-96 flex items-center justify-center">
                <img src="{{ asset($product->image_url) }}" alt="{{ $product->name }}" 
                    class="w-full h-full object-cover">
            </div>
            
            <!-- Thumbnails -->
            @if($product->images->count() > 1)
                <div class="flex gap-2 mt-4">
                    @foreach($product->images as $image)
                        <img src="{{ asset($image->url) }}" alt="{{ $product->name }}"
                            class="w-16 h-16 border-2 rounded cursor-pointer hover:border-red-500">
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Product Info -->
        <div>
            <!-- Rating -->
            <div class="flex items-center gap-2 mb-4">
                <x-star-rating :rating="$product->average_rating ?? 0" />
                <span class="text-sm text-gray-600">({{ $product->review_count ?? 0 }} nhận xét)</span>
            </div>

            <!-- Name -->
            <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $product->name }}</h1>

            <!-- Price -->
            <div class="flex items-center gap-4 mb-6">
                @if($product->sale_price)
                    <span class="text-3xl font-bold text-red-500">
                        {{ number_format($product->sale_price, 0) }}₫
                    </span>
                    <span class="text-xl text-gray-400 line-through">
                        {{ number_format($product->price, 0) }}₫
                    </span>
                    <span class="badge-warning">
                        -{{ round((1 - $product->sale_price / $product->price) * 100) }}%
                    </span>
                @else
                    <span class="text-3xl font-bold text-gray-900">
                        {{ number_format($product->price, 0) }}₫
                    </span>
                @endif
            </div>

            <!-- Stock Status -->
            @if($product->is_active && $product->stock_quantity > 0)
                <p class="text-green-600 font-semibold mb-6">✓ Còn {{ $product->stock_quantity }} sản phẩm</p>
            @else
                <p class="text-red-600 font-semibold mb-6">✕ Hết hàng</p>
            @endif

            <!-- Description -->
            <p class="text-gray-600 mb-6">{{ $product->description }}</p>

            <!-- Colors -->
            @if($product->colors->count() > 0)
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-900 mb-2">Màu sắc</label>
                    <div class="flex gap-3">
                        @foreach($product->colors as $color)
                            <button class="w-8 h-8 rounded-full border-2 border-gray-300 hover:border-gray-900"
                                style="background-color: {{ $color->hex_code }}"
                                title="{{ $color->name }}"></button>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Size (if available) -->
            @if($product->sizes->count() > 0)
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-900 mb-2">Kích cỡ</label>
                    <div class="flex flex-wrap gap-2">
                        @foreach($product->sizes as $size)
                            <button class="px-4 py-2 border-2 border-gray-300 rounded hover:border-red-500 hover:bg-red-50">
                                {{ $size->name }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Quantity & Buttons -->
            <div class="flex gap-3 mb-6">
                <div class="flex items-center border border-gray-300 rounded-lg">
                    <button class="px-4 py-2 text-gray-600 hover:text-red-500">−</button>
                    <input type="number" value="1" min="1" class="w-16 text-center border-l border-r border-gray-300 py-2">
                    <button class="px-4 py-2 text-gray-600 hover:text-red-500">+</button>
                </div>
                <button class="flex-1 btn-primary">Thêm vào giỏ</button>
                <button class="btn-secondary">❤️ Wishlist</button>
            </div>

            <!-- Additional Info -->
            <div class="bg-gray-50 rounded-lg p-4 space-y-3 text-sm text-gray-600">
                <p><strong>SKU:</strong> {{ $product->sku }}</p>
                <p><strong>Danh mục:</strong> {{ $product->category->name }}</p>
                <p><strong>Chất liệu:</strong> {{ $product->material ?? 'N/A' }}</p>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($related->count() > 0)
        <section class="mt-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Sản phẩm tương tự</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($related as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        </section>
    @endif
</div>
@endsection
```

---

## Step 5: Update Product Model

### File: `app/Models/Product.php`

Ensure your Product model has the required attributes:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'price', 'sale_price',
        'sku', 'image_url', 'secondary_image_url', 'is_active',
        'is_featured', 'stock_quantity', 'view_count', 'category_id',
        'material', 'sales_count',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    // Relationships
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function colors(): BelongsToMany
    {
        return $this->belongsToMany(Color::class);
    }

    public function sizes(): BelongsToMany
    {
        return $this->belongsToMany(Size::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'order_items');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    // Computed Attributes
    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    public function getReviewCountAttribute()
    {
        return $this->reviews()->count();
    }

    public function getSalesPriceAttribute($value)
    {
        return $value ? (float) $value : null;
    }
}
```

---

## Step 6: Test Everything

```bash
# Ensure Tailwind is compiled
npm run dev

# Run the application
php artisan serve

# Visit http://localhost:8000
```

---

## 📌 Quick Checklist

- [ ] Update routes in `routes/web.php`
- [ ] Create `HomeController` with featured products query
- [ ] Create `ProductController` with index/show methods
- [ ] Create product views: `index.blade.php`, `show.blade.php`
- [ ] Verify Product model has all required relationships
- [ ] Run `npm run dev` to compile Tailwind
- [ ] Test home page: `http://localhost:8000/`
- [ ] Test product listing: `http://localhost:8000/products`
- [ ] Test product detail: `http://localhost:8000/products/{slug}`
- [ ] Check cart badge updates (Livewire)
- [ ] Test responsive design on mobile/tablet

---

## 🎨 Customization

### Change Brand Color:
In `layouts/app.blade.php`, update the Tailwind config:
```javascript
colors: {
    primary: { 500: '#YOUR_COLOR' },  // Change red accent
}
```

### Update Navigation Links:
In `components/navbar.blade.php`, update route() calls to match your routes.

### Customize Hero Section:
Edit `home.blade.php` hero section HTML/styling.

---

## 🆘 Troubleshooting

**Q: Product images not showing?**
- Ensure `storage:link` is created: `php artisan storage:link`
- Verify image URL attributes in Product model

**Q: Navbar dropdown not working?**
- Check Alpine.js is loaded (check console for errors)
- On mobile, menu is hidden (test on desktop)

**Q: Styles not applying?**
- Run `npm run dev` to compile Tailwind CSS
- Clear cache: `php artisan cache:clear`

---

Enjoy your new SportWear Shop! 🎉
