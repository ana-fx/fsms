<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\FoodItem;
use App\Models\FoodRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    /**
     * Display a listing of orders for this supplier.
     */
    public function index(Request $request)
    {
        // Get supplier's ingredient IDs
        $ingredientIds = FoodItem::where('supplier_id', Auth::id())->pluck('id');

        // Base query for orders - includes both regular orders (by food_item_id) and custom requests (by assigned_supplier_id)
        $query = FoodRequest::where(function($q) use ($ingredientIds) {
                // Regular orders from supplier's ingredients
                $q->whereIn('food_item_id', $ingredientIds)
                  // OR custom requests assigned to this supplier
                  ->orWhere('assigned_supplier_id', Auth::id());
            })
            ->with(['foodItem.foodCategory', 'customer', 'foodCategory'])
            ->orderBy('created_at', 'desc');

        // Filter by status if provided
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Search by order number or customer name
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($customerQuery) use ($search) {
                      $customerQuery->where('name', 'like', "%{$search}%")
                                    ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $orders = $query->paginate(15);

        // Get stats for all orders (regular + custom assigned)
        $allOrders = FoodRequest::where(function($q) use ($ingredientIds) {
            $q->whereIn('food_item_id', $ingredientIds)
              ->orWhere('assigned_supplier_id', Auth::id());
        })->get();
        $stats = [
            'all' => $allOrders->count(),
            'payment_pending' => $allOrders->where('status', 'payment_pending')->count(),
            'paid' => $allOrders->where('status', 'paid')->count(),
            'shipping' => $allOrders->where('status', 'shipping')->count(),
            'delivered' => $allOrders->where('status', 'delivered')->count(),
            'completed' => $allOrders->where('status', 'completed')->count(),
            'rejected' => $allOrders->where('status', 'rejected')->count(),
        ];

        return view('supplier.orders.index', compact('orders', 'stats'));
    }

    /**
     * Display the specified order.
     */
    public function show(FoodRequest $order)
    {
        // Verify this order belongs to supplier (either regular order or assigned custom request)
        $ingredientIds = FoodItem::where('supplier_id', Auth::id())->pluck('id');
        $isRegularOrder = $order->food_item_id && in_array($order->food_item_id, $ingredientIds->toArray());
        $isAssignedCustomRequest = $order->assigned_supplier_id === Auth::id();

        if (!$isRegularOrder && !$isAssignedCustomRequest) {
            abort(403, 'You do not have permission to view this order.');
        }

        $order->load(['foodItem.foodCategory', 'customer', 'shippedBy', 'foodCategory']);

        return view('supplier.orders.show', compact('order'));
    }

    /**
     * Upload delivery proof for an order.
     */
    public function uploadDeliveryProof(Request $request, FoodRequest $order)
    {
        // Verify this order belongs to supplier (either regular order or assigned custom request)
        $ingredientIds = FoodItem::where('supplier_id', Auth::id())->pluck('id');
        $isRegularOrder = $order->food_item_id && in_array($order->food_item_id, $ingredientIds->toArray());
        $isAssignedCustomRequest = $order->assigned_supplier_id === Auth::id();

        if (!$isRegularOrder && !$isAssignedCustomRequest) {
            abort(403, 'You do not have permission to upload delivery proof for this order.');
        }

        // Only allow upload for paid status
        if ($order->status !== 'paid') {
            return redirect()->back()
                ->with('status', ['type' => 'error', 'message' => 'Received items information can only be uploaded for orders that are paid.']);
        }

        try {
            $validated = $request->validate([
                'received_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120', // 5MB max
                'delivery_notes' => 'nullable|string|max:500',
            ]);

            if ($request->hasFile('received_proof')) {
                $file = $request->file('received_proof');

                // Generate unique file name
                $fileName = 'received_proof_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('received_proofs', $fileName, 'public');

                if (!$path) {
                    return redirect()->back()
                        ->with('status', ['type' => 'error', 'message' => 'Failed to save file. Please check storage permissions.']);
                }

                // Delete old received proof if exists
                if ($order->received_proof && Storage::disk('public')->exists($order->received_proof)) {
                    Storage::disk('public')->delete($order->received_proof);
                }

                // Update order with received proof
                $updateData = [
                    'received_proof' => $path,
                    'received_proof_uploaded_at' => now(),
                ];

                // Add delivery notes if provided
                if (!empty($validated['delivery_notes'])) {
                    $existingNotes = $order->notes ? $order->notes . "\n\n" : '';
                    $updateData['notes'] = $existingNotes . 'Delivery Notes: ' . $validated['delivery_notes'];
                }

                $order->update($updateData);

                return redirect()->back()
                    ->with('status', ['type' => 'success', 'message' => 'Delivery proof uploaded successfully!']);
            }

            return redirect()->back()
                ->with('status', ['type' => 'error', 'message' => 'No file was uploaded. Please select a file and try again.']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->with('status', ['type' => 'error', 'message' => 'Validation failed: ' . implode(', ', array_map(function($messages) {
                    return implode(', ', $messages);
                }, $e->errors()))]);
        } catch (\Exception $e) {
            Log::error('Delivery proof upload error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'order_id' => $order->id,
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('status', ['type' => 'error', 'message' => 'An error occurred while uploading delivery proof: ' . $e->getMessage()]);
        }
    }
}

