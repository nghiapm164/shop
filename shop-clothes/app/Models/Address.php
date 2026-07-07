<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'recipient_name',
        'phone',
        'address_detail',
        'ward',
        'district',
        'province',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    /**
     * Get the user that owns the address
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the full address string
     */
    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([
            $this->address_detail,
            $this->ward,
            $this->district,
            $this->province,
        ]);
        return implode(', ', $parts);
    }

    /**
     * Set this address as the default for the user.
     */
    public function makeDefault(): void
    {
        static::where('user_id', $this->user_id)->update(['is_default' => false]);
        $this->update(['is_default' => true]);
    }
}
