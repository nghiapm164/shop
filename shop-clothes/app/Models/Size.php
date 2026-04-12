<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Size extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
    ];

    public $timestamps = false;

    /**
     * Get all product variants with this size
     */
    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }
}

