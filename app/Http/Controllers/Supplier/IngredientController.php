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
        $categories = FoodCategory::active()->ordered()->get();
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
            'is_active' => ['sometimes', 'boolean'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('ingredients', 'public');
        }

        FoodItem::create([
            'supplier_id' => Auth::id(),
            'food_category_id' => $validated['food_category_id'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'unit' => $validated['unit'],
            'stock' => $validated['stock'],
            'min_purchase' => $validated['min_purchase'],
            'image' => $imagePath,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('supplier.ingredients')
            ->with('status', ['type' => 'success', 'message' => 'Ingredient created successfully']);
    }

    public function edit(FoodItem $foodItem)
    {
        $this->authorizeOwnership($foodItem);
        $categories = FoodCategory::active()->ordered()->get();
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
            'is_active' => ['sometimes', 'boolean'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        $data = [
            'food_category_id' => $validated['food_category_id'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'unit' => $validated['unit'],
            'stock' => $validated['stock'],
            'min_purchase' => $validated['min_purchase'],
            'is_active' => $request->boolean('is_active', true),
        ];

        if ($request->hasFile('image')) {
            if ($foodItem->image) {
                Storage::disk('public')->delete($foodItem->image);
            }
            $data['image'] = $request->file('image')->store('ingredients', 'public');
        }

        $foodItem->update($data);

        return redirect()->route('supplier.ingredients')->with('status', ['type' => 'success', 'message' => 'Ingredient updated successfully']);
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
