<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'logo',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get all products for this brand
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Scope query to active brands
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get active products count
     */
    public function getActiveProductsCountAttribute()
    {
        return $this->products()->where('is_active', true)->count();
    }

    /**
     * Get brand logo URL (supports external URLs and local storage paths).
     */
    public function getLogoUrlAttribute(): string
    {
        if ($this->logo && Str::startsWith($this->logo, ['http://', 'https://'])) {
            return $this->logo;
        }

        if (!empty($this->logo)) {
            return asset('storage/' . $this->logo);
        }

        return asset('images/product-placeholder.svg');
    }
}

