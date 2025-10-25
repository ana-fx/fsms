<?php

namespace App\Http\Controllers\Foundation;

use App\Http\Controllers\Controller;
use App\Models\FoodCategory;
use App\Models\FoodRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FoodRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $requests = FoodRequest::with(['foodCategory', 'approvedBy'])
            ->byFoundation(Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('foundation.requests.index', compact('requests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = FoodCategory::active()->ordered()->get();
        return view('foundation.requests.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'food_category_id' => 'required|exists:food_categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'quantity' => 'required|numeric|min:0.01',
            'unit' => 'required|string|max:50',
            'notes' => 'nullable|string',
            'needed_date' => 'required|date|after:today',
        ]);

        $validated['foundation_id'] = Auth::id();
        $validated['requested_date'] = now()->toDateString();
        $validated['status'] = 'pending';

        FoodRequest::create($validated);

        return redirect()->route('foundation.requests.index')
            ->with('success', 'Permintaan bahan makanan berhasil diajukan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(FoodRequest $request)
    {
        $this->authorize('view', $request);

        $request->load(['foodCategory', 'approvedBy']);
        return view('foundation.requests.show', compact('request'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FoodRequest $request)
    {
        $this->authorize('update', $request);

        $categories = FoodCategory::active()->ordered()->get();
        return view('foundation.requests.edit', compact('request', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FoodRequest $foodRequest)
    {
        $this->authorize('update', $foodRequest);

        $validated = $request->validate([
            'food_category_id' => 'required|exists:food_categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'quantity' => 'required|numeric|min:0.01',
            'unit' => 'required|string|max:50',
            'notes' => 'nullable|string',
            'needed_date' => 'required|date|after:today',
        ]);

        $foodRequest->update($validated);

        return redirect()->route('foundation.requests.index')
            ->with('success', 'Permintaan bahan makanan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FoodRequest $request)
    {
        $this->authorize('delete', $request);

        $request->delete();

        return redirect()->route('foundation.requests.index')
            ->with('success', 'Permintaan bahan makanan berhasil dihapus!');
    }
}
