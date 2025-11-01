<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\UserDeliveryAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountSettingsController extends Controller
{
    /**
     * Display the account settings page.
     */
    public function index()
    {
        $user = Auth::user();
        return view('customer.settings.account', compact('user'));
    }

    /**
     * Display the delivery addresses management page.
     */
    public function deliveryAddresses()
    {
        $user = Auth::user();
        $addresses = $user->deliveryAddresses()->orderBy('is_default', 'desc')->orderBy('created_at', 'desc')->get();
        return view('customer.settings.delivery-addresses', compact('user', 'addresses'));
    }

    /**
     * Update the user's account settings.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update($validated);

        return redirect()->route('customer.settings.account')
            ->with('success', 'Account information updated successfully!');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        // Verify current password
        if (!\Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        $user->update([
            'password' => \Hash::make($validated['password']),
        ]);

        return redirect()->route('customer.settings.account')
            ->with('success', 'Password updated successfully!');
    }

    /**
     * Store a new delivery address.
     */
    public function storeAddress(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'label' => 'nullable|string|max:50',
            'recipient_name' => 'required|string|max:255',
            'recipient_phone' => 'required|string|max:20',
            'delivery_address' => 'required|string',
            'city' => 'required|string|max:100',
            'postal_code' => 'nullable|string|max:10',
            'is_default' => 'required|in:0,1',
        ]);

        // Convert string to boolean
        $validated['is_default'] = (bool) $validated['is_default'];

        // If this is set as default, unset other defaults
        if ($validated['is_default']) {
            $user->deliveryAddresses()->update(['is_default' => false]);
        }

        $validated['user_id'] = $user->id;
        UserDeliveryAddress::create($validated);

        return redirect()->route('customer.settings.delivery-addresses')
            ->with('status', ['type' => 'success', 'message' => 'Delivery address added successfully!']);
    }

    /**
     * Update an existing delivery address.
     */
    public function updateAddress(Request $request, $id)
    {
        $user = Auth::user();
        $address = $user->deliveryAddresses()->findOrFail($id);

        $validated = $request->validate([
            'label' => 'nullable|string|max:50',
            'recipient_name' => 'required|string|max:255',
            'recipient_phone' => 'required|string|max:20',
            'delivery_address' => 'required|string',
            'city' => 'required|string|max:100',
            'postal_code' => 'nullable|string|max:10',
            'is_default' => 'required|in:0,1',
        ]);

        // Convert string to boolean
        $validated['is_default'] = (bool) $validated['is_default'];
        
        $wasDefault = $address->is_default;

        // If this is set as default, unset other defaults
        if ($validated['is_default']) {
            $user->deliveryAddresses()->where('id', '!=', $id)->update(['is_default' => false]);
        } else if ($wasDefault) {
            // If unsetting default and this was the default, set another address as default if available
            $otherAddress = $user->deliveryAddresses()->where('id', '!=', $id)->first();
            if ($otherAddress) {
                $otherAddress->update(['is_default' => true]);
            }
        }

        $address->update($validated);

        return redirect()->route('customer.settings.delivery-addresses')
            ->with('status', ['type' => 'success', 'message' => 'Delivery address updated successfully!']);
    }

    /**
     * Delete a delivery address.
     */
    public function deleteAddress($id)
    {
        $user = Auth::user();
        $address = $user->deliveryAddresses()->findOrFail($id);
        $wasDefault = $address->is_default;
        $address->delete();

        // If deleted address was default and there are other addresses, set the first one as default
        if ($wasDefault) {
            $remainingAddress = $user->deliveryAddresses()->first();
            if ($remainingAddress) {
                $remainingAddress->update(['is_default' => true]);
            }
        }

        return redirect()->route('customer.settings.delivery-addresses')
            ->with('status', ['type' => 'success', 'message' => 'Delivery address deleted successfully!']);
    }
}
