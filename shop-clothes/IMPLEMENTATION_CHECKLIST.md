# ✅ Implementation Checklist

Quick reference for setting up the homepage and product listing pages.

---

## Phase 1: Models & Database (30 mins)

- [ ] Review MODEL_TEMPLATES.md
- [ ] Run model/migration creation commands:
```bash
php artisan make:model Product -m
php artisan make:model Category -m
php artisan make:model Brand -m
php artisan make:model Color -m
php artisan make:model Size -m
php artisan make:model Banner -m
php artisan make:model Review -m
php artisan make:model ProductImage -m
php artisan make:migration create_product_color_table
php artisan make:migration create_product_size_table
```

- [ ] Copy migration code from MODEL_TEMPLATES.md into migration files
- [ ] Copy model code from MODEL_TEMPLATES.md into model files
- [ ] Run migrations:
```bash
php artisan migrate
```

- [ ] Update User model if needed (add relationships to Order, Review)
- [ ] Create Order model if you need order tracking for sales_count

---

## Phase 2: Controllers & Routes (15 mins)

- [ ] HomeController.php ✅ **DONE** - Check `app/Http/Controllers/HomeController.php`
- [ ] ProductController.php ✅ **DONE** - Check `app/Http/Controllers/ProductController.php`
- [ ] Routes updated ✅ **DONE** - Check `routes/web.php` for:
  - [ ] Route::get('/', HomeController::class)->name('home')
  - [ ] Route::get('/products', ...)->name('products.index')
  - [ ] Route::get('/products/{product:slug}', ...)->name('products.show')

---

## Phase 3: Components (Livewire) (⏸️ Already Created)

- [ ] ✅ `app/Livewire/BannerSlider.php` - Created
- [ ] ✅ `app/Livewire/ProductFilter.php` - Created

**Verify components exist and have correct namespaces**

---

## Phase 4: Views (Blade Templates) (⏸️ Already Created)

- [ ] ✅ `resources/views/home.blade.php` - Created (8 sections)
- [ ] ✅ `resources/views/products/index.blade.php` - Created
- [ ] ✅ `resources/views/livewire/banner-slider.blade.php` - Created
- [ ] ✅ `resources/views/livewire/product-filter.blade.php` - Created

**Verify file paths match exactly**

---

## Phase 5: Layout & Components (Check)

- [ ] ✅ `resources/views/layouts/app.blade.php` - Exists (from previous session)
- [ ] ✅ `resources/views/components/product-card.blade.php` - Exists
- [ ] ✅ `resources/views/components/pagination.blade.php` - Exists
- [ ] ✅ `resources/views/components/star-rating.blade.php` - Exists

**These should already exist from setup session**

---

## Phase 6: Seed Initial Data (20 mins)

Run these commands to populate test data:

```bash
php artisan tinker
```

Then in Tinker shell:

```php
// Create sizes
$sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];
foreach ($sizes as $size) {
    \App\Models\Size::create(['name' => $size, 'sort_order' => array_key_search($size, $sizes)]);
}

// Create colors
$colors = [
    ['name' => 'Red', 'hex_code' => '#FF0000'],
    ['name' => 'Blue', 'hex_code' => '#0000FF'],
    ['name' => 'Black', 'hex_code' => '#000000'],
    ['name' => 'White', 'hex_code' => '#FFFFFF'],
    ['name' => 'Gray', 'hex_code' => '#808080'],
];
foreach ($colors as $color) {
    \App\Models\Color::create($color);
}

// Create brands
$brands = ['Nike', 'Adidas', 'Puma', 'Under Armour', 'New Balance', 'Reebok'];
foreach ($brands as $brand) {
    \App\Models\Brand::create([
        'name' => $brand,
        'slug' => Str::slug($brand),
        'is_active' => true,
    ]);
}

// Create categories
$categories = ['Nam', 'Nữ', 'Trẻ em'];
foreach ($categories as $cat) {
    \App\Models\Category::create([
        'name' => $cat,
        'slug' => Str::slug($cat),
        'is_active' => true,
    ]);
}

// Create sample banner
\App\Models\Banner::create([
    'title' => 'Mùa hè 2024',
    'subtitle' => 'Giảm giá tới 50%',
    'image_url' => 'https://via.placeholder.com/1200x400',
    'link' => '/products',
    'cta_text' => 'Mua ngay',
    'position' => 'hero',
    'is_active' => true,
]);

// Create sample products (repeat as needed)
for ($i = 1; $i <= 20; $i++) {
    $product = \App\Models\Product::create([
        'name' => "Áo thể thao nam $i",
        'slug' => "ao-the-thao-nam-$i",
        'description' => "Mô tả sản phẩm áo thể thao nam $i",
        'price' => rand(150000, 500000),
        'sale_price' => rand(100000, 400000),
        'sku' => "SKU-" . str_pad($i, 5, '0', STR_PAD_LEFT),
        'image_url' => "https://via.placeholder.com/300x300?text=Product+$i",
        'is_active' => true,
        'stock_quantity' => rand(10, 100),
        'category_id' => rand(1, 3),
        'brand_id' => rand(1, 6),
    ]);
    
    // Attach random colors and sizes
    $product->colors()->attach(random.randint(1, 5));
    $product->sizes()->attach(random.randint(1, 6));
}

exit
```

---

## Phase 7: Testing (30 mins)

### Homepage Tests
- [ ] Visit `/` - Should load successfully
- [ ] See 8 sections: banner, categories, latest products, ad banner, bestsellers, brands, testimonials, newsletter
- [ ] Banner slider auto-rotates every 5 seconds
- [ ] Click banner navigation dots - switches slides
- [ ] Click banner prev/next buttons - switches slides
- [ ] "View All" buttons link to `/products`
- [ ] Product cards show images, names, prices, ratings
- [ ] Testimonials slider auto-rotates
- [ ] Click testimonial dots - switches review
- [ ] Newsletter form visible and functional

### Products Page Tests
- [ ] Visit `/products` - Should load with Livewire component
- [ ] See filtered products in grid view
- [ ] See sidebar with all filter options

#### Category Filter
- [ ] Click category radio button - filters products
- [ ] URL updates with ?category=1
- [ ] Products count decreases
- [ ] Select "All Categories" - resets filter

#### Brand Filter
- [ ] Check brand checkbox - adds to filter
- [ ] Multiple brands can be selected
- [ ] URL shows ?brand[]=1&brand[]=2
- [ ] Uncheck brand - removes from filter

#### Size Filter
- [ ] Click size button - toggles selection
- [ ] Multiple sizes can be selected
- [ ] URL updates with ?size[]=1&size[]=2

#### Color Filter
- [ ] Click color swatch - toggles selection
- [ ] Multiple colors can be selected
- [ ] Selected color has ring highlight

#### Price Range Filter
- [ ] Enter min price - filters products
- [ ] Enter max price - filters products
- [ ] URL updates with ?price_min=100000&price_max=500000

#### Search Filter
- [ ] Type in search box - real-time filtering
- [ ] Products update as you type
- [ ] ?keyword=shirt in URL

#### Sort
- [ ] Select "Mới nhất" - sorts by date desc
- [ ] Select "Giá thấp→cao" - sorts by price asc
- [ ] Select "Giá cao→thấp" - sorts by price desc
- [ ] Select "Phổ biến" - sorts by view count
- [ ] Select "Đánh giá cao" - sorts by rating

#### View Toggle
- [ ] Grid view button active by default
- [ ] List view button shows horizontal list
- [ ] Switching between views works smoothly

#### Pagination
- [ ] Next button navigates to page 2
- [ ] Page 2 shows "?page=2" in URL
- [ ] Previous button returns to page 1
- [ ] Page numbers clickable

#### Combined Filters
- [ ] Select category + brand - both apply
- [ ] Set price range + search - both apply
- [ ] All filters work together
- [ ] Result count updates correctly
- [ ] URL shows all parameters

#### Edge Cases
- [ ] Click "Clear filters" - resets all
- [ ] Browser back button - restores previous filter
- [ ] Share filtered URL - filters apply when opened
- [ ] No results state shows properly
- [ ] Reset filters button appears when needed

---

## Phase 8: Performance Check (15 mins)

- [ ] Products load within 2 seconds
- [ ] Filter updates within 500ms
- [ ] No N+1 queries in logs
- [ ] Images lazy load (optional enhancement)
- [ ] no console errors in browser dev tools

---

## Phase 9: Mobile Responsive (15 mins)

Test on mobile device or use Chrome DevTools:

- [ ] Homepage sections stack properly on mobile
- [ ] Banner hero section responsive
- [ ] Product grid becomes 1 column on mobile
- [ ] Sidebar filters hidden on mobile (show with button/drawer)
- [ ] Search bar visible and functional
- [ ] All buttons clickable with touch
- [ ] No horizontal scrolling
- [ ] Pagination readable on small screens

---

## 🔧 Troubleshooting

### Common Issues & Solutions

#### Issue: "Class not found: HomeController"
**Solution**: 
```bash
php artisan make:controller HomeController
```
Then copy code from app/Http/Controllers/HomeController.php

#### Issue: "No query results for model Product"
**Solution**: Create at least one product in database (either via Tinker or seeding)

#### Issue: "SQLSTATE: table 'products' doesn't exist"
**Solution**:
```bash
php artisan migrate
```

#### Issue: Livewire component not rendering
**Solution**:
- Verify Livewire installed: `composer require livewire/livewire`
- Run: `php artisan livewire:install`
- Check layout includes: `@livewireStyles` and `@livewireScripts`

#### Issue: Product image URL broken
**Solution**:
- Ensure image files exist at specified paths
- Or use placeholder URLs temporarily: `https://via.placeholder.com/300x300`

#### Issue: Filters not updating URL
**Solution**:
- Verify ProductFilter.php uses `#[Url]` attributes correctly
- Run `php artisan view:clear` and `php artisan cache:clear`

#### Issue: Banner slider not auto-rotating
**Solution**:
- Check browser console for Alpine.js errors
- Verify Alpine.js CDN is included in layout
- Ensure banner data exists in database

#### Issue: Testimonials not showing
**Solution**:
- If using default testimonials, they show automatically
- If using Review model, ensure reviews exist in database
- Check Review model relationship to Product and User

---

## 📊 Final Validation

Before considering done:

- [ ] All 8 homepage sections visible and functional
- [ ] Product filtering works across all 5 dimensions (category, brand, size, color, price)
- [ ] URL updates with query parameters
- [ ] Pagination works correctly
- [ ] No console errors in browser
- [ ] Mobile responsive on all screen sizes
- [ ] Database queries optimized (no N+1)
- [ ] All links point to correct routes

---

## 🎉 You're Done!

The homepage and product listing pages are complete and ready for:
- Adding to cart functionality
- Wishlist feature
- User authentication integration
- Payment processing
- Order management

Celebrate! 🚀

---

## 📅 Timeline Estimate

| Phase | Time | Status |
|-------|------|--------|
| Models & Database | 30 mins | ⏳ PENDING |
| Controllers & Routes | 15 mins | ✅ COMPLETE |
| Livewire Components | 0 mins | ✅ COMPLETE |
| Views/Templates | 0 mins | ✅ COMPLETE |
| Seed Data | 20 mins | ⏳ PENDING |
| Testing | 30 mins | ⏳ PENDING |
| Mobile Testing | 15 mins | ⏳ PENDING |
| **TOTAL** | **150 mins** | **~2.5 hours** |

---

## 📞 Resource Files

All reference files available:
- `HOMEPAGE_PRODUCTS_SETUP.md` - Detailed feature documentation
- `MODEL_TEMPLATES.md` - All model and migration code
- `IMPLEMENTATION_CHECKLIST.md` - This file
- `app/Http/Controllers/HomeController.php` - Implementation ready
- `app/Http/Controllers/ProductController.php` - Implementation ready
- `routes/web.php` - Routes configured

Happy coding! 🎊
