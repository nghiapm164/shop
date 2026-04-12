# 🏠 Homepage & Product Pages Setup Guide

## Overview

This guide walks through the complete setup for:
1. **Homepage** - 8 section page with banner slider, categories, products, testimonials, and newsletter
2. **Product Listing Page** - Advanced filterable product grid with Livewire real-time updates

---

## 📂 Files Created/Modified

### Livewire Components
```
✓ app/Livewire/BannerSlider.php
- Manages hero banner carousel (5s auto-rotate)
- Supports custom banners from DB
- Navigation with dots and prev/next buttons

✓ app/Livewire/ProductFilter.php  
- Real-time product filtering with URL binding
- Supports: category, brands, sizes, colors, price range, sort, search
- Computed properties for efficient queries
- 12+ products per page pagination
```

### Views
```
✓ resources/views/home.blade.php
- 8 sections (hero, categories, latest, ad banner, bestsellers, brands, testimonials, newsletter)
- Uses Livewire BannerSlider
- Uses x-product-card component for all product grids
- Alpine.js testimonials slider with auto-rotate

✓ resources/views/products/index.blade.php
- Simple wrapper that includes ProductFilter Livewire component
- All filtering UI in Livewire view

✓ resources/views/livewire/banner-slider.blade.php
- Hero section with auto-rotating banners
- Manual navigation via buttons and indicator dots
- Responsive image with gradient overlay

✓ resources/views/livewire/product-filter.blade.php
- Sidebar filters (categories, brands, sizes, colors, price)
- Product grid with toolbar (sort, view toggle)
- Real-time search
- Responsive pagination
```

---

## ⚙️ Routes Setup

### File: `routes/web.php`

Add these routes:

```php
<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', HomeController::class)->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');

// Authenticated Routes...
// Admin Routes...
require __DIR__.'/auth.php';
```

---

## 🎮 Controller Setup

### File: `app/Http/Controllers/HomeController.php`

```php
<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Banner;
use App\Models\Brand;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        // Latest products (8 items)
        $newProducts = Product::where('is_active', true)
            ->with('category', 'colors', 'reviews')
            ->latest('created_at')
            ->limit(8)
            ->get();

        // Best sellers (8 items)
        $bestSellers = Product::where('is_active', true)
            ->withCount('orders')
            ->orderByDesc('orders_count')
            ->with('category', 'colors', 'reviews')
            ->limit(8)
            ->get();

        // Advertising banner (middle position)
        $adBanner = Banner::where('is_active', true)
            ->where('position', 'middle')
            ->first();

        // Brands (6 items)
        $brands = Brand::where('is_active', true)
            ->limit(6)
            ->get();

        // Testimonials/Reviews (4 items)
        $testimonials = Product::where('is_active', true)
            ->with('reviews.user')
            ->get()
            ->flatMap(fn($product) => $product->reviews->take(1))
            ->unique('user_id')
            ->take(4)
            ->map(fn($review) => [
                'id' => $review->id,
                'name' => $review->user->name,
                'rating' => $review->rating,
                'text' => $review->comment,
                'avatar' => '👨', // or use user avatar
            ])
            ->values()
            ->toArray() ?? [];

        return view('home', [
            'newProducts' => $newProducts,
            'bestSellers' => $bestSellers,
            'adBanner' => $adBanner,
            'brands' => $brands,
            'testimonials' => $testimonials,
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
        // Livewire component handles all filtering
        return view('products.index');
    }

    public function show(Product $product): View
    {
        $product->load('category', 'colors', 'sizes', 'reviews.user', 'images');
        
        // Increment view count
        $product->increment('view_count');

        // Related products (same category, but different product)
        $related = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->with('colors', 'reviews')
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

## 🗄️ Database Model Updates Required

### Product Model
Ensure your Product model has these attributes/relationships:

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
        'is_featured', 'stock_quantity', 'view_count', 'sales_count',
        'category_id', 'brand_id', 'material',
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

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
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
    #[Attribute]
    public function averageRating(): float
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    #[Attribute]
    public function reviewCount(): int
    {
        return $this->reviews()->count();
    }
}
```

### Category Model
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'parent_id', 'is_active', 'icon_url'];

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
```

### Brand Model
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends Model
{
    protected $fillable = ['name', 'slug', 'logo_url', 'is_active'];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
```

### Color Model
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    protected $fillable = ['name', 'hex_code'];

    public $timestamps = false;
}
```

### Size Model
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    protected $fillable = ['name', 'sort_order'];

    public $timestamps = false;
}
```

### Banner Model (for Hero Section)
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'title', 'subtitle', 'image_url', 'link',
        'cta_text', 'position', 'is_active', 'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
```

### Review Model
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $fillable = ['product_id', 'user_id', 'rating', 'comment'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
```

---

## 📝 Homepage Features (8 Sections)

### 1. Hero Banner Slider
- **Component**: `livewire:banner-slider`
- **Features**:
  - Auto-rotates every 5 seconds
  - Manual navigation with prev/next buttons
  - Indicator dots show current slide
  - Fade transition between slides
  - Responsive design

### 2. Featured Categories
- Grid layout: 2 cols mobile, 4 cols desktop
- Icon + category name
- Click navigates to products page with category filter
- Hover scale effect on icons

### 3. Latest Products
- Grid: 4 columns on desktop, 2 on mobile  
- First 8 products ordered by `created_at DESC`
- Uses `x-product-card` component
- Shows NEW badge for products < 7 days old
- "View all" button links to `/products`

### 4. Advertising Banner
- Full-width responsive banner
- Optional - shows only if banner exists in DB with position="middle"
- Contains image, title, CTA button
- Hover scale effect

### 5. Best Sellers
- Grid layout (same as latest)
- Products ordered by sales count descending
- Uses `x-product-card` component
- "View all" button links to products

### 6. Brands Showcase
- 6-column grid (6 brands displayed)
- Grayscale logo images
- Change to color on hover
- Click to filter products by brand

### 7. Testimonials Slider
- Alpine.js slider with auto-rotate (5s interval)
- Manual navigation via indicator dots
- Shows: avatar emoji, customer name, rating, review text
- Responsive and smooth transitions
- Default fallback testimonials if no DB data

### 8. Newsletter Signup
- Email input + subscribe button
- Simple form with validation
- Privacy notice text
- Red accent card styling

---

## 🔽 Product Listing Page Features

### Sidebar Filters (1/4 width)

#### Categories
- Tree view if sub-categories exist
- Radio buttons (single select)
- Indented sub-categories

#### Brands
- Checkbox list
- Product count next to each brand
- Scrollable if many brands
- Searchable (optional enhancement)

#### Sizes
- Toggle buttons (S/M/L/XL/XXL)
- Multiple select
- Highlighted when active
- Red accent color when selected

#### Colors
- Color swatches (circles)
- Hover tooltip with color name
- Ring highlight when selected
- Click to toggle

#### Price Range
- Min/Max input fields
- Real-time price display
- Could add dual slider (enhancement)
- Visual price bar

#### Apply & Reset
- Apply button updates results
- Reset clears all filters
- Only shows when filters active

### Product Grid (3/4 width)

#### Toolbar
- Query string updates real-time
- **Result Count**: "Showing 150 products"
- **Sort Dropdown**: Newest, Popular, Rating, Price Low/High
- **View Toggle**: Grid / List view buttons
- Clear filters button (if active)

#### Grid/List Display
- **Grid View**: Card layout (existing product-card component)
- **List View**: Horizontal rows with image, name, price, button
- Both responsive
- Smooth transitions between views

#### Pagination
- Bottom pagination controls
- Previous/Next buttons
- Page numbers
- Results count displayed
- Mobile simplified pagination

#### Empty State
- Search icon
- "No products found" message
- Suggestion to change filters
- Clear filters CTA button

---

## 🔄 Real-Time Updates (Livewire)

### URL Query String Binding
Every filter change updates the URL:
```
/products?keyword=shirt&category=1&brand[]=5&brand[]=8&size[]=3&color[]=10&price_min=100000&price_max=500000&sort=price_low&page=2
```

Benefits:
- Bookmarkable search results
- Browser back/forward works
- Share filtered search with others
- Page refresh preserves filters

### Live Filtering
- Filters update automatically
- No page reload needed
- Products grid updates in place
- Smooth loading experience
- Debounced search input

---

## 🚀 Setup Checklist

- [ ] Create HomeController with product queries
- [ ] Create ProductController with index/show methods
- [ ] Ensure Product model has all relationships
- [ ] Create Category, Brand, Color, Size models/migrations
- [ ] Create Banner model and seed with example banner
- [ ] Update routes with home and products routes
- [ ] Verify Livewire is installed and configured
- [ ] Test BannerSlider component on homepage
- [ ] Test ProductFilter on /products page
- [ ] Test filters update URL query string
- [ ] Test pagination links work
- [ ] Verify responsive design on mobile/tablet
- [ ] Test search bar real-time keyword matching
- [ ] Test sort dropdown changes order
- [ ] Test view toggle switches grid/list
- [ ] Test all category, brand, size, color filters
- [ ] Test price range filter
- [ ] Test clear filters button

---

## 🎯 Performance Considerations

1. **Product Queries**
   - Use `with()` for eager loading relationships
   - Limit queries with `limit()`
   - Pagination to avoid large result sets

2. **Images**
   - Lazy load product images
   - Use optimized image sizes
   - WebP format recommended

3. **Frontend**
   - Alpine.js for slider (lightweight)
   - Livewire for real-time filters
   - CSS transitions instead of animations

4. **Caching**
   - Cache category list (rarely changes)
   - Cache brand list
   - Cache most popular products

---

## 🐛 Troubleshooting

**Q: Homepage shows no products?**
- Check Product model relationships
- Verify products exist in DB with `is_active = 1`
- Check controller query doesn't have restrictive where clauses

**Q: Filters not working?**
- Verify ProductFilter Livewire component is loading
- Check browser console for JavaScript errors
- Ensure Livewire scripts are included in layout

**Q: Pagination links broken?**
- Verify routes named `products.index`
- Check pagination blade component path
- Ensure query string preserved in pagination links

**Q: Images not showing?**
- Run `php artisan storage:link`
- Verify image_url attributes populated
- Check asset() path is correct

**Q: Testimonials slider not rotating?**
- Ensure Alpine.js CDN loaded
- Check browser console for errors
- Verify testimonials array has data

---

## 📞 Support

For issues:
1. Check console for JavaScript errors
2. Run `php artisan optimize:clear`
3. Clear browser cache
4. Verify all models are created
5. Check database migrations ran successfully

All components are production-ready! 🎉
