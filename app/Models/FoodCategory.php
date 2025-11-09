<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FoodCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'color',
        'is_active',
        'sort_order',
        'parent_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get the food requests for this category.
     */
    public function foodRequests(): HasMany
    {
        return $this->hasMany(FoodRequest::class);
    }

    /**
     * Get the parent category.
     */
    public function parent()
    {
        return $this->belongsTo(FoodCategory::class, 'parent_id');
    }

    /**
     * Get the child categories.
     */
    public function children()
    {
        return $this->hasMany(FoodCategory::class, 'parent_id')->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Get all food items in this category and its sub-categories.
     */
    public function allFoodItems()
    {
        $categoryIds = $this->children()->pluck('id')->prepend($this->id);
        return \App\Models\FoodItem::whereIn('food_category_id', $categoryIds);
    }

    /**
     * Check if this category is a parent category.
     */
    public function isParent(): bool
    {
        return $this->parent_id === null;
    }

    /**
     * Check if this category is a sub-category.
     */
    public function isSubCategory(): bool
    {
        return $this->parent_id !== null;
    }

    /**
     * Scope a query to only include active categories.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to order by sort order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }
}
