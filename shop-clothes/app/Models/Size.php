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

    protected $appends = [
        'short_label',
    ];

    /**
     * Get all product variants with this size
     */
    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    /**
     * Short label used across UI (X, M, L, XL, 2XL, 3XL).
     */
    public function getShortLabelAttribute(): string
    {
        $code = strtoupper(trim((string) ($this->code ?? '')));

        return match ($code) {
            'XXL' => '2XL',
            'XXXL' => '3XL',
            default => ($code !== '' ? $code : $this->name),
        };
    }
}

