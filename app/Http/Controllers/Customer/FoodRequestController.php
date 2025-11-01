<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\FoodCategory;
use App\Models\FoodItem;
use App\Models\FoodRequest;
use App\Models\User;
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
        /** @var User $user */
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

        // Check if using saved address
        $selectedAddressId = $request->input('selected_address_id');
        $addressOption = $request->input('address_option', 'new');

        if ($addressOption === 'saved' && $selectedAddressId) {
            // Get address from saved addresses
            /** @var User $user */
            $user = Auth::user();
            $savedAddress = $user->deliveryAddresses()->find($selectedAddressId);

            if (!$savedAddress) {
                return redirect()->route('customer.requests.checkout')
                    ->with('error', 'Selected address not found.');
            }

            $validated = [
                'recipient_name' => $savedAddress->recipient_name,
                'recipient_phone' => $savedAddress->recipient_phone,
                'delivery_address' => $savedAddress->delivery_address,
                'city' => $savedAddress->city,
                'postal_code' => $savedAddress->postal_code,
                'delivery_notes' => $request->input('delivery_notes'),
                'needed_date' => $request->validate(['needed_date' => 'required|date|after:today'])['needed_date'],
            ];
        } else {
        $validated = $request->validate([
                'recipient_name' => 'required|string|max:255',
                'recipient_phone' => 'required|string|max:20',
                'delivery_address' => 'required|string',
                'city' => 'required|string|max:100',
                'postal_code' => 'nullable|string|max:10',
                'delivery_notes' => 'nullable|string',
            'needed_date' => 'required|date|after:today',
        ]);
        }

        // Group cart items by supplier
        $ordersBySupplier = [];
        $requestIds = [];

        foreach ($cart as $itemId => $item) {
            $product = FoodItem::with(['foodCategory', 'supplier'])->find($itemId);
            if ($product) {
                $subtotal = $product->price * $item['quantity'];

                // Initialize supplier group if not exists
                if (!isset($ordersBySupplier[$product->supplier_id])) {
                    $ordersBySupplier[$product->supplier_id] = [
                        'supplier' => $product->supplier,
                        'items' => [],
                        'total' => 0,
                        'request_ids' => [],
                    ];
                }

                // Add item to supplier group
                $ordersBySupplier[$product->supplier_id]['items'][] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'subtotal' => $subtotal,
                ];
                $ordersBySupplier[$product->supplier_id]['total'] += $subtotal;

                // Create FoodRequest
                $foodRequest = FoodRequest::create([
                    'customer_id' => Auth::id(),
                    'food_category_id' => $product->food_category_id,
                    'food_item_id' => $itemId,
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
                $requestIds[] = $foodRequest->id;
                $ordersBySupplier[$product->supplier_id]['request_ids'][] = $foodRequest->id;
            }
        }

        // Clear cart after successful checkout
        session()->forget('cart');

        // Store order data in session for success page (grouped by supplier)
        session()->put('order_success', [
            'suppliers' => $ordersBySupplier,
            'delivery' => $validated,
            'request_ids' => $requestIds,
            'order_date' => now()->toDateTimeString(),
        ]);

        // Redirect to first order's invoice page using custom slug
        $firstRequest = FoodRequest::find($requestIds[0]);
        if ($firstRequest) {
            return redirect()->route('customer.requests.show', $firstRequest)
                ->with('status', ['type' => 'success', 'message' => 'Order placed successfully! Your order is being processed.']);
        }

        // Fallback to dashboard if something went wrong
        return redirect()->route('customer.requests.index')
            ->with('status', ['type' => 'success', 'message' => 'Order placed successfully!']);
    }

    /**
     * Display the invoice for a specific request.
     */
    public function show(FoodRequest $request)
    {
        Gate::authorize('view', $request);

        // Check if we're showing a single invoice or multi-invoice from order_success session
        $orderData = session()->get('order_success');

        if ($orderData && in_array($request->id, $orderData['request_ids'])) {
            // Show multi-invoice from successful checkout
            $requests = FoodRequest::whereIn('id', $orderData['request_ids'])
                ->with(['foodCategory', 'foodItem'])
                ->orderBy('created_at', 'desc')
                ->get();

            // Check if payment proof already uploaded for all requests
            $paymentProofUploaded = $requests->whereNotNull('payment_proof')->count() === $requests->count();

            return view('customer.requests.success', compact('orderData', 'requests', 'paymentProofUploaded'));
        } else {
            // Show single invoice (normal view)
            $request->load(['foodCategory', 'foodItem', 'customer', 'approvedBy']);

            // Prepare order data in same format as success page
            $orderData = [
                'items' => [],
                'delivery' => [
                    'recipient_name' => $request->recipient_name,
                    'recipient_phone' => $request->recipient_phone,
                    'delivery_address' => $request->delivery_address,
                    'city' => $request->city,
                    'postal_code' => $request->postal_code,
                    'delivery_notes' => $request->delivery_notes,
                    'needed_date' => $request->needed_date,
                ],
                'total' => 0,
            ];

            // Build item structure for invoice
            if ($request->foodItem) {
                $subtotal = $request->foodItem->price * $request->quantity;
                $orderData['items'][] = [
                    'product' => $request->foodItem,
                    'quantity' => $request->quantity,
                    'subtotal' => $subtotal,
                ];
                $orderData['total'] = $subtotal;
            } else {
                // Fallback if no food_item
                $orderData['items'][] = [
                    'product' => (object) [
                        'name' => $request->title,
                        'description' => $request->description,
                        'price' => 0,
                        'unit' => $request->unit,
                        'foodCategory' => $request->foodCategory,
                    ],
                    'quantity' => $request->quantity,
                    'subtotal' => 0,
                ];
            }

            $requests = collect([$request]);
            $paymentProofUploaded = $request->payment_proof !== null;

            return view('customer.requests.success', compact('orderData', 'requests', 'paymentProofUploaded', 'request'));
        }
    }

    /**
     * Show the edit checkout page with existing request data.
     */
    public function edit(FoodRequest $request)
    {
        Gate::authorize('update', $request);

        // Only allow editing pending requests
        if ($request->status !== 'pending') {
            return redirect()->route('customer.requests.show', $request)
                ->with('status', ['type' => 'error', 'message' => 'Cannot edit request. Only pending requests can be edited.']);
        }

        // Load relations
        $request->load(['foodCategory', 'foodItem']);

        // Get cart items - single item for this request
        $cartItems = [];
        $cart = session()->get('cart', []);

        // Check if this request's product is in cart
        if ($request->food_item_id && isset($cart[$request->food_item_id])) {
            // Use existing cart
            foreach ($cart as $itemId => $item) {
                $product = FoodItem::with('foodCategory')->find($itemId);
                if ($product) {
                    $subtotal = $product->price * $item['quantity'];
                    $cartItems[] = [
                        'product' => $product,
                        'quantity' => $item['quantity'],
                        'subtotal' => $subtotal,
                    ];
                }
            }
        } else {
            // Build cart from request data
            if ($request->foodItem) {
                $subtotal = $request->foodItem->price * $request->quantity;
                $cartItems[] = [
                    'product' => $request->foodItem,
                    'quantity' => $request->quantity,
                    'subtotal' => $subtotal,
                ];

                // Add to cart for checkout
                $cart[$request->food_item_id] = ['quantity' => $request->quantity];
                session()->put('cart', $cart);
            }
        }

        $total = collect($cartItems)->sum('subtotal');

        // Get user and addresses
        /** @var User $user */
        $user = Auth::user();
        $addresses = $user->deliveryAddresses()->orderBy('is_default', 'desc')->get();
        $defaultAddress = $user->defaultDeliveryAddress();

        return view('customer.requests.checkout', compact('cartItems', 'total', 'addresses', 'defaultAddress', 'user', 'request'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FoodRequest $foodRequest)
    {
        Gate::authorize('update', $foodRequest);

        $selectedAddressId = $request->input('selected_address_id');
        $addressOption = $request->input('address_option', 'new');

        if ($addressOption === 'saved' && $selectedAddressId) {
            /** @var User $user */
            $user = Auth::user();
            $savedAddress = $user->deliveryAddresses()->find($selectedAddressId);

            if (!$savedAddress) {
                return redirect()->back()->with('error', 'Selected address not found.');
            }

            $validated = [
                'recipient_name' => $savedAddress->recipient_name,
                'recipient_phone' => $savedAddress->recipient_phone,
                'delivery_address' => $savedAddress->delivery_address,
                'city' => $savedAddress->city,
                'postal_code' => $savedAddress->postal_code,
                'delivery_notes' => $request->input('delivery_notes'),
                'needed_date' => $request->validate(['needed_date' => 'required|date|after:today'])['needed_date'],
            ];
        } else {
            $validated = $request->validate([
                'recipient_name' => 'required|string|max:255',
                'recipient_phone' => 'required|string|max:20',
                'delivery_address' => 'required|string',
                'city' => 'required|string|max:100',
                'postal_code' => 'nullable|string|max:10',
                'delivery_notes' => 'nullable|string',
                'needed_date' => 'required|date|after:today',
            ]);
        }

        $foodRequest->update($validated);

        return redirect()->route('customer.requests.show', $foodRequest)
            ->with('status', ['type' => 'success', 'message' => 'Order updated successfully!']);
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

    /**
     * Upload payment proof for order requests.
     */
    public function uploadPaymentProof(Request $request)
    {
        $validated = $request->validate([
            'request_ids' => 'required|array',
            'request_ids.*' => 'required|exists:food_requests,id',
            'payment_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120', // 5MB max
            'payment_notes' => 'nullable|string|max:500',
        ]);

        // Verify all requests belong to current user
        /** @var User $user */
        $user = Auth::user();
        $requests = FoodRequest::whereIn('id', $validated['request_ids'])
            ->where('customer_id', $user->id)
            ->get();

        if ($requests->count() !== count($validated['request_ids'])) {
            return redirect()->back()
                ->with('status', ['type' => 'error', 'message' => 'Invalid request. Some requests were not found.']);
        }

        // Upload payment proof
        if ($request->hasFile('payment_proof')) {
            $file = $request->file('payment_proof');
            $fileName = 'payment_proof_' . time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('payment_proofs', $fileName, 'public');

            // Update all requests with payment proof
            foreach ($requests as $foodRequest) {
                $updateData = [
                    'payment_proof' => $path,
                    'payment_proof_uploaded_at' => now(),
                ];

                // Add payment notes if provided
                if (!empty($validated['payment_notes'])) {
                    $existingNotes = $foodRequest->notes ? $foodRequest->notes . "\n\n" : '';
                    $updateData['notes'] = $existingNotes . 'Payment Notes: ' . $validated['payment_notes'];
                }

                $foodRequest->update($updateData);
            }

            // Clear order success session after successful upload
            session()->forget('order_success');

            return redirect()->route('customer.requests.index')
                ->with('status', ['type' => 'success', 'message' => 'Payment proof uploaded successfully! Your order is being processed.']);
        }

        return redirect()->back()
            ->with('status', ['type' => 'error', 'message' => 'Failed to upload payment proof. Please try again.']);
    }

}
