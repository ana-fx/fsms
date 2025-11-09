<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreIngredientRequest;
use App\Http\Requests\UpdateIngredientRequest;
use App\Models\FoodCategory;
use App\Models\FoodItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class IngredientController extends Controller
{
    public function create()
    {
        // Get only parent categories (for first dropdown)
        $categories = FoodCategory::active()
            ->whereNull('parent_id')
            ->ordered()
            ->get();

        // Get all categories with parent relationships for JavaScript
        $allCategories = FoodCategory::active()
            ->with('parent')
            ->ordered()
            ->get();

        // Group sub-categories by parent_id for JavaScript
        // Include max_price for each sub-category
        $subCategoriesByParent = [];
        foreach ($allCategories as $category) {
            if ($category->parent_id) {
                if (!isset($subCategoriesByParent[$category->parent_id])) {
                    $subCategoriesByParent[$category->parent_id] = [];
                }
                $subCategoriesByParent[$category->parent_id][] = [
                    'id' => $category->id,
                    'name' => $category->name,
                    'max_price' => $category->max_price ? (float)$category->max_price : null,
                ];
            }
        }

        return view('supplier.ingredient-create', [
            'title' => 'Add Ingredient',
            'categories' => $categories,
            'subCategoriesByParent' => $subCategoriesByParent,
        ]);
    }

    public function store(StoreIngredientRequest $request)
    {
        // Get validated data from Form Request
        $validated = $request->validated();

        // Final security check: Get max_price from category (sub-category), not from ingredient
        // This ensures all ingredients in the same category have the same max_price limit
        $selectedCategory = FoodCategory::findOrFail($validated['food_category_id']);
        $finalMaxPriceValue = $selectedCategory->max_price;

        if ($finalMaxPriceValue !== null && $finalMaxPriceValue !== '' && is_numeric($finalMaxPriceValue)) {
            $finalMaxPriceFloat = (float) $finalMaxPriceValue;

            if ($finalMaxPriceFloat > 0) {
                $finalInputPrice = (float) $validated['price'];

                if ($finalInputPrice > $finalMaxPriceFloat) {
                    // Log this attempt for security monitoring
                    Log::warning('Price validation bypass attempt - SECURITY ALERT', [
                        'category_id' => $selectedCategory->id,
                        'category_name' => $selectedCategory->name,
                        'input_price' => $finalInputPrice,
                        'max_price' => $finalMaxPriceFloat,
                        'user_id' => Auth::id(),
                        'user_email' => Auth::user()->email ?? 'unknown',
                        'ip_address' => $request->ip(),
                    ]);

                    return redirect()->back()
                        ->withInput()
                        ->withErrors([
                            'price' => 'SECURITY: Price validation failed! Maximum allowed for category "' . $selectedCategory->name . '": Rp ' . number_format($finalMaxPriceFloat, 0, ',', '.') . ', Your input: Rp ' . number_format($finalInputPrice, 0, ',', '.')
                        ])
                        ->with('error', 'Price cannot exceed maximum limit for this category! This attempt has been logged.');
                }
            }
        }

        // Get the selected category (sub-category) to get its name
        $categoryName = $selectedCategory->name;

        // Combine sub_name with category name to create final name
        $subName = isset($validated['sub_name']) && !empty(trim($validated['sub_name'])) ? trim($validated['sub_name']) : null;
        $finalName = $subName ? $subName . ' ' . $categoryName : $categoryName;

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('ingredients', 'public');
        }

        $maxPurchase = isset($validated['max_purchase']) && $validated['max_purchase'] > 0 ? $validated['max_purchase'] : null;
        $maxTotalPrice = $maxPurchase ? $validated['price'] * $maxPurchase : null;

        FoodItem::create([
            'supplier_id' => Auth::id(),
            'food_category_id' => $validated['food_category_id'],
            'name' => $finalName,
            'sub_name' => $subName,
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'default_price_increment' => 500, // Default price increment
            'price_increment_type' => 'fixed', // Default type: fixed (Rp)
            'unit' => $validated['unit'],
            'stock' => $validated['stock'],
            'min_purchase' => $validated['min_purchase'],
            'max_purchase' => $maxPurchase,
            'image' => $imagePath,
            'is_active' => $request->boolean('is_active', true),
        ]);

        $message = 'Ingredient created successfully';
        if ($maxPurchase) {
            $message .= '. Maximum purchase limit: ' . $maxPurchase . ' ' . $validated['unit'] . ' (Max total: Rp ' . number_format($maxTotalPrice, 0, ',', '.') . ')';
        }

        return redirect()->route('supplier.ingredients')
            ->with('status', ['type' => 'success', 'message' => $message]);
    }

    public function edit(FoodItem $foodItem)
    {
        $this->authorizeOwnership($foodItem);

        // Get only parent categories (for first dropdown)
        $categories = FoodCategory::active()
            ->whereNull('parent_id')
            ->ordered()
            ->get();

        // Get all categories with parent relationships for JavaScript
        $allCategories = FoodCategory::active()
            ->with('parent')
            ->ordered()
            ->get();

        // Group sub-categories by parent_id for JavaScript
        // Include max_price for each sub-category
        $subCategoriesByParent = [];
        foreach ($allCategories as $category) {
            if ($category->parent_id) {
                if (!isset($subCategoriesByParent[$category->parent_id])) {
                    $subCategoriesByParent[$category->parent_id] = [];
                }
                $subCategoriesByParent[$category->parent_id][] = [
                    'id' => $category->id,
                    'name' => $category->name,
                    'max_price' => $category->max_price ? (float)$category->max_price : null,
                ];
            }
        }

        // Get the parent category of the current ingredient's category
        $currentParentCategory = null;
        if ($foodItem->foodCategory && $foodItem->foodCategory->parent_id) {
            $currentParentCategory = FoodCategory::find($foodItem->foodCategory->parent_id);
        }

        return view('supplier.ingredient-edit', [
            'title' => 'Edit Ingredient',
            'ingredient' => $foodItem,
            'categories' => $categories,
            'subCategoriesByParent' => $subCategoriesByParent,
            'currentParentCategory' => $currentParentCategory,
        ]);
    }

    public function update(UpdateIngredientRequest $request, FoodItem $foodItem)
    {
        $this->authorizeOwnership($foodItem);

        // Get validated data from Form Request
        $validated = $request->validated();

        // Final security check: Get max_price from category (sub-category), not from ingredient
        // This ensures all ingredients in the same category have the same max_price limit
        $selectedCategory = FoodCategory::findOrFail($validated['food_category_id']);
        $finalMaxPriceValue = $selectedCategory->max_price;

        if ($finalMaxPriceValue !== null && $finalMaxPriceValue !== '' && is_numeric($finalMaxPriceValue)) {
            $finalMaxPriceFloat = (float) $finalMaxPriceValue;

            if ($finalMaxPriceFloat > 0) {
                $finalInputPrice = (float) $validated['price'];

                if ($finalInputPrice > $finalMaxPriceFloat) {
                    // Log this attempt for security monitoring
                    Log::warning('Price validation bypass attempt - SECURITY ALERT', [
                        'food_item_id' => $foodItem->id,
                        'food_item_name' => $foodItem->name,
                        'category_id' => $selectedCategory->id,
                        'category_name' => $selectedCategory->name,
                        'input_price' => $finalInputPrice,
                        'max_price' => $finalMaxPriceFloat,
                        'user_id' => Auth::id(),
                        'user_email' => Auth::user()->email ?? 'unknown',
                        'ip_address' => $request->ip(),
                    ]);

                    return redirect()->back()
                        ->withInput()
                        ->withErrors([
                            'price' => 'SECURITY: Price validation failed! Maximum allowed for category "' . $selectedCategory->name . '": Rp ' . number_format($finalMaxPriceFloat, 0, ',', '.') . ', Your input: Rp ' . number_format($finalInputPrice, 0, ',', '.')
                        ])
                        ->with('error', 'Price cannot exceed maximum limit for this category! This attempt has been logged.');
                }
            }
        }

        // Get the selected category (sub-category) to get its name
        $selectedCategory = FoodCategory::findOrFail($validated['food_category_id']);
        $categoryName = $selectedCategory->name;

        // Combine sub_name with category name to create final name
        $subName = isset($validated['sub_name']) && !empty(trim($validated['sub_name'])) ? trim($validated['sub_name']) : null;
        $finalName = $subName ? $subName . ' ' . $categoryName : $categoryName;

        $maxPurchase = isset($validated['max_purchase']) && $validated['max_purchase'] > 0 ? $validated['max_purchase'] : null;
        $maxTotalPrice = $maxPurchase ? $validated['price'] * $maxPurchase : null;

        $data = [
            'food_category_id' => $validated['food_category_id'],
            'name' => $finalName,
            'sub_name' => $subName,
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'unit' => $validated['unit'],
            'stock' => $validated['stock'],
            'min_purchase' => $validated['min_purchase'],
            'max_purchase' => $maxPurchase,
            'is_active' => $request->boolean('is_active', true),
        ];

        if ($request->hasFile('image')) {
            if ($foodItem->image) {
                Storage::disk('public')->delete($foodItem->image);
            }
            $data['image'] = $request->file('image')->store('ingredients', 'public');
        }

        $foodItem->update($data);

        $message = 'Ingredient updated successfully';
        if ($maxPurchase) {
            $message .= '. Maximum purchase limit: ' . $maxPurchase . ' ' . $validated['unit'] . ' (Max total: Rp ' . number_format($maxTotalPrice, 0, ',', '.') . ')';
        }

        return redirect()->route('supplier.ingredients')->with('status', ['type' => 'success', 'message' => $message]);
    }

    public function destroy(FoodItem $foodItem)
    {
        $this->authorizeOwnership($foodItem);
        $foodItem->delete();
        return redirect()->route('supplier.ingredients')->with('status', ['type' => 'danger', 'message' => 'Ingredient deleted']);
    }

    private function authorizeOwnership(FoodItem $foodItem): void
    {
        abort_unless($foodItem->supplier_id === Auth::id(), 403);
    }
}
