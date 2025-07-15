<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campaign extends Model
{
    use HasFactory, SoftDeletes;

    const TYPE_DISCOUNT = 'discount';
    const TYPE_PROMOTION = 'promotion';
    const TYPE_COUPON = 'coupon';
    const TYPE_LOYALTY = 'loyalty';

    const DISCOUNT_TYPE_PERCENTAGE = 'percentage';
    const DISCOUNT_TYPE_FIXED_AMOUNT = 'fixed_amount';
    const DISCOUNT_TYPE_FREE_SHIPPING = 'free_shipping';

    const PRIORITY_LOW = 'low';
    const PRIORITY_NORMAL = 'normal';
    const PRIORITY_HIGH = 'high';

    protected $fillable = [
        'name',
        'code',
        'description',
        'type',
        'discount_type',
        'discount_value',
        'minimum_order_amount',
        'maximum_discount_amount',
        'usage_limit',
        'usage_limit_per_customer',
        'used_count',
        'starts_at',
        'ends_at',
        'applicable_products',
        'applicable_categories',
        'applicable_zones',
        'applicable_franchises',
        'applicable_user_roles',
        'excluded_products',
        'excluded_categories',
        'is_active',
        'is_public',
        'requires_code',
        'stackable',
        'priority',
        'conditions',
        'meta_data',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_public' => 'boolean',
            'requires_code' => 'boolean',
            'stackable' => 'boolean',
            'discount_value' => 'decimal:2',
            'minimum_order_amount' => 'decimal:2',
            'maximum_discount_amount' => 'decimal:2',
            'usage_limit' => 'integer',
            'usage_limit_per_customer' => 'integer',
            'used_count' => 'integer',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'applicable_products' => 'array',
            'applicable_categories' => 'array',
            'applicable_zones' => 'array',
            'applicable_franchises' => 'array',
            'applicable_user_roles' => 'array',
            'excluded_products' => 'array',
            'excluded_categories' => 'array',
            'conditions' => 'array',
            'meta_data' => 'array',
        ];
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'campaign_products')
            ->withPivot('discount_type', 'discount_value', 'is_active')
            ->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where('starts_at', '<=', now())
            ->where(function ($q) {
                $q->whereNull('ends_at')
                  ->orWhere('ends_at', '>', now());
            });
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByDiscountType($query, $discountType)
    {
        return $query->where('discount_type', $discountType);
    }

    public function isActive(): bool
    {
        return $this->is_active 
            && $this->starts_at <= now()
            && (is_null($this->ends_at) || $this->ends_at > now());
    }

    public function isExpired(): bool
    {
        return !is_null($this->ends_at) && $this->ends_at < now();
    }

    public function hasReachedUsageLimit(): bool
    {
        return !is_null($this->usage_limit) && $this->used_count >= $this->usage_limit;
    }

    public function canBeUsed(): bool
    {
        return $this->isActive() && !$this->hasReachedUsageLimit();
    }

    public function calculateDiscount(float $amount): float
    {
        if (!$this->canBeUsed() || $amount < $this->minimum_order_amount) {
            return 0;
        }

        $discount = 0;

        if ($this->discount_type === self::DISCOUNT_TYPE_PERCENTAGE) {
            $discount = $amount * ($this->discount_value / 100);
        } elseif ($this->discount_type === self::DISCOUNT_TYPE_FIXED_AMOUNT) {
            $discount = $this->discount_value;
        }

        if ($this->maximum_discount_amount && $discount > $this->maximum_discount_amount) {
            $discount = $this->maximum_discount_amount;
        }

        return round($discount, 2);
    }

    public function incrementUsage(): void
    {
        $this->increment('used_count');
    }

    public function getUsagePercentageAttribute(): float
    {
        if (!$this->usage_limit) {
            return 0;
        }

        return round(($this->used_count / $this->usage_limit) * 100, 2);
    }

    public static function getTypes(): array
    {
        return [
            self::TYPE_DISCOUNT => 'Descuento',
            self::TYPE_PROMOTION => 'Promoción',
            self::TYPE_COUPON => 'Cupón',
            self::TYPE_LOYALTY => 'Lealtad',
        ];
    }

    public static function getDiscountTypes(): array
    {
        return [
            self::DISCOUNT_TYPE_PERCENTAGE => 'Porcentaje',
            self::DISCOUNT_TYPE_FIXED_AMOUNT => 'Monto Fijo',
            self::DISCOUNT_TYPE_FREE_SHIPPING => 'Envío Gratis',
        ];
    }

    public static function getPriorities(): array
    {
        return [
            self::PRIORITY_LOW => 'Baja',
            self::PRIORITY_NORMAL => 'Normal',
            self::PRIORITY_HIGH => 'Alta',
        ];
    }
} 