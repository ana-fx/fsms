<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FoodCategory;
use App\Models\FoodItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class IngredientController extends Controller
{
    /**
     * Display the ingredients page.
     */
    public function index(Request $request)
    {
        $query = FoodItem::with(['supplier', 'foodCategory']);

        // Search by name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('food_category_id', $request->category);
        }

        // Filter by supplier
        if ($request->filled('supplier')) {
            $query->where('supplier_id', $request->supplier);
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Filter by stock
        if ($request->filled('stock')) {
            if ($request->stock === 'low') {
                $query->where('stock', '<=', 10);
            } elseif ($request->stock === 'in_stock') {
                $query->where('stock', '>', 0);
            } elseif ($request->stock === 'out_of_stock') {
                $query->where('stock', '=', 0);
            }
        }

        $products = $query->orderBy('created_at', 'desc')->get();

        // Get categories and suppliers for filter dropdowns
        $categories = FoodCategory::active()->ordered()->get();
        $suppliers = User::whereHas('roles', function($q) {
            $q->where('name', 'supplier');
        })->get();

        return view('admin.ingredients.index', compact('products', 'categories', 'suppliers'));
    }

    /**
     * Update default price increment for an ingredient.
     */
    public function updatePriceIncrement(Request $request, FoodItem $foodItem)
    {
        $validated = $request->validate([
            'default_price_increment' => 'required|numeric|min:0',
            'price_increment_type' => 'required|in:fixed,percentage',
        ]);

        // If percentage, ensure it doesn't exceed 100%
        if ($validated['price_increment_type'] === 'percentage' && $validated['default_price_increment'] > 100) {
            return response()->json([
                'success' => false,
                'message' => 'Percentage cannot exceed 100%',
            ], 422);
        }

        $foodItem->update([
            'default_price_increment' => $validated['default_price_increment'],
            'price_increment_type' => $validated['price_increment_type'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Default price increment updated successfully',
            'default_price_increment' => $foodItem->default_price_increment,
            'price_increment_type' => $foodItem->price_increment_type,
        ]);
    }

    /**
     * Update maximum price for an ingredient.
     */
    public function updateMaxPrice(Request $request, FoodItem $foodItem)
    {
        $validated = $request->validate([
            'max_price' => ['nullable', 'numeric', 'min:0'],
        ]);

        $maxPrice = isset($validated['max_price']) && $validated['max_price'] > 0 ? $validated['max_price'] : null;

        // Validate price <= max_price if max_price is set
        if ($maxPrice !== null && $foodItem->price > $maxPrice) {
            return response()->json([
                'success' => false,
                'message' => 'Current price (Rp ' . number_format($foodItem->price, 0, ',', '.') . ') cannot exceed maximum price limit.',
            ], 422);
        }

        $foodItem->update([
            'max_price' => $maxPrice,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Maximum price updated successfully',
            'max_price' => $foodItem->max_price,
            'price' => $foodItem->price,
        ]);
    }
}

