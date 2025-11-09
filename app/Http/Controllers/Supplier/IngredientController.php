<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\FoodCategory;
use App\Models\FoodItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class IngredientController extends Controller
{
    public function create()
    {
        // Get parent categories with their sub-categories
        $categories = FoodCategory::active()
            ->whereNull('parent_id')
            ->with(['children' => function($q) {
                $q->active()->ordered();
            }])
            ->ordered()
            ->get();
        return view('supplier.ingredient-create', [
            'title' => 'Add Ingredient',
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'food_category_id' => ['required', 'exists:food_categories,id'],
            'price' => ['required', 'numeric', 'min:0'],
            'unit' => ['required', 'string', 'max:20'],
            'stock' => ['required', 'integer', 'min:0'],
            'min_purchase' => ['required', 'integer', 'min:0'],
            'max_purchase' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        // Validate max_purchase >= min_purchase if max_purchase is set
        if (isset($validated['max_purchase']) && $validated['max_purchase'] !== null) {
            if ($validated['max_purchase'] < $validated['min_purchase']) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['max_purchase' => 'Maximum purchase must be greater than or equal to minimum purchase.']);
            }
        }

        // Note: max_price validation will be checked after ingredient is created
        // because max_price is set by admin, not supplier
        // However, we cannot validate it during creation as the ingredient doesn't exist yet
        // The validation will happen when admin sets max_price

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('ingredients', 'public');
        }

        $maxPurchase = isset($validated['max_purchase']) && $validated['max_purchase'] > 0 ? $validated['max_purchase'] : null;
        $maxTotalPrice = $maxPurchase ? $validated['price'] * $maxPurchase : null;

        FoodItem::create([
            'supplier_id' => Auth::id(),
            'food_category_id' => $validated['food_category_id'],
            'name' => $validated['name'],
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
        // Get parent categories with their sub-categories
        $categories = FoodCategory::active()
            ->whereNull('parent_id')
            ->with(['children' => function($q) {
                $q->active()->ordered();
            }])
            ->ordered()
            ->get();
        return view('supplier.ingredient-edit', [
            'title' => 'Edit Ingredient',
            'ingredient' => $foodItem,
            'categories' => $categories,
        ]);
    }

    public function update(Request $request, FoodItem $foodItem)
    {
        $this->authorizeOwnership($foodItem);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'food_category_id' => ['required', 'exists:food_categories,id'],
            'price' => ['required', 'numeric', 'min:0'],
            'unit' => ['required', 'string', 'max:20'],
            'stock' => ['required', 'integer', 'min:0'],
            'min_purchase' => ['required', 'integer', 'min:0'],
            'max_purchase' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        // Validate max_purchase >= min_purchase if max_purchase is set
        if (isset($validated['max_purchase']) && $validated['max_purchase'] !== null) {
            if ($validated['max_purchase'] < $validated['min_purchase']) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['max_purchase' => 'Maximum purchase must be greater than or equal to minimum purchase.']);
            }
        }

        // Validate price against max_price limit set by admin
        if ($foodItem->max_price !== null && $foodItem->max_price > 0) {
            if ($validated['price'] > $foodItem->max_price) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors([
                        'price' => 'Price cannot exceed the maximum price limit of Rp ' . number_format($foodItem->max_price, 0, ',', '.') . ' set by administrator.'
                    ]);
            }
        }

        $maxPurchase = isset($validated['max_purchase']) && $validated['max_purchase'] > 0 ? $validated['max_purchase'] : null;
        $maxTotalPrice = $maxPurchase ? $validated['price'] * $maxPurchase : null;

        $data = [
            'food_category_id' => $validated['food_category_id'],
            'name' => $validated['name'],
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
