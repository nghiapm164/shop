# 🧪 Product Detail Page - Testing & Deployment Guide

## ✅ Quick Setup (5 minutes)

### 1. Verify Database Structure

Make sure these tables exist with proper fields:

```sql
-- ProductImage must have image_path (not image_url)
SELECT * FROM product_images LIMIT 1;

-- ProductVariant must have color_id, size_id, stock_quantity
SELECT * FROM product_variants LIMIT 1;

-- Review must have is_approved column
SELECT * FROM reviews LIMIT 1;
```

### 2. Verify Models Have Proper Relationships

```bash
php artisan tinker

// Check Product has variants
$product = Product::with('variants', 'images', 'reviews')->first();
$product->variants;  // Should not be empty
$product->images;    // Should not be empty
$product->reviews;   // Should work (even if empty)
```

### 3. Database Seeding (if needed)

If you don't have product variants, create some:

```bash
php artisan tinker

$product = Product::first();

// Add a variant
ProductVariant::create([
    'product_id' => $product->id,
    'size_id' => Size::first()->id,           // Must exist
    'color_id' => Color::first()->id,         // Must exist
    'stock_quantity' => 50,
    'additional_price' => 10000,              // Optional
]);

// Add an image
ProductImage::create([
    'product_id' => $product->id,
    'image_path' => 'products/image.jpg',
    'alt_text' => $product->name,
    'sort_order' => 1,
    'is_primary' => true,
]);

// Add a review
Review::create([
    'product_id' => $product->id,
    'user_id' => User::first()->id,
    'rating' => 5,
    'comment' => 'Excellent product!',
    'is_approved' => true,
]);
```

---

## 🧪 Testing Steps

### **Step 1: Test Page Load**

Visit: `http://localhost:8000/products/[product-slug]`

✅ **Should see:**
- No errors in browser console
- Product name in h1
- Breadcrumb navigation
- Image gallery
- Product details on right

❌ **If error:**
- Check if route exists: `php artisan route:list | grep products.show`
- Check if Product model exists with migrations
- Check if product slug is correct in URL

---

### **Step 2: Test Image Gallery**

✅ **Should work:**
- Click on thumbnail → main image changes
- Click on main image → lightbox opens
- Click X or background → lightbox closes
- SALE/NEW badge visible (if applicable)

❌ **If images don't load:**
- Check: `public/storage` symlink exists
- Verify image_path in database
- Try: `php artisan storage:link`

---

### **Step 3: Test Color Selection**

✅ **Should work:**
- Color swatches display (if variants exist)
- Click color → highlights with red ring
- Selecting color → size options update
- Invalid swatches disabled (if no stock)

❌ **If colors don't show:**
- Create ProductVariant with color_id
- Ensure Color model exists with data
- Check: `ProductVariant::where('product_id', X)->get();`

---

### **Step 4: Test Size Selection**

✅ **Should work:**
- Size buttons appear after color selected
- Click size → button highlights
- "Size Guide" link opens modal
- Modal shows size chart table
- Modal closes on X or background click

❌ **If sizes don't appear:**
- Create ProductVariant with size_id
- Ensure Size model exists with data
- Make sure color is selected first

---

### **Step 5: Test Quantity & Add to Cart**

✅ **Should work:**
- +/- buttons change quantity
- Quantity buttons disabled if qty = max stock
- "Add to Cart" button enabled (if color + size selected)
- Flash notification appears after clicking
- Cart badge in navbar updates

❌ **If cart not working:**
- Check browser console for JavaScript errors
- Verify Livewire is loaded in layout
- Ensure `/checkout` route exists (for "Buy Now")

---

### **Step 6: Test Pricing Display**

✅ **Should show:**
- Sale price in large red text (if exists)
- Original price crossed out (if on sale)
- "Tiết kiệm X%" badge (if on sale)
- Correct currency format with ₫

❌ **If prices wrong:**
- Check Product model has sale_price field
- Verify product has price data

---

### **Step 7: Test Reviews Tab**

✅ **Should show:**
- Click "Đánh giá" tab → reviews display
- Average rating star (with 1-5 stars)
- Review count
- Rating distribution chart
- "Write Review" button

❌ **If reviews not showing:**
- Create Review with is_approved = true
- Ensure user relationship exists
- Check Review model has proper relationships

---

### **Step 8: Test Write Review Form**

✅ **Should work:**
- Click "Write Review" → form appears
- Can select 1-5 stars
- Can type comment (min 10 chars)
- Submit button works
- Review appears in list (if approved automatically)
- Only shows for authenticated users
- Only shows for users who bought product

❌ **If form not working:**
- Check if user is authenticated (login required)
- Check if user has order with this product
- Verify validation messages show
- Check console for errors

---

### **Step 9: Test Tabs**

✅ **All three tabs should work:**
1. **Mô tả** - Shows product description
2. **Thông số** - Shows specs table (material, origin, care)
3. **Đánh giá** - Shows reviews (Livewire component)

❌ **If tabs not switching:**
- Check Alpine.js is loaded
- Verify x-data and x-show directives work
- Check browser console for errors

---

### **Step 10: Test Related Products**

✅ **Should show:**
- Grid of 4 products from same category
- Product images with hover zoom
- Product names and prices
- SALE badges (if applicable)
- Click product → navigate to detail page

❌ **If none showing:**
- Ensure product has category_id
- Ensure other products exist in same category
- Check query: `Product::where('category_id', X)->limit(4)`

---

### **Step 11: Mobile Testing**

Use Chrome DevTools (F12) → Toggle device toolbar

✅ **Mobile should show:**
- Single column layout
- Image gallery stacks properly
- Color/size options readable
- Buttons fully clickable
- Reviews readable
- No horizontal scrolling

❌ **If mobile broken:**
- Check responsive classes (md:, lg:)
- Test on actual device (not just emulation)
- Check viewport meta tag in layout

---

### **Step 12: Accessibility Testing**

✅ **Should support:**
- Keyboard navigation (Tab through elements)
- Screen reader (alt text on images)
- Color contrast ratios met
- Form labels associated

❌ **If issues:**
- Add alt text to images
- Add proper label tags
- Check color contrast with WebAIM

---

## 🚀 Deployment Checklist

Before going live:

- [ ] Page loads without errors
- [ ] All relationships working (images, variants, reviews)
- [ ] Add to cart functionality working
- [ ] Cart badge updates
- [ ] Responsive on mobile
- [ ] No console errors
- [ ] Images loaded correctly
- [ ] All tabs display content
- [ ] Reviews showing (if data exists)
- [ ] Breadcrumb navigation working
- [ ] Related products displaying
- [ ] Pricing displays correctly
- [ ] Color/size selection working

---

## 🔗 Integration Points

### **Required Routes:**
```php
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/', HomeController::class)->name('home');
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
```

### **Required Database Tables:**
- `products` (with sale_price column)
- `product_images` (with image_path)
- `product_variants` (with size_id, color_id, stock_quantity)
- `reviews` (with is_approved)
- `users` (for review authors)
- `categories`
- `brands`
- `colors`
- `sizes`

### **Required Frontend:**
- `layouts.app` (extends)
- Alpine.js (for image gallery)
- Livewire styles/scripts (cart + reviews)
- Tailwind CSS

---

## 🆘 Troubleshooting

### **Page shows 404**
```bash
php artisan route:clear
php artisan cache:clear
php artisan route:list | grep products
```

### **Images not loading**
```bash
php artisan storage:link
# Check: public/storage exists and is symbolic link
ls -la public/storage
```

### **Livewire components not rendering**
```bash
php artisan livewire:install
# Check layout has: @livewireStyles and @livewireScripts
```

### **Cart not updating**
- Ensure cart handler listening for 'cart-item-added' event
- Check event is dispatched: `$this->dispatch('cart-item-added', [...])`
- Verify cart component listening: `#[On('cart-item-added')]`

### **Reviews not showing after submit**
- Check: Review `is_approved = true` in database
- Check: User relationship exists and loaded
- Check: Review created successfully (check reviews table)

### **Size Guide modal not opening**
- Check Alpine.js loaded in layout
- Check browser console for errors
- Verify x-data directive syntax

---

## 📊 Data Validation

**Before testing, ensure:**

```sql
-- At least 1 product
SELECT COUNT(*) FROM products;

-- Product has variants with colors and sizes
SELECT COUNT(*) FROM product_variants WHERE product_id = 1;

-- Product has images
SELECT COUNT(*) FROM product_images WHERE product_id = 1;

-- Sizes exist
SELECT COUNT(*) FROM sizes;

-- Colors exist
SELECT COUNT(*) FROM colors;

-- Reviews exist and approved
SELECT COUNT(*) FROM reviews WHERE is_approved = true;

-- Users exist (for reviews)
SELECT COUNT(*) FROM users;
```

---

## 📝 Sample Test Data

If you need to create test data quickly:

```bash
php artisan tinker

// Create product with all relationships
$product = Product::create([
    'name' => 'Áo thể thao test',
    'slug' => 'ao-the-thao-test',
    'description' => 'Mô tả sản phẩm test',
    'price' => 500000,
    'sale_price' => 350000,
    'sku' => 'TEST-001',
    'category_id' => 1,
    'brand_id' => 1,
    'is_active' => true,
]);

// Create variants (size + color combos)
ProductVariant::create([
    'product_id' => $product->id,
    'size_id' => 1,
    'color_id' => 1,
    'stock_quantity' => 100,
]);

// Create image
ProductImage::create([
    'product_id' => $product->id,
    'image_path' => 'products/test.jpg',
    'is_primary' => true,
]);

// Create review
Review::create([
    'product_id' => $product->id,
    'user_id' => 1,
    'rating' => 5,
    'comment' => 'Sản phẩm tuyệt vời, giao hàng nhanh chóng!',
    'is_approved' => true,
]);
```

Then visit: `http://localhost:8000/products/ao-the-thao-test`

---

## ✨ What You Should See

### **Full Page Layout:**
```
[Breadcrumb: Trang chủ > Danh mục > Tên SP]

[IMAGE GALLERY]     [PRODUCT INFO]
  [Big Image]       Brand Name
  ┌─────────┐       Product Name
  │         │       ★★★★★ (5 reviews)
  │         │       
  │         │       350,000₫  500,000₫
  │         │       Tiết kiệm 30%
  └─────────┘       
  [■] [■] [■]       Color Swatches
  (thumbnails)      Size Buttons
                    Quantity: 1
                    [Add to Cart] [Buy Now]
                    [❤️ Wishlist] [Facebook] [Zalo] [Link]

[Tabs: Description | Specs | Reviews]
Description content...

[Related Products: 4-item grid]
```

If you see this structure, the page is working! ✅

---

## 🎉 Success Criteria

**The page is ready for production when:**
1. ✅ No JavaScript errors in console
2. ✅ All images load correctly
3. ✅ Color/size selection functional
4. ✅ Add to cart works
5. ✅ Reviews display (if data exists)
6. ✅ Mobile responsive
7. ✅ Tabs switch smoothly
8. ✅ Related products visible
9. ✅ Pricing displays correctly
10. ✅ Breadcrumb navigation works

---

## 📞 Need Help?

Check the main documentation: **PRODUCT_DETAIL_PAGE.md**

Or test individual components:

```bash
php artisan tinker

# Test product
$p = Product::first();
$p->images;    // Should have images
$p->variants;  // Should have variants  
$p->reviews;   // Should show reviews
```

---

**Happy Testing!** 🚀
