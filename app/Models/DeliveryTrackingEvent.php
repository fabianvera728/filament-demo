<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliveryTrackingEvent extends Model
{
    use HasFactory;

    const EVENT_TYPE_STATUS_CHANGE = 'status_change';
    const EVENT_TYPE_LOCATION_UPDATE = 'location_update';
    const EVENT_TYPE_NOTE_ADDED = 'note_added';
    const EVENT_TYPE_PHOTO_TAKEN = 'photo_taken';

    protected $fillable = [
        'delivery_id',
        'event_type',
        'status',
        'latitude',
        'longitude',
        'description',
        'photo_url',
        'additional_data',
        'event_time',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
            'additional_data' => 'array',
            'event_time' => 'datetime',
        ];
    }

    public function delivery(): BelongsTo
    {
        return $this->belongsTo(Delivery::class);
    }

    public function getFormattedStatusAttribute(): string
    {
        return Delivery::getStatuses()[$this->status] ?? $this->status;
    }

    public static function getEventTypes(): array
    {
        return [
            self::EVENT_TYPE_STATUS_CHANGE => 'Cambio de Estado',
            self::EVENT_TYPE_LOCATION_UPDATE => 'Actualización de Ubicación',
            self::EVENT_TYPE_NOTE_ADDED => 'Nota Agregada',
            self::EVENT_TYPE_PHOTO_TAKEN => 'Foto Tomada',
        ];
    }
} 