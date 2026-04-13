<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'label',
        'group',
        'type',
        'value',
        'sort_order',
        'is_public',
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    public function scopeInGroup($query, string $group)
    {
        return $query->where('group', $group);
    }
}
