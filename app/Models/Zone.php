<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Zone extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'code',
        'coordinates',
        'is_active',
        'delivery_fee',
        'min_order_amount',
        'estimated_delivery_time',
        'coverage_area',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'delivery_fee' => 'decimal:2',
            'min_order_amount' => 'decimal:2',
            'estimated_delivery_time' => 'integer',
            'coordinates' => 'array',
            'coverage_area' => 'array',
        ];
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function deliveries(): HasMany
    {
        return $this->hasMany(Delivery::class);
    }

    public function franchises(): HasMany
    {
        return $this->hasMany(Franchise::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getFullNameAttribute(): string
    {
        return $this->code ? "{$this->code} - {$this->name}" : $this->name;
    }
} 