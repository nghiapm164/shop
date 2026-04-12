# Layout & Components Documentation

## 📋 Overview

Complete layout and component system for SportWear Shop website with modern, minimalist design using:
- **Colors**: Black-White with Red (#E11D48) accent
- **Framework**: Tailwind CSS
- **Interactivity**: Alpine.js + Livewire
- **Responsive**: Mobile, Tablet, Desktop

---

## 🏗️ Layout: `layouts/app.blade.php`

Main layout for all pages with SEO optimization, sticky header, and responsive footer.

### Features:
- ✅ Dynamic SEO meta tags (title, description, keywords, OG)
- ✅ Tailwind CSS CDN with custom color theme
- ✅ Alpine.js for interactivity
- ✅ Livewire styles/scripts
- ✅ Responsive design with mobile menu

### Usage:
```blade
@extends('layouts.app')

@section('meta_title', 'Product Page')
@section('meta_description', 'Browse our sportswear collection')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <h1>Welcome</h1>
</div>
@endsection
```

### Available Sections:
- `meta_title` - Page title for SEO
- `meta_description` - Page description for SEO
- `meta_keywords` - Comma-separated keywords
- `og_type` - OpenGraph type (website, product, etc)
- `og_image` - OpenGraph image URL
- `content` - Page content
- `styles` - Custom page styles

---

## 🧩 Components

### 1. **Navbar** - `components/navbar.blade.php`

Sticky header with navigation menu, search, cart, and user dropdown.

#### Features:
- Logo + Brand Name (left)
- Desktop Menu:
  - Trang chủ (Home)
  - Sản phẩm (Products) - with Mega Menu dropdown
  - Thương hiệu (Brands)
  - Sale (red highlight)
- Right Section:
  - Search bar (desktop)
  - Wishlist icon with badge
  - Cart icon with Livewire badge
  - User dropdown (auth/guest)
- Mobile:
  - Hamburger menu
  - Slide-in drawer with main menu
  - Search icon

#### Usage:
```blade
<x-navbar />

<!-- Or with custom configuration (extend component) -->
```

#### Mega Menu:
Automatically displays on hover "Sản phẩm" with 2-column category grid (desktop).

#### Customization:
Edit links in navbar component to match your routes:
```blade
<!-- Update home route -->
<a href="{{ route('home') }}" ...>

<!-- Update category links inside mega menu -->
```

---

### 2. **Footer** - `components/footer.blade.php`

Responsive footer with 4-column layout, newsletter signup, and bottom bar.

#### Features:
- Brand info + social links (left)
- Danh mục (Categories) links
- Thông tin (Information) links
- Liên hệ (Contact) info with icons
- Newsletter signup form
- Bottom bar with copyright + policy links
- Scroll-to-top button (appears when scrolled > 300px)

#### Usage:
```blade
<x-footer />
```

#### Customize:
- Update social links
- Add your contact information
- Modify footer links and categories
- Update newsletter form action

---

### 3. **Product Card** - `components/product-card.blade.php`

Product card component with images, badges, ratings, and action buttons.

#### Props:
- `$product` (required) - Product Model instance

#### Features:
- **Images**:
  - Primary image with lazy loading
  - Secondary image shows on hover
  - Smooth zoom effect
  
- **Badges** (top-left):
  - `NEW` - If created within 7 days
  - `SALE` - If has sale_price
  - Discount percentage `-XX%`
  - `🔥 HOT` - If view_count > 1000 or sales_count > 100
  
- **Product Info**:
  - Category link
  - Product name (line-clamped to 2 lines)
  - Star rating + review count
  - Price (with strikethrough if on sale)
  - Color swatches (first 5 colors)
  
- **Actions**:
  - "Thêm giỏ" (Add to Cart) - Livewire event
  - Wishlist heart button - Toggle wishlist via Livewire
  
- **Hover**:
  - "Xem chi tiết" (View Details) button overlay
  - Scale zoom effect

#### Usage:
```blade
<!-- In a loop -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    @foreach($products as $product)
        <x-product-card :product="$product" />
    @endforeach
</div>
```

#### Model Requirements:
Product model should have these attributes/methods:
```php
$product->id;
$product->name;
$product->slug;
$product->price;
$product->sale_price; // nullable
$product->image_url; // or $product->image
$product->secondary_image_url; // optional
$product->category->name; // relation
$product->colors(); // relation with hex_code
$product->average_rating; // calculated
$product->review_count; // count
$product->view_count; // for HOT badge
$product->sales_count; // for HOT badge
$product->created_at; // for NEW badge
```

#### Livewire Events Required:
```php
// In Livewire component
#[OnPost('addToCart')]
public function addToCart($productId) { ... }

#[OnPost('toggleWishlist')]
public function toggleWishlist($productId) { ... }
```

---

### 4. **Breadcrumb** - `components/breadcrumb.blade.php`

Navigation breadcrumb component showing page hierarchy.

#### Props:
- `$items` (array) - Breadcrumb items

#### Usage:
```blade
<x-breadcrumb :items="[
    ['label' => 'Sản phẩm', 'url' => route('products')],
    ['label' => 'Áo thun', 'url' => route('products', ['category' => 'ao-thun'])],
    ['label' => 'Áo thun nam basic'],
]" />
```

#### Item Structure:
```php
[
    'label' => 'Page Name',           // Display text
    'url' => route('...'),            // Optional - if not last item
]

// Last item should NOT have 'url' - displays as current page
```

#### Features:
- Always starts with "🏠 Trang chủ"
- "/" separator between items
- Last item bold and non-clickable
- Hover effects and color transitions

---

### 5. **Pagination** - `components/pagination.blade.php`

Custom styled pagination for Eloquent paginated results.

#### Usage with Eloquent:
```php
// In Controller
$products = Product::paginate(12);

// In Blade
<x-pagination :paginator="$products" />
```

#### Features:
- Previous/Next buttons
- Page number buttons (1, 2, 3, ...)
- Current page highlighted in red
- "Three dots" for skipped pages
- Responsive: full on desktop, prev/next only on mobile
- Shows result count: "Showing 1 to 12 of 150 results"

#### Customization:
Modify the component to customize styling or spacing.

---

### 6. **Alert** - `components/alert.blade.php`

Alert component for success, error, warning, and info messages.

#### Types:
- `success` - Green (✓)
- `error` - Red (✕)
- `warning` - Yellow (⚠)
- `info` - Blue (ℹ)

#### Usage - Custom Alert:
```blade
<x-alert type="success" title="Success!">
    Your order has been placed successfully.
</x-alert>

<x-alert type="error">
    Please fill in all required fields.
</x-alert>

<!-- Non-closeable alert -->
<x-alert type="info" :closeable="false">
    Important information about this product.
</x-alert>
```

#### Usage - Flash Messages (from session):
```php
// In Controller
return redirect()->back()->with('success', 'Product added to cart!');

// In Blade - includes automatically in component
<x-alert />

<!-- Alert will show for 5 seconds then auto-dismiss -->
```

#### Flash Message Methods:
```php
session()->put('success', 'Success message');
session()->put('error', 'Error message');
session()->put('warning', 'Warning message');
session()->put('info', 'Info message');
```

#### Props:
- `type` - success|error|warning|info (default: info)
- `title` - Optional title
- `closeable` - Show close button (default: true)

---

### 7. **Star Rating** - `components/star-rating.blade.php`

Star rating display component with optional interactive mode.

#### Props:
- `$rating` - Rating value (0-5)
- `$max` - Maximum stars (default: 5)
- `$interactive` - Allow rating input (default: false)
- `$size` - sm|md|lg|xl (default: md)

#### Usage - Display Rating:
```blade
<!-- Show 4.5 stars -->
<x-star-rating :rating="4.5" />

<!-- Show 5 stars, large size -->
<x-star-rating :rating="5" size="lg" />

<!-- Show 3 stars, small size -->
<x-star-rating :rating="3" size="sm" />
```

#### Usage - Interactive Rating (for reviews):
```blade
<form method="POST" action="{{ route('reviews.store') }}">
    @csrf
    
    <label>Rate this product:</label>
    <x-star-rating :interactive="true" :rating="0" />
    
    <input type="hidden" name="rating" id="rating">
    <button type="submit">Submit Review</button>
</form>
```

#### Features:
- Full stars, Half stars, Empty stars
- Hover effect on star in interactive mode
- Smooth transitions
- Shows rating value (e.g., "4.5")
- Supports various sizes
- Alpine.js interactive rating selection

#### Star Display:
- ⭐ Full star (rating >= star number)
- ⭐ Half star (between 0.5)
- ☆ Empty star (rating < star number)

---

## 🎨 CSS Utility Classes

Pre-defined classes in `layouts/app.blade.php`:

```css
.btn-primary      /* Red button with hover/active states */
.btn-secondary    /* Gray border button */
.btn-ghost        /* Minimal button */
.badge            /* Inline badge base */
.badge-primary    /* Red badge */
.badge-success    /* Green badge */
.badge-warning    /* Yellow badge */
.line-clamp-2     /* Truncate text to 2 lines */
.line-clamp-3     /* Truncate text to 3 lines */
```

### Example Usage:
```blade
<button class="btn-primary">Add to Cart</button>
<button class="btn-secondary">Cancel</button>
<span class="badge-primary">NEW</span>
<span class="badge-success">In Stock</span>
<p class="line-clamp-2">Long product description...</p>
```

---

## 📱 Responsive Design

All components are fully responsive:

- **Mobile** (<768px): Stack layout, hamburger menu, simplified navigation
- **Tablet** (768px-1024px): 2-column grids, adjustable spacing
- **Desktop** (>1024px): Full layout, mega menus, side-by-side content

### Tailwind Breakpoints Used:
- `hidden md:block` - Hide on mobile, show on tablet+
- `hidden lg:flex` - Hide on mobile/tablet, show on desktop
- `max-w-7xl` - Container max width (1280px)
- `grid-cols-1 md:grid-cols-2 lg:grid-cols-4` - Responsive grid

---

## 🔌 Integration with Livewire

For interactive features like cart and wishlist:

### Required Livewire Components:
1. `cart-badge` - Display cart item count
   ```blade
   <livewire:cart-badge />
   ```

2. Product card expects Livewire events:
   ```php
   // In your Livewire component
   #[OnPost('addToCart')]
   public function addToCart($productId) { ... }
   
   #[OnPost('toggleWishlist')]
   public function toggleWishlist($productId) { ... }
   ```

### Example Livewire Component:
```php
<?php
namespace App\Livewire;

use Livewire\Component;

class CartBadge extends Component
{
    public function render()
    {
        return view('livewire.cart-badge', [
            'count' => auth()->user()?->cart?->items?->count() ?? 0,
        ]);
    }
}
```

---

## 🚀 Quick Start

1. **Update Navigation Links**:
   - Edit `components/navbar.blade.php` routes
   - Link to your actual product/category pages

2. **Customize Colors**:
   - In `layouts/app.blade.php`, update tailwind theme config
   - Current red: `#e11d48`

3. **Add Livewire Components**:
   - Create `app/Livewire/CartBadge.php`
   - Create `resources/views/livewire/cart-badge.blade.php`

4. **Create Product Pages**:
   - Use `x-product-card` in index views
   - Use `x-breadcrumb` on detail pages

5. **Test Alerts**:
   ```blade
   <x-alert type="success" title="Success">
       Component is working!
   </x-alert>
   ```

---

## 📝 Notes

- All components use **Blade View Components** syntax (`<x-component-name />`)
- Tailwind CSS configured with extended colors (primary accent red)
- Alpine.js for interactive features (no external JS framework needed)
- Livewire integration ready for real-time features
- Mobile-first responsive design
- SEO-optimized with meta tags in layout

---

## 🔗 File References

```
resources/views/
├── layouts/
│   └── app.blade.php          ← Main layout
├── components/
│   ├── navbar.blade.php       ← Navigation header
│   ├── footer.blade.php       ← Footer
│   ├── product-card.blade.php ← Product card
│   ├── breadcrumb.blade.php   ← Breadcrumb
│   ├── pagination.blade.php   ← Pagination
│   ├── alert.blade.php        ← Alert messages
│   └── star-rating.blade.php  ← Star rating
└── livewire/
    └── cart-badge.blade.php   ← Cart count display
```

---

## Troubleshooting

**Q: Product images not showing?**
- Ensure product model has `image_url` attribute
- Check `storage:link` is created
- Verify asset() path is correct

**Q: Mobile menu not working?**
- Ensure Alpine.js is loaded (check CDN)
- Check browser console for JavaScript errors

**Q: Navbar dropdown not appearing?**
- Use desktop view (menu hidden on mobile)
- Hover over "Sản phẩm" on desktop
- Check tailwind CSS is compiling correctly

**Q: Colors not matching brand?**
- Update primary color in tailwind config section of `layouts/app.blade.php`
- Red accent: change `#e11d48` to your color

---

Enjoy building with SportWear Shop components! 🎉
