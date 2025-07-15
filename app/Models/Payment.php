<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_REFUNDED = 'refunded';

    const METHOD_CASH = 'cash';
    const METHOD_CARD = 'card';
    const METHOD_TRANSFER = 'transfer';
    const METHOD_WOMPI = 'wompi';
    const METHOD_OPENPAY = 'openpay';
    const METHOD_GLOBALPAY = 'globalpay';
    const METHOD_WENJOY = 'wenjoy';

    protected $fillable = [
        'order_id',
        'user_id',
        'payment_method',
        'gateway',
        'status',
        'amount',
        'currency',
        'gateway_transaction_id',
        'gateway_reference',
        'gateway_response',
        'processed_at',
        'failed_at',
        'failure_reason',
        'refunded_at',
        'refund_amount',
        'refund_reason',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'refund_amount' => 'decimal:2',
            'gateway_response' => 'array',
            'processed_at' => 'datetime',
            'failed_at' => 'datetime',
            'refunded_at' => 'datetime',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function scopeFailed($query)
    {
        return $query->where('status', self::STATUS_FAILED);
    }

    public function scopeByMethod($query, $method)
    {
        return $query->where('payment_method', $method);
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isFailed(): bool
    {
        return $this->status === self::STATUS_FAILED;
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isRefunded(): bool
    {
        return $this->status === self::STATUS_REFUNDED;
    }

    public function markAsCompleted(array $gatewayData = []): void
    {
        $this->update([
            'status' => self::STATUS_COMPLETED,
            'processed_at' => now(),
            'gateway_response' => $gatewayData,
        ]);
    }

    public function markAsFailed(string $reason, array $gatewayData = []): void
    {
        $this->update([
            'status' => self::STATUS_FAILED,
            'failed_at' => now(),
            'failure_reason' => $reason,
            'gateway_response' => $gatewayData,
        ]);
    }

    public function markAsRefunded(float $amount, string $reason): void
    {
        $this->update([
            'status' => self::STATUS_REFUNDED,
            'refunded_at' => now(),
            'refund_amount' => $amount,
            'refund_reason' => $reason,
        ]);
    }

    public function getFormattedAmountAttribute(): string
    {
        return '$' . number_format($this->amount, 2);
    }

    public function getFormattedRefundAmountAttribute(): string
    {
        return $this->refund_amount ? '$' . number_format($this->refund_amount, 2) : '';
    }

    public static function getStatuses(): array
    {
        return [
            self::STATUS_PENDING => 'Pendiente',
            self::STATUS_PROCESSING => 'Procesando',
            self::STATUS_COMPLETED => 'Completado',
            self::STATUS_FAILED => 'Fallido',
            self::STATUS_CANCELLED => 'Cancelado',
            self::STATUS_REFUNDED => 'Reembolsado',
        ];
    }

    public static function getMethods(): array
    {
        return [
            self::METHOD_CASH => 'Efectivo',
            self::METHOD_CARD => 'Tarjeta',
            self::METHOD_TRANSFER => 'Transferencia',
            self::METHOD_WOMPI => 'Wompi',
            self::METHOD_OPENPAY => 'OpenPay',
            self::METHOD_GLOBALPAY => 'GlobalPay',
            self::METHOD_WENJOY => 'Wenjoy',
        ];
    }
} 