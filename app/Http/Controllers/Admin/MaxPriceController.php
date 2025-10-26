<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FoodCategory;
use App\Models\MaxPriceSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MaxPriceController extends Controller
{
    /**
     * Display the max price settings page.
     */
    public function index()
    {
        $categories = FoodCategory::with('maxPriceSetting')->get();

        return view('admin.max-price', compact('categories'));
    }

    /**
     * Store or update max price settings.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:food_categories,id',
            'max_price' => 'required|numeric|min:0',
            'unit' => 'required|string|max:20',
            'notes' => 'nullable|string',
        ]);

        MaxPriceSetting::updateOrCreate(
            ['food_category_id' => $validated['category_id']],
            [
                'max_price' => $validated['max_price'],
                'unit' => $validated['unit'],
                'notes' => $validated['notes'],
                'updated_by' => auth()->id(),
            ]
        );

        return redirect()->route('admin.max-price')
            ->with('success', 'Harga maksimal berhasil disimpan!');
    }

    /**
     * Update max price setting.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'max_price' => 'required|numeric|min:0',
            'unit' => 'required|string|max:20',
            'notes' => 'nullable|string',
        ]);

        $setting = MaxPriceSetting::findOrFail($id);
        $setting->update([
            'max_price' => $validated['max_price'],
            'unit' => $validated['unit'],
            'notes' => $validated['notes'],
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('admin.max-price')
            ->with('success', 'Harga maksimal berhasil diupdate!');
    }
}

