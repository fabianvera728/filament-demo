<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PqrResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'pqr_id',
        'user_id',
        'message',
        'is_internal',
        'attachments',
    ];

    protected function casts(): array
    {
        return [
            'is_internal' => 'boolean',
            'attachments' => 'array',
        ];
    }

    public function pqr(): BelongsTo
    {
        return $this->belongsTo(Pqr::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopePublic($query)
    {
        return $query->where('is_internal', false);
    }

    public function scopeInternal($query)
    {
        return $query->where('is_internal', true);
    }

    public function isFromCustomer(): bool
    {
        return $this->user_id === $this->pqr->user_id;
    }

    public function isFromSupport(): bool
    {
        return !$this->isFromCustomer();
    }
} 