<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FoodItem extends Model
{
    protected $fillable = [
        'supplier_id',
        'food_category_id',
        'name',
        'description',
        'price',
        'unit',
        'stock',
        'min_stock',
        'image',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
        'min_stock' => 'integer',
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
}
