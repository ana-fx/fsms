<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
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
        return view('supplier.settings.account', compact('user'));
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
            'phone' => 'required|string|max:20',
        ]);

        $user->update($validated);

        return redirect()->route('supplier.settings.account')
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

        return redirect()->route('supplier.settings.account')
            ->with('success', 'Password updated successfully!');
    }
}
