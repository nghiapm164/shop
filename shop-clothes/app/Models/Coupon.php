<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'value',
        'min_order_amount',
        'max_discount',
        'usage_limit',
        'used_count',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Check if coupon is valid for the given amount
     * Returns: ['valid' => bool, 'message' => string, 'discount' => decimal]
     */
    public function checkValid($orderAmount = 0)
    {
        // Check if coupon is active
        if (!$this->is_active) {
            return [
                'valid' => false,
                'message' => 'Mã giảm giá không hoạt động',
                'discount' => 0
            ];
        }

        // Check dates
        $today = Carbon::today();
        if ($today->lessThan($this->start_date) || $today->greaterThan($this->end_date)) {
            return [
                'valid' => false,
                'message' => 'Mã giảm giá đã hết hạn',
                'discount' => 0
            ];
        }

        // Check usage limit
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            return [
                'valid' => false,
                'message' => 'Mã giảm giá đã hết lượt sử dụng',
                'discount' => 0
            ];
        }

        // Check minimum order amount
        if ($orderAmount < $this->min_order_amount) {
            return [
                'valid' => false,
                'message' => "Đơn hàng tối thiểu {$this->min_order_amount}đ để sử dụng mã này",
                'discount' => 0
            ];
        }

        // Calculate discount
        $discount = 0;
        if ($this->type === 'percent') {
            $discount = ($orderAmount * $this->value) / 100;
        } else {
            $discount = $this->value;
        }

        // Check max discount
        if ($this->max_discount && $discount > $this->max_discount) {
            $discount = $this->max_discount;
        }

        return [
            'valid' => true,
            'message' => 'Mã giảm giá hợp lệ',
            'discount' => round($discount, 2)
        ];
    }

    /**
     * Increment usage count
     */
    public function incrementUsage()
    {
        $this->increment('used_count');
    }

    /**
     * Get discount type label
     */
    public function getTypeLabelAttribute()
    {
        return $this->type === 'percent' ? 'Phần trăm' : 'Cố định';
    }

    /**
     * Get discount display text
     */
    public function getDiscountTextAttribute()
    {
        if ($this->type === 'percent') {
            return "{$this->value}%";
        }
        return number_format($this->value, 0) . 'đ';
    }

    /**
     * Check if coupon is expired
     */
    public function getIsExpiredAttribute()
    {
        return Carbon::today()->greaterThan($this->end_date);
    }

    /**
     * Check if coupon has reached usage limit
     */
    public function getIsUsedUpAttribute()
    {
        return $this->usage_limit && $this->used_count >= $this->usage_limit;
    }

    /**
     * Scope for active coupons
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for valid coupons (active and not expired)
     */
    public function scopeValid($query)
    {
        $today = Carbon::today();
        return $query->where('is_active', true)
            ->where('start_date', '<=', $today)
            ->where('end_date', '>=', $today);
    }

    /**
     * Scope for percent coupons
     */
    public function scopePercent($query)
    {
        return $query->where('type', 'percent');
    }

    /**
     * Scope for fixed amount coupons
     */
    public function scopeFixed($query)
    {
        return $query->where('type', 'fixed');
    }
}

