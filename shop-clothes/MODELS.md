# Eloquent Models Documentation - Sportswear Shop

## 📋 Models Overview

### Core Models (5)

#### 1. **Product**
```php
$product = Product::active()->featured()->inCategory('shirts')->search('nike')->first();
$discount = $product->sale_price_percent; // Get discount %
$inStock = $product->is_in_stock; // Check stock
$images = $product->images; // All images
$variants = $product->variants; // All variants
$users = $product->wishlist_users; // Users who wishlisted
```

**Relationships:**
- `belongsTo(Category)` - owning category
- `belongsTo(Brand)` - owning brand
- `hasMany(ProductImage)` - product images
- `hasMany(ProductVariant)` - product variants
- `hasMany(Review)` - reviews
- `belongsToMany(User, 'wishlists')` - wishlisted by users

**Scopes:**
- `active()` - only active products
- `featured()` - only featured products
- `inCategory($slug)` - by category slug
- `priceRange($min, $max)` - by price range
- `search($keyword)` - search by name/description/sku

**Accessors:**
- `sale_price_percent` - percentage discount
- `is_in_stock` - check if any variant in stock
- `effective_price` - sale price or regular price

---

#### 2. **Category**
```php
$active = Category::active()->get(); // Only active
$children = $category->children; // Direct children
$allProducts = $category->allProducts(); // Products from all subcategories
$breadcrumb = $category->getBreadcrumb(); // Parent chain
$ancestors = $category->getAncestors(); // All parent categories
```

**Relationships:**
- `belongsTo(Category, 'parent_id')` - parent category
- `hasMany(Category, 'parent_id')` - child categories
- `hasMany(Product)` - products in category

**Scopes:**
- `active()` - only active categories

---

#### 3. **Brand**
```php
$brand = Brand::active()->first();
$products = $brand->products; // All products
$activeCount = $brand->active_products_count; // Count active products
```

**Relationships:**
- `hasMany(Product)` - brand products

**Scopes:**
- `active()` - only active brands

---

#### 4. **Order**
```php
$orders = Order::byStatus(Order::STATUS_PENDING)->thisMonth()->get();
$order = Order::where('order_code', 'ORD-001')->first();
$label = Order::getStatusLabel($order->status); // "Chờ xác nhận"
$items = $order->items; // Order items
$user = $order->user; // Ordering user
```

**Constants:**
- `STATUS_PENDING` - chờ xác nhận
- `STATUS_CONFIRMED` - đã xác nhận
- `STATUS_PROCESSING` - đang xử lý
- `STATUS_SHIPPED` - đã gửi
- `STATUS_DELIVERED` - đã giao
- `STATUS_CANCELLED` - đã huỷ
- `STATUS_REFUNDED` - đã hoàn tiền
- `PAYMENT_STATUS_UNPAID`, `PAYMENT_STATUS_PAID`, `PAYMENT_STATUS_REFUNDED`

**Relationships:**
- `belongsTo(User)` - customer
- `hasMany(OrderItem)` - order items

**Scopes:**
- `byStatus($status)` - by status
- `thisMonth()` - orders this month
- `paid()` - paid orders
- `completed()` - delivered orders

---

#### 5. **User**
```php
$user = User::with('orders', 'addresses', 'review', 'wishlist')->first();
$orders = $user->orders; // All orders
$defaultAddress = $user->defaultAddress; // Default shipping address
$wishlist = $user->wishlist; // Wishlisted products
$isAdmin = $user->isAdmin(); // Check role
```

**Relationships:**
- `hasMany(Order)` - user orders
- `hasMany(Address)` - shipping addresses
- `hasOne(Address, is_default)` - default address
- `hasMany(Review)` - user reviews
- `belongsToMany(Product, 'wishlists')` - wishlisted products
- `HasRoles` (Spatie Permission) - user roles

**Helper Methods:**
- `isAdmin()`, `isStaff()`, `isCustomer()` - check role

---

### Product Details (4)

#### 6. **ProductVariant**
```php
$variant = ProductVariant::inStock()->first();
$price = $variant->final_price; // Price with size/color surcharge
$label = $variant->label; // "Shirt - Large - Red"
$sku = $variant->full_sku; // "PROD-XL-Red"
```

**Relationships:**
- `belongsTo(Product)` - product
- `belongsTo(Size)` - size
- `belongsTo(Color)` - color
- `hasMany(OrderItem)` - order items

**Scopes:**
- `inStock()` - stock > 0
- `outOfStock()` - stock = 0

**Accessors:**
- `final_price` - total price including surcharge
- `is_in_stock` - check if in stock
- `full_sku` - complete SKU
- `label` - readable variant name

---

#### 7. **ProductImage**
```php
$primary = $product->primaryImage;
$url = $image->image_url; // Full URL
$image->makePrimary(); // Set as primary image
```

**Relationships:**
- `belongsTo(Product)` - product

**Methods:**
- `makePrimary()` - set this as primary

**Scopes:**
- `primary()` - only primary images

---

#### 8. **Size**
```php
$sizes = Size::all(); // All sizes (XS, S, M, L, XL, XXL, XXXL)
$variants = $size->variants; // All product variants with this size
```

**Relationships:**
- `hasMany(ProductVariant)` - variants with this size

---

#### 9. **Color**
```php
$colors = Color::all();
$rgb = $color->rgb; // ['r' => 255, 'g' => 0, 'b' => 0]
```

**Relationships:**
- `hasMany(ProductVariant)` - variants with this color

**Accessors:**
- `rgb` - RGB color from hex

---

### Order Details (2)

#### 10. **OrderItem**
```php
$item = $order->items()->first();
$variant = $item->productVariant; // Product variant details
$total = $item->total; // price * quantity
$variantLabel = $item->variant_label; // "M - Red"
```

**Relationships:**
- `belongsTo(Order)` - order
- `belongsTo(ProductVariant)` - variant details

**Accessors:**
- `variant_label` - readable variant
- `total` - line total price

---

#### 11. **Address**
```php
$addresses = $user->addresses;
$defaultAddress = $user->addresses()->default()->first();
$address->makeDefault(); // Set as default
$full = $address->full_address; // Complete address string
```

**Relationships:**
- `belongsTo(User)` - address owner

**Methods:**
- `makeDefault()` - set as default for user

**Scopes:**
- `default()` - default addresses
- `forUser($userId)` - addresses for user

**Accessors:**
- `full_address` - complete address

---

### Commerce Models (3)

#### 12. **Review**
```php
$reviews = Review::approved()->byRating(5)->get();
$highRated = Review::highRated()->get(); // 4-5 stars
$pending = Review::pending()->get(); // Pending approval
$stars = $review->rating_stars; // ★★★★★
```

**Relationships:**
- `belongsTo(User)` - reviewer
- `belongsTo(Product)` - reviewed product
- `belongsTo(Order)` - related order

**Scopes:**
- `approved()` - approved reviews
- `pending()` - pending approval
- `byRating($rating)` - by star rating
- `highRated()` - 4-5 stars
- `lowRated()` - 1-2 stars

**Accessors:**
- `rating_stars` - star visualization

---

#### 13. **Coupon**
```php
// Check coupon validity
$result = $coupon->checkValid($orderAmount); // Returns ['valid' => bool, 'message' => string, 'discount' => decimal]

// Usage
if ($result['valid']) {
    $discount = $result['discount'];
    $coupon->incrementUsage();
}

// Properties
$text = $coupon->discount_text; // "20%" or "50000đ"
$expired = $coupon->is_expired; // Check expiration
$usedUp = $coupon->is_used_up; // Check usage limit

// Queries
$active = Coupon::active()->valid()->get(); // Valid coupons
$percent = Coupon::percent()->get(); // Percent-off coupons
$fixed = Coupon::fixed()->get(); // Fixed-amount coupons
```

**Methods:**
- `checkValid($orderAmount)` - validate coupon for amount
  - Checks: active, dates, usage limit, min amount
  - Returns: ['valid', 'message', 'discount']
- `incrementUsage()` - add to usage count

**Scopes:**
- `active()` - active coupons
- `valid()` - active and not expired
- `percent()` - percent-type coupons
- `fixed()` - fixed-amount coupons

**Accessors:**
- `discount_text` - formatted discount display
- `is_expired` - check expiration date
- `is_used_up` - check usage limit reached

---

#### 14. **Wishlist**
```php
$wishlists = Wishlist::where('user_id', $userId)->get();
$user->wishlist()->attach($productId); // Add to wishlist
$user->wishlist()->detach($productId); // Remove from wishlist
$user->wishlist()->toggle($productId); // Toggle wishlist
```

**Relationships:**
- `belongsTo(User)` - user
- `belongsTo(Product)` - product

---

#### 15. **Banner**
```php
$homeTop = Banner::byPosition('home_top')->get(); // Get banners in position
$active = Banner::active()->get(); // All active banners
$label = Banner::getPositionLabel('home_top'); // "Banner trên trang chủ"
```

**Positions:**
- `home_top` - Top of homepage
- `home_middle` - Middle of homepage
- `home_bottom` - Bottom of homepage
- `category` - Category pages
- `product` - Product pages

**Scopes:**
- `byPosition($position)` - banners in position
- `active()` - active banners

**Accessors:**
- `image_url` - full image URL

---

## Common Patterns

### Eager Loading
```php
// Load relationships to avoid N+1
$products = Product::with('category', 'brand', 'variants', 'images')->get();
```

### Filtering & Searching
```php
$products = Product::active()
    ->inCategory('shirts')
    ->priceRange(100000, 500000)
    ->search('nike')
    ->orderBy('created_at', 'desc')
    ->paginate(15);
```

### Order Management
```php
$order = Order::create([
    'user_id' => $user->id,
    'order_code' => 'ORD-' . time(),
    'subtotal' => 1000000,
    'total' => 950000,
    'shipping_address' => $address->toArray(),
    'status' => Order::STATUS_PENDING,
    'payment_method' => 'cod',
]);

$order->items()->create([
    'product_variant_id' => $variant->id,
    'product_name' => $variant->product->name,
    'size' => $variant->size->name,
    'color' => $variant->color->name,
    'price' => $variant->final_price,
    'quantity' => 2,
    'subtotal' => $variant->final_price * 2,
]);
```

### Wishlist Management
```php
// Add to wishlist
$user->wishlist()->attach($product->id);

// Check if in wishlist
$inWishlist = $user->wishlist()->where('product_id', $product->id)->exists();

// Get wishlisted products
$products = $user->wishlist;
```

### Coupon Validation
```php
$coupon = Coupon::where('code', $code)->firstOrFail();

$result = $coupon->checkValid($orderTotal);

if ($result['valid']) {
    $discount = $result['discount'];
    $finalTotal = $orderTotal - $discount;
    $coupon->incrementUsage();
} else {
    // Show error message
    return error($result['message']);
}
```

---

## Configuration Checklist

- [x] All models created
- [x] All relationships configured
- [x] All scopes implemented
- [x] All casts configured
- [x] All accessors implemented
- [x] Constants defined for Order statuses
- [x] Helper methods created
- [x] Spatie Permission integrated for User
- [ ] Factories created (if needed)
- [ ] Policies created (if needed)
- [ ] Events created (if needed)
