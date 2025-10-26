<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaxPriceSetting extends Model
{
    protected $fillable = [
        'food_category_id',
        'max_price',
        'unit',
        'notes',
        'updated_by',
    ];

    protected $casts = [
        'max_price' => 'decimal:2',
    ];

    /**
     * Get the food category for this setting.
     */
    public function foodCategory(): BelongsTo
    {
        return $this->belongsTo(FoodCategory::class);
    }

    /**
     * Get the user who last updated this setting.
     */
    public function updatedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}

