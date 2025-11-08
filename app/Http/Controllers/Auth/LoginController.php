<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Check if user exists and is active BEFORE attempting login
        $user = User::where('email', $request->email)->first();
        
        if ($user && $user->is_active === false) {
            // User exists but account is disabled
            return redirect()->back()
                ->withInput($request->only('email'))
                ->with('account_disabled', true)
                ->with('disabled_message', 'Your account has been disabled. Please contact administrator for assistance.')
                ->withErrors([
                    'email' => 'Your account has been disabled. Please contact administrator.',
                ]);
        }

        if (! Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        $request->session()->regenerate();

        /** @var User $user */
        $user = Auth::user();

        // Double check if user account is active (in case status changed during login)
        if ($user->is_active === false) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            // Redirect back with error message and disabled account notification
            return redirect()->back()
                ->withInput($request->only('email'))
                ->with('account_disabled', true)
                ->with('disabled_message', 'Your account has been disabled. Please contact administrator for assistance.')
                ->withErrors([
                    'email' => 'Your account has been disabled. Please contact administrator.',
                ]);
        }

        // Redirect based on role
        if ($user->isSuperAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isSupplier()) {
            return redirect()->route('supplier.dashboard');
        } elseif ($user->isCustomer()) {
            return redirect()->route('customer.dashboard');
        }

        return redirect()->route('home');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
