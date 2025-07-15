<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'user_id',
        'product_id',
        'product_name',
        'product_sku',
        'product_price',
        'quantity',
        'product_options',
        'total_price',
    ];

    protected function casts(): array
    {
        return [
            'product_price' => 'decimal:2',
            'total_price' => 'decimal:2',
            'quantity' => 'integer',
            'product_options' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Calculate total price when quantity or price changes
     */
    public function calculateTotal(): void
    {
        $this->total_price = $this->quantity * $this->product_price;
        $this->save();
    }

    /**
     * Get cart items for current session or user
     */
    public static function getCartItems(?string $sessionId = null, ?int $userId = null)
    {
        $query = static::with('product');

        if ($userId) {
            $query->where('user_id', $userId);
        } elseif ($sessionId) {
            $query->where('session_id', $sessionId);
        }

        return $query->get();
    }

    /**
     * Get cart total for current session or user
     */
    public static function getCartTotal(?string $sessionId = null, ?int $userId = null): float
    {
        $query = static::query();

        if ($userId) {
            $query->where('user_id', $userId);
        } elseif ($sessionId) {
            $query->where('session_id', $sessionId);
        }

        return $query->sum('total_price');
    }

    /**
     * Clear cart for current session or user
     */
    public static function clearCart(?string $sessionId = null, ?int $userId = null): void
    {
        $query = static::query();

        if ($userId) {
            $query->where('user_id', $userId);
        } elseif ($sessionId) {
            $query->where('session_id', $sessionId);
        }

        $query->delete();
    }
}
