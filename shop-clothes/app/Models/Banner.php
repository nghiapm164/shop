<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'image',
        'link',
        'position',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get full image URL
     */
    public function getImageUrlAttribute()
    {
        return asset('storage/' . $this->image);
    }

    /**
     * Scope for banners in a specific position
     */
    public function scopeByPosition($query, $position)
    {
        return $query->where('position', $position)->where('is_active', true)->orderBy('sort_order', 'asc');
    }

    /**
     * Scope for active banners
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get position label
     */
    public static function getPositionLabel($position)
    {
        $positions = [
            'home_top' => 'Banner trên trang chủ',
            'home_middle' => 'Banner giữa trang chủ',
            'home_bottom' => 'Banner dưới trang chủ',
            'category' => 'Banner danh mục',
            'product' => 'Banner sản phẩm',
        ];
        return $positions[$position] ?? $position;
    }
}

