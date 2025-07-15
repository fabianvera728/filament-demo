<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Franchise extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'description',
        'address',
        'phone',
        'email',
        'latitude',
        'longitude',
        'commission_percentage',
        'opening_time',
        'closing_time',
        'operating_days',
        'delivery_radius_km',
        'minimum_order_amount',
        'is_active',
        'manager_name',
        'manager_phone',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'operating_days' => 'array',
            'commission_percentage' => 'decimal:2',
            'minimum_order_amount' => 'decimal:2',
            'delivery_radius_km' => 'decimal:2',
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
            'opening_time' => 'datetime:H:i:s',
            'closing_time' => 'datetime:H:i:s',
        ];
    }

    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function campaigns(): HasMany
    {
        return $this->hasMany(Campaign::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getFullNameAttribute(): string
    {
        return $this->code ? "{$this->code} - {$this->name}" : $this->name;
    }

    public function getTotalOrdersAttribute(): int
    {
        return $this->orders()->count();
    }

    public function getTotalRevenueAttribute(): float
    {
        return $this->orders()
            ->where('status', 'completed')
            ->sum('total_amount');
    }
} 