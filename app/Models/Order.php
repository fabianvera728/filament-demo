<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_IN_PROCESS = 'in_process';
    const STATUS_READY = 'ready';
    const STATUS_OUT_FOR_DELIVERY = 'out_for_delivery';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    const PAYMENT_STATUS_PENDING = 'pending';
    const PAYMENT_STATUS_PROCESSING = 'processing';
    const PAYMENT_STATUS_PAID = 'paid';
    const PAYMENT_STATUS_FAILED = 'failed';
    const PAYMENT_STATUS_REFUNDED = 'refunded';

    protected $fillable = [
        'order_number',
        'user_id',
        'franchise_id',
        'zone_id',
        'status',
        'payment_status',
        'payment_method',
        'subtotal',
        'tax_amount',
        'shipping_amount',
        'discount_amount',
        'total_amount',
        'currency',
        'notes',
        'special_instructions',
        'customer_name',
        'customer_phone',
        'customer_email',
        'customer_notes',
        'delivery_address',
        'delivery_city',
        'delivery_state',
        'delivery_postal_code',
        'delivery_country',
        'delivery_latitude',
        'delivery_longitude',
        'delivery_instructions',
        'delivery_method',
        'delivery_coordinates',
        'billing_address',
        'billing_city',
        'billing_state',
        'billing_postal_code',
        'billing_country',
        'scheduled_delivery_date',
        'estimated_delivery_time',
        'actual_delivery_time',
        'confirmed_at',
        'prepared_at',
        'dispatched_at',
        'delivered_at',
        'cancelled_at',
        'tracking_number',
        'payment_reference',
        'cancellation_reason',
        'completed_at',
        'applied_coupons',
        'meta_data',
        'tip_amount',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'shipping_amount' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'tip_amount' => 'decimal:2',
            'delivery_latitude' => 'decimal:8',
            'delivery_longitude' => 'decimal:8',
            'delivery_address' => 'array',
            'delivery_coordinates' => 'array',
            'billing_address' => 'array',
            'applied_coupons' => 'array',
            'meta_data' => 'array',
            'scheduled_delivery_date' => 'datetime',
            'estimated_delivery_time' => 'datetime',
            'actual_delivery_time' => 'datetime',
            'confirmed_at' => 'datetime',
            'prepared_at' => 'datetime',
            'dispatched_at' => 'datetime',
            'delivered_at' => 'datetime',
            'cancelled_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function franchise(): BelongsTo
    {
        return $this->belongsTo(Franchise::class);
    }

    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function delivery(): HasOne
    {
        return $this->hasOne(Delivery::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function statusHistory(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByPaymentStatus($query, $paymentStatus)
    {
        return $query->where('payment_status', $paymentStatus);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', self::STATUS_CANCELLED);
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, [
            self::STATUS_PENDING,
            self::STATUS_CONFIRMED,
            self::STATUS_IN_PROCESS
        ]);
    }

    public function updateStatus(string $status, ?string $notes = null): void
    {
        // Validar transición de estado
        if (!$this->canTransitionTo($status)) {
            throw new \InvalidArgumentException("No se puede cambiar de '{$this->status}' a '{$status}'");
        }

        $oldStatus = $this->status;
        
        // Actualizar el estado
        $this->update(['status' => $status]);
        
        // Actualizar timestamps específicos según el estado
        $this->updateTimestampForStatus($status);
        
        // Registrar en el historial
        $this->statusHistory()->create([
            'status' => $status,
            'notes' => $notes,
            'changed_by' => auth()->id(),
            'changed_at' => now(),
        ]);

        // Ejecutar acciones específicas del estado
        $this->executeStatusActions($status, $oldStatus);
    }

    protected function canTransitionTo(string $newStatus): bool
    {
        $validTransitions = [
            self::STATUS_PENDING => [
                self::STATUS_CONFIRMED,
                self::STATUS_CANCELLED,
            ],
            self::STATUS_CONFIRMED => [
                self::STATUS_IN_PROCESS,
                self::STATUS_CANCELLED,
            ],
            self::STATUS_IN_PROCESS => [
                self::STATUS_READY,
                self::STATUS_CANCELLED,
            ],
            self::STATUS_READY => [
                self::STATUS_OUT_FOR_DELIVERY,
                self::STATUS_CANCELLED,
            ],
            self::STATUS_OUT_FOR_DELIVERY => [
                self::STATUS_DELIVERED,
                self::STATUS_CANCELLED,
            ],
            self::STATUS_DELIVERED => [
                self::STATUS_COMPLETED,
            ],
            self::STATUS_COMPLETED => [],
            self::STATUS_CANCELLED => [],
        ];

        return in_array($newStatus, $validTransitions[$this->status] ?? []);
    }

    protected function updateTimestampForStatus(string $status): void
    {
        $timestampField = match ($status) {
            self::STATUS_CONFIRMED => 'confirmed_at',
            self::STATUS_IN_PROCESS => 'prepared_at',
            self::STATUS_OUT_FOR_DELIVERY => 'dispatched_at',
            self::STATUS_DELIVERED => 'delivered_at',
            self::STATUS_CANCELLED => 'cancelled_at',
            self::STATUS_COMPLETED => 'completed_at',
            default => null,
        };

        if ($timestampField) {
            $this->update([$timestampField => now()]);
        }
    }

    protected function executeStatusActions(string $newStatus, string $oldStatus): void
    {
        // Aquí se pueden agregar acciones específicas para cada cambio de estado
        // Por ejemplo: enviar notificaciones, actualizar inventario, etc.
        
        match ($newStatus) {
            self::STATUS_CONFIRMED => $this->onConfirmed(),
            self::STATUS_IN_PROCESS => $this->onStartedPreparing(),
            self::STATUS_READY => $this->onReady(),
            self::STATUS_OUT_FOR_DELIVERY => $this->onDispatched(),
            self::STATUS_DELIVERED => $this->onDelivered(),
            self::STATUS_COMPLETED => $this->onCompleted(),
            self::STATUS_CANCELLED => $this->onCancelled(),
            default => null,
        };
    }

    protected function onConfirmed(): void
    {
        // Lógica cuando se confirma el pedido
        // Ejemplo: reservar inventario, enviar notificación al cliente
    }

    protected function onStartedPreparing(): void
    {
        // Lógica cuando se inicia la preparación
        // Ejemplo: notificar a la cocina
    }

    protected function onReady(): void
    {
        // Lógica cuando está listo
        // Ejemplo: notificar al repartidor o cliente
    }

    protected function onDispatched(): void
    {
        // Lógica cuando se despacha
        // Ejemplo: crear registro de entrega, enviar tracking
    }

    protected function onDelivered(): void
    {
        // Lógica cuando se entrega
        // Ejemplo: liberar inventario reservado
    }

    protected function onCompleted(): void
    {
        // Lógica cuando se completa
        // Ejemplo: procesar puntos de fidelidad
    }

    protected function onCancelled(): void
    {
        // Lógica cuando se cancela
        // Ejemplo: liberar inventario, procesar reembolso
    }

    public function getAvailableTransitions(): array
    {
        $validTransitions = [
            self::STATUS_PENDING => [
                self::STATUS_CONFIRMED,
                self::STATUS_CANCELLED,
            ],
            self::STATUS_CONFIRMED => [
                self::STATUS_IN_PROCESS,
                self::STATUS_CANCELLED,
            ],
            self::STATUS_IN_PROCESS => [
                self::STATUS_READY,
                self::STATUS_CANCELLED,
            ],
            self::STATUS_READY => [
                self::STATUS_OUT_FOR_DELIVERY,
                self::STATUS_CANCELLED,
            ],
            self::STATUS_OUT_FOR_DELIVERY => [
                self::STATUS_DELIVERED,
                self::STATUS_CANCELLED,
            ],
            self::STATUS_DELIVERED => [
                self::STATUS_COMPLETED,
            ],
            self::STATUS_COMPLETED => [],
            self::STATUS_CANCELLED => [],
        ];

        $availableStatuses = $validTransitions[$this->status] ?? [];
        $allStatuses = self::getStatuses();
        
        return array_intersect_key($allStatuses, array_flip($availableStatuses));
    }

    public function getTotalItemsAttribute(): int
    {
        return $this->items()->sum('quantity');
    }

    public function getFormattedTotalAttribute(): string
    {
        return '$' . number_format($this->total_amount, 2);
    }

    public static function getStatuses(): array
    {
        return [
            self::STATUS_PENDING => 'Pendiente',
            self::STATUS_CONFIRMED => 'Confirmado',
            self::STATUS_IN_PROCESS => 'En Proceso',
            self::STATUS_READY => 'Listo',
            self::STATUS_OUT_FOR_DELIVERY => 'En Camino',
            self::STATUS_DELIVERED => 'Entregado',
            self::STATUS_COMPLETED => 'Completado',
            self::STATUS_CANCELLED => 'Cancelado',
        ];
    }

    public static function getPaymentStatuses(): array
    {
        return [
            self::PAYMENT_STATUS_PENDING => 'Pendiente',
            self::PAYMENT_STATUS_PROCESSING => 'Procesando',
            self::PAYMENT_STATUS_PAID => 'Pagado',
            self::PAYMENT_STATUS_FAILED => 'Fallido',
            self::PAYMENT_STATUS_REFUNDED => 'Reembolsado',
        ];
    }
} 