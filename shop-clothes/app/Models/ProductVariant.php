<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'size_id',
        'color_id',
        'stock_quantity',
        'additional_price',
    ];

    protected $casts = [
        'additional_price' => 'decimal:2',
    ];

    /**
     * Get the product this variant belongs to
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the size for this variant
     */
    public function size(): BelongsTo
    {
        return $this->belongsTo(Size::class);
    }

    /**
     * Get the color for this variant
     */
    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class);
    }

    /**
     * Get order items for this variant
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the final price for this variant
     */
    public function getFinalPriceAttribute()
    {
        $basePrice = $this->product->sale_price ?? $this->product->price;
        return $basePrice + $this->additional_price;
    }

    /**
     * Check if variant is in stock
     */
    public function getIsInStockAttribute()
    {
        return $this->stock_quantity > 0;
    }

    /**
     * Get SKU with size and color
     */
    public function getFullSkuAttribute()
    {
        return $this->product->sku . '-' . $this->size->code . '-' . $this->color->name;
    }

    /**
     * Get a human-readable label for this variant
     */
    public function getLabelAttribute()
    {
        return "{$this->product->name} - {$this->size->name} - {$this->color->name}";
    }

    /**
     * Scope for in-stock variants
     */
    public function scopeInStock($query)
    {
        return $query->where('stock_quantity', '>', 0);
    }

    /**
     * Scope for out-of-stock variants
     */
    public function scopeOutOfStock($query)
    {
        return $query->where('stock_quantity', '<=', 0);
    }
}

