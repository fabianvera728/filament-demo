<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bonus extends Model
{
    use HasFactory, SoftDeletes;

    const TYPE_PERCENTAGE = 'percentage';
    const TYPE_FIXED = 'fixed';
    const TYPE_POINTS = 'points';

    const STATUS_PENDING = 'pending';
    const STATUS_ACTIVE = 'active';
    const STATUS_USED = 'used';
    const STATUS_EXPIRED = 'expired';
    const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'name',
        'description',
        'code',
        'user_id',
        'order_id',
        'type',
        'status',
        'value',
        'points',
        'min_order_amount',
        'max_discount_amount',
        'issued_date',
        'expiry_date',
        'used_date',
        'used_order_id',
        'is_transferable',
        'notes',
        'terms_conditions',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'decimal:2',
            'points' => 'integer',
            'min_order_amount' => 'decimal:2',
            'max_discount_amount' => 'decimal:2',
            'is_transferable' => 'boolean',
            'issued_date' => 'datetime',
            'expiry_date' => 'datetime',
            'used_date' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function usedOrder(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'used_order_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE)
            ->where(function ($q) {
                $q->whereNull('expiry_date')
                  ->orWhere('expiry_date', '>', now());
            });
    }

    public function scopeExpired($query)
    {
        return $query->where('expiry_date', '<', now())
            ->whereIn('status', [self::STATUS_ACTIVE, self::STATUS_PENDING]);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE
            && (is_null($this->expiry_date) || $this->expiry_date > now());
    }

    public function isExpired(): bool
    {
        return !is_null($this->expiry_date) && $this->expiry_date < now();
    }

    public function isUsed(): bool
    {
        return $this->status === self::STATUS_USED;
    }

    public function canBeUsed(): bool
    {
        return $this->isActive() && !$this->isUsed() && !$this->isExpired();
    }

    public function calculateDiscount(float $orderAmount): float
    {
        if (!$this->canBeUsed() || $orderAmount < $this->min_order_amount) {
            return 0;
        }

        $discount = 0;

        switch ($this->type) {
            case self::TYPE_PERCENTAGE:
                $discount = $orderAmount * ($this->value / 100);
                break;
            case self::TYPE_FIXED:
                $discount = $this->value;
                break;
            case self::TYPE_POINTS:
                // Assuming 1 point = $0.01
                $discount = $this->points * 0.01;
                break;
        }

        if ($this->max_discount_amount && $discount > $this->max_discount_amount) {
            $discount = $this->max_discount_amount;
        }

        return round($discount, 2);
    }

    public function use(int $orderId): void
    {
        $this->update([
            'status' => self::STATUS_USED,
            'used_date' => now(),
            'used_order_id' => $orderId,
        ]);
    }

    public function cancel(?string $reason = null): void
    {
        $this->update([
            'status' => self::STATUS_CANCELLED,
            'notes' => $reason,
        ]);
    }

    public function getDaysUntilExpiryAttribute(): ?int
    {
        if (!$this->expiry_date) {
            return null;
        }

        return now()->diffInDays($this->expiry_date, false);
    }

    public function getFormattedValueAttribute(): string
    {
        switch ($this->type) {
            case self::TYPE_PERCENTAGE:
                return $this->value . '%';
            case self::TYPE_FIXED:
                return '$' . number_format($this->value, 2);
            case self::TYPE_POINTS:
                return $this->points . ' puntos';
            default:
                return '';
        }
    }

    public static function getTypes(): array
    {
        return [
            self::TYPE_PERCENTAGE => 'Porcentaje',
            self::TYPE_FIXED => 'Monto Fijo',
            self::TYPE_POINTS => 'Puntos',
        ];
    }

    public static function getStatuses(): array
    {
        return [
            self::STATUS_PENDING => 'Pendiente',
            self::STATUS_ACTIVE => 'Activo',
            self::STATUS_USED => 'Usado',
            self::STATUS_EXPIRED => 'Expirado',
            self::STATUS_CANCELLED => 'Cancelado',
        ];
    }
} 