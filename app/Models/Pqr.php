<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pqr extends Model
{
    use HasFactory, SoftDeletes;

    const TYPE_PETITION = 'petition';
    const TYPE_COMPLAINT = 'complaint';
    const TYPE_CLAIM = 'claim';
    const TYPE_SUGGESTION = 'suggestion';

    const STATUS_OPEN = 'open';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_RESOLVED = 'resolved';
    const STATUS_CLOSED = 'closed';
    const STATUS_ESCALATED = 'escalated';

    const PRIORITY_LOW = 'low';
    const PRIORITY_MEDIUM = 'medium';
    const PRIORITY_HIGH = 'high';
    const PRIORITY_URGENT = 'urgent';

    protected $fillable = [
        'ticket_number',
        'user_id',
        'order_id',
        'type',
        'status',
        'priority',
        'subject',
        'description',
        'category',
        'assigned_to',
        'resolved_at',
        'closed_at',
        'response',
        'resolution_notes',
        'customer_satisfaction',
        'satisfaction_feedback',
        'attachments',
        'tags',
    ];

    protected function casts(): array
    {
        return [
            'resolved_at' => 'datetime',
            'closed_at' => 'datetime',
            'customer_satisfaction' => 'integer',
            'attachments' => 'array',
            'tags' => 'array',
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

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function responses(): HasMany
    {
        return $this->hasMany(PqrResponse::class);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeOpen($query)
    {
        return $query->whereIn('status', [self::STATUS_OPEN, self::STATUS_IN_PROGRESS]);
    }

    public function scopeResolved($query)
    {
        return $query->where('status', self::STATUS_RESOLVED);
    }

    public function scopeHighPriority($query)
    {
        return $query->whereIn('priority', [self::PRIORITY_HIGH, self::PRIORITY_URGENT]);
    }

    public function isOpen(): bool
    {
        return in_array($this->status, [self::STATUS_OPEN, self::STATUS_IN_PROGRESS]);
    }

    public function isResolved(): bool
    {
        return $this->status === self::STATUS_RESOLVED;
    }

    public function isClosed(): bool
    {
        return $this->status === self::STATUS_CLOSED;
    }

    public function isHighPriority(): bool
    {
        return in_array($this->priority, [self::PRIORITY_HIGH, self::PRIORITY_URGENT]);
    }

    public function resolve(string $response, ?string $notes = null): void
    {
        $this->update([
            'status' => self::STATUS_RESOLVED,
            'response' => $response,
            'resolution_notes' => $notes,
            'resolved_at' => now(),
        ]);
    }

    public function close(?string $notes = null): void
    {
        $this->update([
            'status' => self::STATUS_CLOSED,
            'resolution_notes' => $notes,
            'closed_at' => now(),
        ]);
    }

    public function assignTo(int $userId): void
    {
        $this->update([
            'assigned_to' => $userId,
            'status' => self::STATUS_IN_PROGRESS,
        ]);
    }

    public function escalate(): void
    {
        $this->update([
            'status' => self::STATUS_ESCALATED,
            'priority' => self::PRIORITY_HIGH,
        ]);
    }

    public function getResponseTimeAttribute(): ?int
    {
        if (!$this->resolved_at) {
            return null;
        }

        return $this->created_at->diffInHours($this->resolved_at);
    }

    public function getFormattedTicketNumberAttribute(): string
    {
        return "PQR-{$this->ticket_number}";
    }

    public static function getTypes(): array
    {
        return [
            self::TYPE_PETITION => 'Petición',
            self::TYPE_COMPLAINT => 'Queja',
            self::TYPE_CLAIM => 'Reclamo',
            self::TYPE_SUGGESTION => 'Sugerencia',
        ];
    }

    public static function getStatuses(): array
    {
        return [
            self::STATUS_OPEN => 'Abierto',
            self::STATUS_IN_PROGRESS => 'En Progreso',
            self::STATUS_RESOLVED => 'Resuelto',
            self::STATUS_CLOSED => 'Cerrado',
            self::STATUS_ESCALATED => 'Escalado',
        ];
    }

    public static function getPriorities(): array
    {
        return [
            self::PRIORITY_LOW => 'Baja',
            self::PRIORITY_MEDIUM => 'Media',
            self::PRIORITY_HIGH => 'Alta',
            self::PRIORITY_URGENT => 'Urgente',
        ];
    }
} 