<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FlashSale extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'product_id',
        'flash_price',
        'start_at',
        'end_at',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'flash_price' => 'decimal:2',
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeRunning($query)
    {
        $now = now();

        return $query->active()
            ->where('start_at', '<=', $now)
            ->where('end_at', '>=', $now);
    }

    public function getOriginalPriceAttribute(): ?float
    {
        if (!$this->relationLoaded('product') || !$this->product) {
            return null;
        }

        return (float) ($this->product->sale_price ?? $this->product->price);
    }

    public function getDiscountPercentAttribute(): int
    {
        $original = $this->original_price;

        if (!$original || $original <= 0 || $this->flash_price >= $original) {
            return 0;
        }

        return (int) round((($original - $this->flash_price) / $original) * 100);
    }

    public function getIsRunningAttribute(): bool
    {
        $now = now();

        return $this->is_active && $this->start_at <= $now && $this->end_at >= $now;
    }

    public function getIsUpcomingAttribute(): bool
    {
        return $this->is_active && $this->start_at > now();
    }

    public function getIsExpiredAttribute(): bool
    {
        return $this->end_at < now();
    }
}
