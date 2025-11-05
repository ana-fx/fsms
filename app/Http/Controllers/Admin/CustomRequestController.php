<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FoodRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomRequestController extends Controller
{
    /**
     * Display a listing of custom requests (where food_item_id is null).
     */
    public function index(Request $request)
    {
        $query = FoodRequest::whereNull('food_item_id')
            ->with(['foodCategory', 'customer', 'assignedSupplier'])
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($customerQuery) use ($search) {
                      $customerQuery->where('name', 'like', "%{$search}%")
                                    ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $customRequests = $query->paginate(15);

        // Get stats
        $allCustomRequests = FoodRequest::whereNull('food_item_id')->get();
        $stats = [
            'all' => $allCustomRequests->count(),
            'pending' => $allCustomRequests->where('status', 'pending')->count(),
            'payment_pending' => $allCustomRequests->where('status', 'payment_pending')->count(),
            'paid' => $allCustomRequests->where('status', 'paid')->count(),
            'rejected' => $allCustomRequests->where('status', 'rejected')->count(),
        ];

        return view('admin.custom-requests.index', compact('customRequests', 'stats'));
    }

    /**
     * Display the specified custom request.
     */
    public function show(FoodRequest $customRequest)
    {
        // Verify this is a custom request
        if ($customRequest->food_item_id !== null) {
            abort(404, 'This is not a custom request.');
        }

        $customRequest->load(['foodCategory', 'customer', 'assignedSupplier']);

        // Get all suppliers for assignment
        $suppliers = User::whereHas('roles', function($q) {
            $q->where('name', 'supplier');
        })->get();

        return view('admin.custom-requests.show', compact('customRequest', 'suppliers'));
    }

    /**
     * Approve custom request and optionally assign supplier.
     */
    public function approve(Request $request, FoodRequest $customRequest)
    {
        // Verify this is a custom request
        if ($customRequest->food_item_id !== null) {
            return redirect()->back()
                ->with('status', ['type' => 'error', 'message' => 'This is not a custom request.']);
        }

        // Verify status is pending
        if ($customRequest->status !== 'pending') {
            return redirect()->back()
                ->with('status', ['type' => 'error', 'message' => 'Only pending requests can be approved.']);
        }

        $validated = $request->validate([
            'assigned_supplier_id' => 'required|exists:users,id',
            'price' => 'required|numeric|min:0',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        // Verify supplier exists and has supplier role
        $supplier = User::findOrFail($validated['assigned_supplier_id']);
        if (!$supplier->isSupplier()) {
            return redirect()->back()
                ->with('status', ['type' => 'error', 'message' => 'Selected user is not a supplier.']);
        }

        // Update custom request
        $customRequest->update([
            'assigned_supplier_id' => $validated['assigned_supplier_id'],
            'price' => $validated['price'],
            'status' => 'payment_pending', // Change to payment_pending after approval
            'admin_notes' => $validated['admin_notes'] ?? null,
        ]);

        return redirect()->route('admin.custom-requests.index')
            ->with('status', ['type' => 'success', 'message' => 'Custom request approved and assigned to supplier successfully!']);
    }

    /**
     * Reject custom request.
     */
    public function reject(Request $request, FoodRequest $customRequest)
    {
        // Verify this is a custom request
        if ($customRequest->food_item_id !== null) {
            return redirect()->back()
                ->with('status', ['type' => 'error', 'message' => 'This is not a custom request.']);
        }

        // Verify status is pending
        if ($customRequest->status !== 'pending') {
            return redirect()->back()
                ->with('status', ['type' => 'error', 'message' => 'Only pending requests can be rejected.']);
        }

        $validated = $request->validate([
            'admin_notes' => 'required|string|max:1000',
        ]);

        // Update custom request
        $customRequest->update([
            'status' => 'rejected',
            'admin_notes' => $validated['admin_notes'],
        ]);

        return redirect()->route('admin.custom-requests.index')
            ->with('status', ['type' => 'success', 'message' => 'Custom request rejected successfully!']);
    }
}
