<?php

namespace App\Http\Requests;

use App\Models\FoodItem;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdateIngredientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $foodItem = $this->route('foodItem');
        
        // Check if user owns this ingredient
        if ($foodItem instanceof \App\Models\FoodItem) {
            return $foodItem->supplier_id === auth()->id();
        }
        
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $foodItem = $this->route('foodItem');
        
        // Get max_price from the selected category (sub-category), not from ingredient
        $maxPrice = null;
        $foodCategoryId = $this->input('food_category_id');
        
        if ($foodCategoryId) {
            $category = \App\Models\FoodCategory::find($foodCategoryId);
            if ($category) {
                $maxPrice = $category->max_price;
            }
        }

        return [
            'parent_category_id' => ['required', 'exists:food_categories,id'],
            'food_category_id' => ['required', 'exists:food_categories,id'],
            'sub_name' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'price' => [
                'required',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) use ($maxPrice, $foodCategoryId) {
                    // CRITICAL: Get max_price from category (sub-category), not from ingredient
                    // This ensures all ingredients in the same category have the same max_price limit
                    if ($foodCategoryId) {
                        $category = \App\Models\FoodCategory::find($foodCategoryId);
                        if ($category && $category->max_price !== null && $category->max_price !== '' && is_numeric($category->max_price)) {
                            $inputPrice = (float) $value;
                            $maxPriceFloat = (float) $category->max_price;

                            // Only validate if max_price is greater than 0
                            if ($maxPriceFloat > 0 && $inputPrice > $maxPriceFloat) {
                                $fail('Price cannot exceed the maximum price limit of Rp ' . number_format($maxPriceFloat, 0, ',', '.') . ' set by administrator for category "' . $category->name . '". Your input: Rp ' . number_format($inputPrice, 0, ',', '.'));
                            }
                        }
                    }
                },
            ],
            'unit' => ['required', 'string', 'max:20'],
            'stock' => ['required', 'integer', 'min:0'],
            'min_purchase' => ['required', 'integer', 'min:0'],
            'max_purchase' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
            'image' => ['nullable', 'image', 'max:2048'],
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            // Get max_price from the selected category (sub-category), not from ingredient
            $foodCategoryId = $this->input('food_category_id');
            
            if ($foodCategoryId) {
                $category = \App\Models\FoodCategory::find($foodCategoryId);
                if ($category && $category->max_price !== null && $category->max_price !== '' && is_numeric($category->max_price)) {
                    $maxPriceFloat = (float) $category->max_price;
                    
                    if ($maxPriceFloat > 0) {
                        $inputPrice = (float) $this->input('price');
                        
                        if ($inputPrice > $maxPriceFloat) {
                            $validator->errors()->add('price', 'Price cannot exceed the maximum price limit of Rp ' . number_format($maxPriceFloat, 0, ',', '.') . ' set by administrator for category "' . $category->name . '". Your input: Rp ' . number_format($inputPrice, 0, ',', '.'));
                        }
                    }
                }
                
                // Validate that the selected food_category_id is a child of parent_category_id
                $parentCategoryId = $this->input('parent_category_id');
                $foodCategoryId = $this->input('food_category_id');
                
                if ($parentCategoryId && $foodCategoryId) {
                    $foodCategory = \App\Models\FoodCategory::find($foodCategoryId);
                    if ($foodCategory && $foodCategory->parent_id != $parentCategoryId) {
                        $validator->errors()->add('food_category_id', 'The selected item name must belong to the selected category.');
                    }
                }
                
                // Validate max_purchase >= min_purchase
                $maxPurchase = $this->input('max_purchase');
                $minPurchase = $this->input('min_purchase');
                
                if ($maxPurchase !== null && $maxPurchase !== '' && $minPurchase !== null && $minPurchase !== '') {
                    if ((int)$maxPurchase > 0 && (int)$minPurchase > 0 && (int)$maxPurchase < (int)$minPurchase) {
                        $validator->errors()->add('max_purchase', 'Maximum purchase must be greater than or equal to minimum purchase.');
                    }
                }
            }
        });
    }
}
