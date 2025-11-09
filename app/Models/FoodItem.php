<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class FoodItem extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'supplier_id',
        'food_category_id',
        'name',
        'description',
        'price',
        'max_price',
        'default_price_increment',
        'price_increment_type',
        'unit',
        'stock',
        'min_purchase',
        'max_purchase',
        'image',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'max_price' => 'decimal:2',
        'default_price_increment' => 'decimal:2',
        'price_increment_type' => 'string',
        'stock' => 'integer',
        'min_purchase' => 'integer',
        'max_purchase' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Get the supplier that owns the food item.
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'supplier_id');
    }

    /**
     * Get the food category for this item.
     */
    public function foodCategory(): BelongsTo
    {
        return $this->belongsTo(FoodCategory::class);
    }

    /**
     * Get all food requests for this item.
     */
    public function foodRequests(): HasMany
    {
        return $this->hasMany(FoodRequest::class);
    }

    /**
     * Calculate the final price with increment applied.
     *
     * @return float
     */
    public function getFinalPrice(): float
    {
        $increment = $this->default_price_increment ?? 500;
        $type = $this->price_increment_type ?? 'fixed';

        if ($type === 'percentage') {
            // Percentage increment: price * (1 + increment/100)
            return $this->price * (1 + ($increment / 100));
        } else {
            // Fixed increment: price + increment
            return $this->price + $increment;
        }
    }

    /**
     * Get the increment amount as a formatted string for display.
     *
     * @return string
     */
    public function getIncrementDisplay(): string
    {
        $increment = $this->default_price_increment ?? 500;
        $type = $this->price_increment_type ?? 'fixed';

        if ($type === 'percentage') {
            return number_format($increment, 2, ',', '.') . '%';
        } else {
            return 'Rp ' . number_format($increment, 0, ',', '.');
        }
    }

    /**
     * Validate purchase quantity against min and max limits.
     *
     * @param float $quantity
     * @return array ['valid' => bool, 'message' => string]
     */
    public function validatePurchaseQuantity(float $quantity): array
    {
        // Check minimum purchase
        if ($quantity < $this->min_purchase) {
            return [
                'valid' => false,
                'message' => "Minimum purchase is {$this->min_purchase} {$this->unit}. Please add at least the minimum quantity."
            ];
        }

        // Check maximum purchase if set
        if ($this->max_purchase !== null && $quantity > $this->max_purchase) {
            return [
                'valid' => false,
                'message' => "Maximum purchase is {$this->max_purchase} {$this->unit}. Please reduce the quantity."
            ];
        }

        // Check stock availability
        if ($quantity > $this->stock) {
            return [
                'valid' => false,
                'message' => "Insufficient stock. Available stock is {$this->stock} {$this->unit}."
            ];
        }

        return [
            'valid' => true,
            'message' => 'Valid quantity'
        ];
    }
}
