<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectAfterLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Check if user just logged in
        if (auth()->check() && $request->is('login')) {
            $user = auth()->user();

            // Redirect based on role
            if ($user->isSuperAdmin()) {
                return redirect()->route('admin.dashboard');
            } elseif ($user->isSupplier()) {
                return redirect()->route('supplier.dashboard');
            } elseif ($user->isCustomer()) {
                return redirect()->route('customer.dashboard');
            }
        }

        return $response;
    }
}
