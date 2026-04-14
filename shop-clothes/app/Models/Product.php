<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'short_description',
        'category_id',
        'brand_id',
        'price',
        'sale_price',
        'sku',
        'is_featured',
        'is_active',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get the category that owns the product
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the brand that owns the product
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Get all images for the product
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order', 'asc');
    }

    /**
     * Get the primary image for the product
     */
    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    /**
     * Get all variants for the product
     */
    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    /**
     * Get all reviews for the product
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get users who wishlisted this product
     */
    public function wishlistUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'wishlists', 'product_id', 'user_id')->withTimestamps();
    }

    /**
     * Get order items for this product through variants
     */
    public function orderItems(): HasManyThrough
    {
        return $this->hasManyThrough(
            OrderItem::class,
            ProductVariant::class,
            'product_id', // foreign key on ProductVariant table
            'product_variant_id', // foreignKey on OrderItem
            'id', // local key on Product table
            'id' // local key on ProductVariant table
        );
    }

    /**
     * Get flash sale records related to this product.
     */
    public function flashSales(): HasMany
    {
        return $this->hasMany(FlashSale::class);
    }

    /**
     * Scope query to active products only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope query to featured products only
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope query by category slug
     */
    public function scopeInCategory($query, $slug)
    {
        return $query->whereHas('category', function ($q) use ($slug) {
            $q->where('slug', $slug);
        });
    }

    /**
     * Scope query by price range
     */
    public function scopePriceRange($query, $min = null, $max = null)
    {
        if ($min !== null) {
            $query->where('price', '>=', $min);
        }
        if ($max !== null) {
            $query->where('price', '<=', $max);
        }
        return $query;
    }

    /**
     * Scope query by search keyword
     */
    public function scopeSearch($query, $keyword)
    {
        return $query->where('name', 'like', "%{$keyword}%")
            ->orWhere('description', 'like', "%{$keyword}%")
            ->orWhere('sku', 'like', "%{$keyword}%");
    }

    /**
     * Get the sale price percentage discount
     */
    public function getSalePricePercentAttribute()
    {
        if (!$this->sale_price || !$this->price) {
            return 0;
        }
        return round((($this->price - $this->sale_price) / $this->price) * 100);
    }

    /**
     * Check if product is in stock
     */
    public function getIsInStockAttribute()
    {
        return $this->variants()->sum('stock_quantity') > 0;
    }

    /**
     * Get product colors collection through variants.
     */
    public function getColorsAttribute()
    {
        return $this->variants->pluck('color')->filter()->unique('id')->values();
    }

    /**
     * Primary image URL fallback.
     */
    public function getImageUrlAttribute(): string
    {
        $primary = $this->images->firstWhere('is_primary', true) ?? $this->images->first();

        if ($primary?->image_path && Str::startsWith($primary->image_path, ['http://', 'https://'])) {
            return $primary->image_path;
        }

        return $primary?->image_path
            ? 'storage/' . $primary->image_path
            : 'images/product-placeholder.svg';
    }

    /**
     * Secondary image URL fallback.
     */
    public function getSecondaryImageUrlAttribute(): ?string
    {
        $secondary = $this->images->where('is_primary', false)->first();

        if ($secondary?->image_path && Str::startsWith($secondary->image_path, ['http://', 'https://'])) {
            return $secondary->image_path;
        }

        return $secondary?->image_path
            ? 'storage/' . $secondary->image_path
            : null;
    }

    /**
     * Get the effective price (sale price if exists, else regular price)
     */
    public function getEffectivePriceAttribute()
    {
        return $this->sale_price ?? $this->price;
    }

    /**
     * Get average rating of all reviews
     */
    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    /**
     * Get count of all reviews
     */
    public function getReviewsCountAttribute()
    {
        return $this->reviews()->count();
    }
}

