<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Delivery extends Model
{
    use HasFactory, SoftDeletes;

    const STATUS_PENDING = 'pending';
    const STATUS_ASSIGNED = 'assigned';
    const STATUS_PICKED_UP = 'picked_up';
    const STATUS_IN_TRANSIT = 'in_transit';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_FAILED = 'failed';
    const STATUS_CANCELLED = 'cancelled';

    const TYPE_STANDARD = 'standard';
    const TYPE_EXPRESS = 'express';
    const TYPE_SCHEDULED = 'scheduled';

    protected $fillable = [
        'delivery_number',
        'order_id',
        'delivery_person_id',
        'zone_id',
        'status',
        'delivery_type',
        'delivery_address',
        'delivery_city',
        'delivery_state',
        'delivery_postal_code',
        'delivery_latitude',
        'delivery_longitude',
        'delivery_instructions',
        'recipient_name',
        'recipient_phone',
        'recipient_email',
        'assigned_at',
        'picked_up_at',
        'in_transit_at',
        'delivered_at',
        'failed_at',
        'cancelled_at',
        'estimated_delivery_time',
        'scheduled_delivery_time',
        'delivery_fee',
        'tip_amount',
        'distance_km',
        'estimated_duration_minutes',
        'delivery_notes',
        'failure_reason',
        'cancellation_reason',
        'proof_of_delivery',
        'current_latitude',
        'current_longitude',
        'last_location_update',
        'route_data',
        'meta_data',
    ];

    protected function casts(): array
    {
        return [
            'delivery_latitude' => 'decimal:8',
            'delivery_longitude' => 'decimal:8',
            'current_latitude' => 'decimal:8',
            'current_longitude' => 'decimal:8',
            'assigned_at' => 'datetime',
            'picked_up_at' => 'datetime',
            'in_transit_at' => 'datetime',
            'delivered_at' => 'datetime',
            'failed_at' => 'datetime',
            'cancelled_at' => 'datetime',
            'estimated_delivery_time' => 'datetime',
            'scheduled_delivery_time' => 'datetime',
            'last_location_update' => 'datetime',
            'delivery_fee' => 'decimal:2',
            'tip_amount' => 'decimal:2',
            'distance_km' => 'decimal:2',
            'estimated_duration_minutes' => 'integer',
            'route_data' => 'array',
            'meta_data' => 'array',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function deliveryPerson(): BelongsTo
    {
        return $this->belongsTo(User::class, 'delivery_person_id');
    }

    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class);
    }

    public function trackingEvents(): HasMany
    {
        return $this->hasMany(DeliveryTrackingEvent::class);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeAssigned($query)
    {
        return $query->where('status', self::STATUS_ASSIGNED);
    }

    public function scopeInTransit($query)
    {
        return $query->where('status', self::STATUS_IN_TRANSIT);
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', self::STATUS_DELIVERED);
    }

    public function isAssigned(): bool
    {
        return $this->status === self::STATUS_ASSIGNED;
    }

    public function isInTransit(): bool
    {
        return $this->status === self::STATUS_IN_TRANSIT;
    }

    public function isDelivered(): bool
    {
        return $this->status === self::STATUS_DELIVERED;
    }

    public function isFailed(): bool
    {
        return $this->status === self::STATUS_FAILED;
    }

    public function updateStatus(string $status, ?string $notes = null, ?array $coordinates = null): void
    {
        $this->update(['status' => $status]);
        
        $this->trackingEvents()->create([
            'event_type' => 'status_change',
            'status' => $status,
            'description' => $notes ?? "Estado actualizado a {$status}",
            'latitude' => $coordinates['latitude'] ?? null,
            'longitude' => $coordinates['longitude'] ?? null,
            'event_time' => now(),
        ]);

        // Update timestamps based on status
        switch ($status) {
            case self::STATUS_ASSIGNED:
                $this->update(['assigned_at' => now()]);
                break;
            case self::STATUS_PICKED_UP:
                $this->update(['picked_up_at' => now()]);
                break;
            case self::STATUS_IN_TRANSIT:
                $this->update(['in_transit_at' => now()]);
                break;
            case self::STATUS_DELIVERED:
                $this->update(['delivered_at' => now()]);
                break;
            case self::STATUS_FAILED:
                $this->update(['failed_at' => now()]);
                break;
            case self::STATUS_CANCELLED:
                $this->update(['cancelled_at' => now()]);
                break;
        }
    }

    public function getFormattedDeliveryFeeAttribute(): string
    {
        return '$' . number_format($this->delivery_fee, 2);
    }

    public function getFormattedTipAmountAttribute(): string
    {
        return '$' . number_format($this->tip_amount, 2);
    }

    public function getEstimatedDurationAttribute(): ?int
    {
        return $this->estimated_duration_minutes;
    }

    public function getActualDurationAttribute(): ?int
    {
        if (!$this->picked_up_at || !$this->delivered_at) {
            return null;
        }

        return $this->picked_up_at->diffInMinutes($this->delivered_at);
    }

    public static function getStatuses(): array
    {
        return [
            self::STATUS_PENDING => 'Pendiente',
            self::STATUS_ASSIGNED => 'Asignado',
            self::STATUS_PICKED_UP => 'Recogido',
            self::STATUS_IN_TRANSIT => 'En Tránsito',
            self::STATUS_DELIVERED => 'Entregado',
            self::STATUS_FAILED => 'Fallido',
            self::STATUS_CANCELLED => 'Cancelado',
        ];
    }

    public static function getTypes(): array
    {
        return [
            self::TYPE_STANDARD => 'Estándar',
            self::TYPE_EXPRESS => 'Express',
            self::TYPE_SCHEDULED => 'Programado',
        ];
    }
} 