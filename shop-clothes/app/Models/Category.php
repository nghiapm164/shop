<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'parent_id',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the full image url.
     */
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return null;
        }

        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }

        return asset('storage/' . $this->image);
    }

    /**
     * Get parent category
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Get child categories
     */
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('sort_order', 'asc');
    }

    /**
     * Get all products in this category
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get all products including from subcategories
     */
    public function allProducts()
    {
        $products = $this->products;
        
        foreach ($this->children as $child) {
            $products = $products->merge($child->allProducts());
        }
        
        return $products;
    }

    /**
     * Danh mục gốc đang hoạt động kèm tổng số sản phẩm đang bán (gồm cả danh mục con),
     * sắp xếp theo số sản phẩm giảm dần.
     */
    public static function rootsWithProductCount(int $limit = 6)
    {
        return static::where('is_active', true)
            ->whereNull('parent_id')
            ->with('children:id,parent_id')
            ->get()
            ->each(function (Category $category) {
                $category->products_count = Product::where('is_active', true)
                    ->whereIn('category_id', $category->children->pluck('id')->push($category->id))
                    ->count();
            })
            ->sortByDesc('products_count')
            ->take($limit)
            ->values();
    }

    /**
     * Scope query to active categories
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get all parent categories (ancestors)
     */
    public function getAncestors()
    {
        $ancestors = [];
        $current = $this;
        
        while ($current->parent) {
            $ancestors[] = $current->parent;
            $current = $current->parent;
        }
        
        return collect($ancestors);
    }

    /**
     * Get breadcrumb path
     */
    public function getBreadcrumb()
    {
        $breadcrumb = [$this];
        $current = $this;
        
        while ($current->parent) {
            $breadcrumb[] = $current->parent;
            $current = $current->parent;
        }
        
        return array_reverse($breadcrumb);
    }
}

