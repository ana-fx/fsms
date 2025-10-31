<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\FoodCategory;
use App\Models\FoodItem;
use App\Models\FoodRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class FoodRequestController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $requests = FoodRequest::with(['foodCategory', 'approvedBy'])
            ->byCustomer(Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('customer.requests.index', compact('requests'));
    }

    /**
     * Show the checkout page with cart items and delivery address form.
     */
    public function checkout()
    {
        // Get cart items from session
        $cartItems = [];
        $cart = session()->get('cart', []);
        $total = 0;
        
        foreach ($cart as $itemId => $item) {
            $product = FoodItem::with('foodCategory')->find($itemId);
            if ($product) {
                $subtotal = $product->price * $item['quantity'];
                $cartItems[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'subtotal' => $subtotal,
                ];
                $total += $subtotal;
            }
        }
        
        // Redirect to cart if cart is empty
        if (empty($cartItems)) {
            return redirect()->route('customer.cart')
                ->with('error', 'Your cart is empty. Please add items to cart first.');
        }
        
        // Get user's delivery addresses
        $user = Auth::user();
        $addresses = $user->deliveryAddresses()->orderBy('is_default', 'desc')->get();
        $defaultAddress = $user->defaultDeliveryAddress();
        
        return view('customer.requests.checkout', compact('cartItems', 'total', 'addresses', 'defaultAddress', 'user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate cart items exist
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('customer.cart')
                ->with('error', 'Your cart is empty.');
        }
        
        $validated = $request->validate([
            'recipient_name' => 'required|string|max:255',
            'recipient_phone' => 'required|string|max:20',
            'delivery_address' => 'required|string',
            'city' => 'required|string|max:100',
            'postal_code' => 'nullable|string|max:10',
            'delivery_notes' => 'nullable|string',
            'needed_date' => 'required|date|after:today',
        ]);

        // Create a request for each cart item
        foreach ($cart as $itemId => $item) {
            $product = FoodItem::with('foodCategory')->find($itemId);
            if ($product) {
                FoodRequest::create([
                    'customer_id' => Auth::id(),
                    'food_category_id' => $product->food_category_id,
                    'title' => $product->name . ' Request',
                    'description' => 'Request for ' . $item['quantity'] . ' ' . $product->unit . ' of ' . $product->name,
                    'quantity' => $item['quantity'],
                    'unit' => $product->unit,
                    'recipient_name' => $validated['recipient_name'],
                    'recipient_phone' => $validated['recipient_phone'],
                    'delivery_address' => $validated['delivery_address'],
                    'city' => $validated['city'],
                    'postal_code' => $validated['postal_code'] ?? null,
                    'delivery_notes' => $validated['delivery_notes'] ?? null,
                    'needed_date' => $validated['needed_date'],
                    'requested_date' => now()->toDateString(),
                    'status' => 'pending',
                ]);
            }
        }

        // Clear cart after successful checkout
        session()->forget('cart');

        return redirect()->route('customer.requests.index')
            ->with('success', 'Order placed successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(FoodRequest $request)
    {
        Gate::authorize('view', $request);

        $request->load(['foodCategory', 'approvedBy']);
        return view('customer.requests.show', compact('request'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FoodRequest $request)
    {
        Gate::authorize('update', $request);

        $categories = FoodCategory::active()->ordered()->get();
        return view('customer.requests.edit', compact('request', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FoodRequest $foodRequest)
    {
        Gate::authorize('update', $foodRequest);

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

        return redirect()->route('customer.requests.index')
            ->with('success', 'Permintaan bahan makanan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FoodRequest $request)
    {
        Gate::authorize('delete', $request);

        $request->delete();

        return redirect()->route('customer.requests.index')
            ->with('success', 'Permintaan bahan makanan berhasil dihapus!');
    }
}
