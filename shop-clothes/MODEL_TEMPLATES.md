# 📦 Model Templates

Use these templates to create the necessary models for the product system. Run the commands below in sequence.

---

## 🚀 Quick Setup Commands

```bash
# Create models with migrations
php artisan make:model Product -m
php artisan make:model Category -m
php artisan make:model Brand -m
php artisan make:model Color -m
php artisan make:model Size -m
php artisan make:model Banner -m
php artisan make:model Review -m
php artisan make:model ProductImage -m

# Create pivot table migrations
php artisan make:migration create_product_color_table
php artisan make:migration create_product_size_table
php artisan make:migration create_order_items_table
```

---

## 📋 Model Code Templates

### 1. Product Model

**File**: `app/Models/Product.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'sale_price',
        'sku',
        'image_url',
        'secondary_image_url',
        'is_active',
        'is_featured',
        'stock_quantity',
        'view_count',
        'sales_count',
        'category_id',
        'brand_id',
        'material',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    // ============ RELATIONSHIPS ============

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

    // ============ COMPUTED ATTRIBUTES ============

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

    // ============ SCOPES ============

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeLatest($query)
    {
        return $query->orderByDesc('created_at');
    }

    public function scopePopular($query)
    {
        return $query->orderByDesc('view_count');
    }

    public function scopeBestSelling($query)
    {
        return $query->withCount('orders')->orderByDesc('orders_count');
    }
}
```

---

### 2. Category Model

**File**: `app/Models/Category.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'parent_id',
        'is_active',
        'icon_url',
        'description',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // ============ RELATIONSHIPS ============

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    // ============ SCOPES ============

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeRoots($query)
    {
        return $query->whereNull('parent_id');
    }
}
```

---

### 3. Brand Model

**File**: `app/Models/Brand.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'logo_url',
        'is_active',
        'description',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // ============ RELATIONSHIPS ============

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    // ============ SCOPES ============

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
```

---

### 4. Color Model

**File**: `app/Models/Color.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Color extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'hex_code',
    ];

    // ============ RELATIONSHIPS ============

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }
}
```

---

### 5. Size Model

**File**: `app/Models/Size.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Size extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'sort_order',
    ];

    // ============ RELATIONSHIPS ============

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }

    // ============ SCOPES ============

    public function scopeSorted($query)
    {
        return $query->orderBy('sort_order');
    }
}
```

---

### 6. Banner Model

**File**: `app/Models/Banner.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'image_url',
        'link',
        'cta_text',
        'position',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // ============ SCOPES ============

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByPosition($query, $position)
    {
        return $query->where('position', $position);
    }
}
```

---

### 7. Review Model

**File**: `app/Models/Review.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'rating',
        'comment',
        'is_approved',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
    ];

    // ============ RELATIONSHIPS ============

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ============ SCOPES ============

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopeHighestRated($query)
    {
        return $query->orderByDesc('rating');
    }
}
```

---

### 8. ProductImage Model

**File**: `app/Models/ProductImage.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'image_url',
        'sort_order',
        'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    // ============ RELATIONSHIPS ============

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // ============ SCOPES ============

    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }
}
```

---

## 🗄️ Migration Templates

### Products Table

**File**: `database/migrations/YYYY_MM_DD_HHMMSS_create_products_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 12, 2);
            $table->decimal('sale_price', 12, 2)->nullable();
            $table->string('sku')->unique();
            $table->string('image_url')->nullable();
            $table->string('secondary_image_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->integer('stock_quantity')->default(0);
            $table->integer('view_count')->default(0);
            $table->integer('sales_count')->default(0);
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('brand_id')->nullable()->constrained()->onDelete('set null');
            $table->string('material')->nullable();
            $table->timestamps();
            
            // Indexes for better query performance
            $table->index('category_id');
            $table->index('brand_id');
            $table->index('is_active');
            $table->index('is_featured');
            $table->fullText(['name', 'description']); // for full text search
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
```

### Categories Table

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->foreignId('parent_id')->nullable()->constrained('categories')->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->string('icon_url')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->index('parent_id');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
```

### Brands Table

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('logo_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('brands');
    }
};
```

### Colors Table

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('colors', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('hex_code', 7); // #FFFFFF format
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('colors');
    }
};
```

### Sizes Table

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sizes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('sort_order')->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sizes');
    }
};
```

### Banners Table

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->string('image_url');
            $table->string('link')->nullable();
            $table->string('cta_text')->default('Xem chi tiết');
            $table->enum('position', ['hero', 'middle', 'bottom'])->default('hero');
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->index('position');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
```

### Reviews Table

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('rating')->min(1)->max(5);
            $table->text('comment')->nullable();
            $table->boolean('is_approved')->default(false);
            $table->timestamps();
            
            $table->index('product_id');
            $table->index('user_id');
            $table->index('is_approved');
            $table->unique(['product_id', 'user_id']); // One review per product per user
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
```

### Product Images Table

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('image_url');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
            
            $table->index('product_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_images');
    }
};
```

### Pivot Tables

#### Product Color

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_color', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('color_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->primary(['product_id', 'color_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_color');
    }
};
```

#### Product Size

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_size', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('size_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->primary(['product_id', 'size_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_size');
    }
};
```

---

## ✅ Setup Sequence

1. Create all models and migrations:
   ```bash
   php artisan make:model Product -m
   php artisan make:model Category -m
   # ... etc
   ```

2. Replace migration contents with templates above

3. Run migrations:
   ```bash
   php artisan migrate
   ```

4. Replace model contents with templates above

5. Seed initial data (optional):
   ```bash
   php artisan tinker
   ```
   Then create sample data via the models

All models are now ready for use with HomePage and ProductFilter components! 🎉
