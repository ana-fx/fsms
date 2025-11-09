<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\FoodItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Get cart items from session
     */
    private function getCart()
    {
        return session()->get('cart', []);
    }

    /**
     * Save cart to session
     */
    private function saveCart(array $cart)
    {
        session()->put('cart', $cart);
    }

    /**
     * Display the cart page
     */
    public function index()
    {
        $cart = $this->getCart();
        $items = [];
        $total = 0;

        foreach ($cart as $itemId => $item) {
            $product = FoodItem::with('foodCategory')->find($itemId);
            if ($product) {
                $finalPrice = $product->getFinalPrice();
                $subtotal = $finalPrice * $item['quantity'];
                $items[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'subtotal' => $subtotal,
                    'final_price' => $finalPrice,
                ];
                $total += $subtotal;
            }
        }

        return view('customer.cart', compact('items', 'total'));
    }

    /**
     * Add item to cart
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:food_items,id',
            'quantity' => 'required|numeric|min:0.01',
        ]);

        $product = FoodItem::findOrFail($request->product_id);

        $cart = $this->getCart();
        $itemId = $request->product_id;

        if (isset($cart[$itemId])) {
            // Update quantity (add to existing)
            $newQuantity = $cart[$itemId]['quantity'] + $request->quantity;

            // Validate purchase quantity (min, max, stock)
            $validation = $product->validatePurchaseQuantity($newQuantity);
            if (!$validation['valid']) {
                return response()->json([
                    'success' => false,
                    'message' => $validation['message']
                ], 400);
            }

            $cart[$itemId]['quantity'] = $newQuantity;
        } else {
            // Add new item - validate purchase quantity
            $validation = $product->validatePurchaseQuantity($request->quantity);
            if (!$validation['valid']) {
                return response()->json([
                    'success' => false,
                    'message' => $validation['message']
                ], 400);
            }

            $cart[$itemId] = [
                'quantity' => $request->quantity,
            ];
        }

        $this->saveCart($cart);

        return response()->json([
            'success' => true,
            'message' => 'Item added to cart successfully',
            'cart_count' => $this->getCartCount(),
        ]);
    }

    /**
     * Update item quantity in cart
     */
    public function update(Request $request, $itemId)
    {
        $request->validate([
            'quantity' => 'required|numeric|min:0.01',
        ]);

        $product = FoodItem::findOrFail($itemId);
        $cart = $this->getCart();

        if (!isset($cart[$itemId])) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found in cart'
            ], 404);
        }

        if ($request->quantity <= 0) {
            // Remove item if quantity is 0 or less
            unset($cart[$itemId]);
        } else {
            // Validate purchase quantity (min, max, stock)
            $validation = $product->validatePurchaseQuantity($request->quantity);
            if (!$validation['valid']) {
                return response()->json([
                    'success' => false,
                    'message' => $validation['message']
                ], 400);
            }

            $cart[$itemId]['quantity'] = $request->quantity;
        }

        $this->saveCart($cart);

        return response()->json([
            'success' => true,
            'message' => 'Cart updated successfully',
            'cart_count' => $this->getCartCount(),
        ]);
    }

    /**
     * Remove item from cart
     */
    public function remove($itemId)
    {
        $cart = $this->getCart();

        if (isset($cart[$itemId])) {
            unset($cart[$itemId]);
            $this->saveCart($cart);

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil dihapus dari keranjang',
                'cart_count' => $this->getCartCount(),
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Item tidak ditemukan di keranjang'
        ], 404);
    }

    /**
     * Clear all items from cart
     */
    public function clear()
    {
        session()->forget('cart');

        return response()->json([
            'success' => true,
            'message' => 'Keranjang berhasil dikosongkan',
        ]);
    }

    /**
     * Get cart count
     */
    public function getCount()
    {
        return response()->json([
            'count' => $this->getCartCount(),
        ]);
    }

    /**
     * Calculate total items in cart
     */
    private function getCartCount(): int
    {
        $cart = $this->getCart();
        return array_sum(array_column($cart, 'quantity'));
    }
}
