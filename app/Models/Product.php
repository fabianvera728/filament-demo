<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'short_description',
        'sku',
        'barcode',
        'price',
        'cost_price',
        'compare_price',
        'stock_quantity',
        'min_stock_level',
        'track_inventory',
        'allow_backorder',
        'weight',
        'dimensions',
        'images',
        'featured_image',
        'category_id',
        'franchise_id',
        'is_active',
        'is_featured',
        'is_digital',
        'requires_shipping',
        'attributes',
        'variants',
        'tags',
        'seo_data',
        'sort_order',
        'rating_average',
        'rating_count',
        'view_count',
        'purchase_count',
        'available_from',
        'available_until',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'cost_price' => 'decimal:2',
            'compare_price' => 'decimal:2',
            'weight' => 'decimal:3',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'is_digital' => 'boolean',
            'requires_shipping' => 'boolean',
            'track_inventory' => 'boolean',
            'allow_backorder' => 'boolean',
            'stock_quantity' => 'integer',
            'min_stock_level' => 'integer',
            'sort_order' => 'integer',
            'rating_count' => 'integer',
            'view_count' => 'integer',
            'purchase_count' => 'integer',
            'rating_average' => 'decimal:2',
            'dimensions' => 'array',
            'images' => 'array',
            'attributes' => 'array',
            'variants' => 'array',
            'tags' => 'array',
            'seo_data' => 'array',
            'available_from' => 'datetime',
            'available_until' => 'datetime',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function franchise(): BelongsTo
    {
        return $this->belongsTo(Franchise::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function campaigns(): BelongsToMany
    {
        return $this->belongsToMany(Campaign::class, 'campaign_products')
            ->withPivot('discount_type', 'discount_value', 'is_active')
            ->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock_quantity', '>', 0);
    }

    public function scopeLowStock($query)
    {
        return $query->whereColumn('stock_quantity', '<=', 'min_stock_level');
    }

    public function isInStock(): bool
    {
        return $this->stock_quantity > 0;
    }

    public function isLowStock(): bool
    {
        return $this->stock_quantity <= $this->min_stock_level;
    }

    public function getDiscountPercentageAttribute(): float
    {
        if (!$this->compare_price || $this->compare_price <= $this->price) {
            return 0;
        }

        return round((($this->compare_price - $this->price) / $this->compare_price) * 100, 2);
    }

    public function getPrimaryImageAttribute(): ?string
    {
        if ($this->featured_image) {
            return $this->featured_image;
        }
        
        return $this->images[0] ?? null;
    }

    public function getPrimaryImageUrlAttribute(): ?string
    {
        $primaryImage = $this->primary_image;
        return $primaryImage ? asset('storage/' . $primaryImage) : null;
    }

    public function getAllImagesAttribute(): array
    {
        $allImages = [];
        
        if ($this->featured_image) {
            $allImages[] = $this->featured_image;
        }
        
        if ($this->images && is_array($this->images)) {
            foreach ($this->images as $image) {
                if ($image !== $this->featured_image) {
                    $allImages[] = $image;
                }
            }
        }
        
        return array_unique($allImages);
    }

    public function getAllImageUrlsAttribute(): array
    {
        return array_map(function($image) {
            return asset('storage/' . $image);
        }, $this->all_images);
    }

    public function getFormattedPriceAttribute(): string
    {
        return '$' . number_format($this->price, 2);
    }

    public function getFormattedComparePriceAttribute(): string
    {
        return $this->compare_price ? '$' . number_format($this->compare_price, 2) : '';
    }
} 