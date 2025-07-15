<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Holiday extends Model
{
    use HasFactory, SoftDeletes;

    const TYPE_FIXED = 'fixed';
    const TYPE_MOVABLE = 'movable';
    const TYPE_RECURRING = 'recurring';

    protected $fillable = [
        'name',
        'description',
        'date',
        'type',
        'is_active',
        'affects_delivery',
        'alternative_delivery_fee',
        'notes',
        'color',
        'recurring_pattern',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'is_active' => 'boolean',
            'affects_delivery' => 'boolean',
            'alternative_delivery_fee' => 'decimal:2',
            'recurring_pattern' => 'array',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByDate($query, $date)
    {
        return $query->whereDate('date', $date);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('date', '>=', now()->toDateString());
    }

    public function scopeThisYear($query)
    {
        return $query->whereYear('date', now()->year);
    }

    public function isToday(): bool
    {
        return $this->date->isToday();
    }

    public function isUpcoming(): bool
    {
        return $this->date->isFuture();
    }

    public function isPast(): bool
    {
        return $this->date->isPast();
    }

    public function getDaysUntilAttribute(): int
    {
        return now()->diffInDays($this->date, false);
    }

    public function getFormattedDateAttribute(): string
    {
        return $this->date->format('d/m/Y');
    }

    public static function getTypes(): array
    {
        return [
            self::TYPE_FIXED => 'Fecha Fija',
            self::TYPE_MOVABLE => 'Fecha Móvil',
            self::TYPE_RECURRING => 'Recurrente',
        ];
    }

    public static function isHoliday($date): bool
    {
        return self::active()
            ->whereDate('date', $date)
            ->exists();
    }

    public static function getHolidayByDate($date): ?self
    {
        return self::active()
            ->whereDate('date', $date)
            ->first();
    }
} 