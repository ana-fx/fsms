<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Features;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\MaxPriceController;
use App\Http\Controllers\Customer\IngredientController;
use App\Http\Controllers\Supplier\IngredientController as SupplierIngredientController;
use App\Http\Controllers\Supplier\OrderController as SupplierOrderController;
use App\Http\Controllers\Supplier\AccountSettingsController as SupplierAccountSettingsController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\AccountSettingsController;
use App\Http\Controllers\Admin\AccountSettingsController as AdminAccountSettingsController;
use App\Models\User;

Route::get('/', function () {
    if (Auth::check()) {
        /** @var User $user */
        $user = Auth::user();
        if ($user->isCustomer()) {
            return redirect()->route('customer.ingredients');
        } elseif ($user->isSupplier()) {
            return redirect()->route('supplier.dashboard');
        } elseif ($user->isSuperAdmin()) {
            return redirect()->route('admin.dashboard');
        }
    }
    return view('home');
})->name('home');

Route::get('/csrf-token', function () {
    return response()->json(['csrf_token' => csrf_token()]);
});

// Auth Routes
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [LoginController::class, 'store']);

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/register', [RegisterController::class, 'store']);

Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');



// Role-based routes
Route::middleware(['auth', 'role:super_admin'])->group(function () {
    Route::get('/admin', function () {
        return view('admin.dashboard', ['title' => 'Super Admin Dashboard']);
    })->name('admin.dashboard');

    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users');
    Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::put('/admin/users/{id}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    Route::post('/admin/users/{id}/change-password', [UserController::class, 'changePassword'])->name('admin.users.change-password');
    Route::post('/admin/users/{id}/change-role', [UserController::class, 'changeRole'])->name('admin.users.change-role');

    // Max Price Management
    Route::get('/admin/max-price', [MaxPriceController::class, 'index'])->name('admin.max-price');
    Route::post('/admin/max-price', [MaxPriceController::class, 'store'])->name('admin.max-price.store');
    Route::post('/admin/max-price/{id}', [MaxPriceController::class, 'update'])->name('admin.max-price.update');

    // Admin Account Settings Routes
    Route::get('/admin/settings/account', [AdminAccountSettingsController::class, 'index'])
        ->name('admin.settings.account');
    Route::put('/admin/settings/account', [AdminAccountSettingsController::class, 'update'])
        ->name('admin.settings.account.update');
    Route::put('/admin/settings/account/password', [AdminAccountSettingsController::class, 'updatePassword'])
        ->name('admin.settings.account.password');

    // Custom Requests Management
    Route::get('/admin/custom-requests', [\App\Http\Controllers\Admin\CustomRequestController::class, 'index'])
        ->name('admin.custom-requests.index');
    Route::get('/admin/custom-requests/{customRequest}', [\App\Http\Controllers\Admin\CustomRequestController::class, 'show'])
        ->name('admin.custom-requests.show');
    Route::post('/admin/custom-requests/{customRequest}/approve', [\App\Http\Controllers\Admin\CustomRequestController::class, 'approve'])
        ->name('admin.custom-requests.approve');
    Route::post('/admin/custom-requests/{customRequest}/reject', [\App\Http\Controllers\Admin\CustomRequestController::class, 'reject'])
        ->name('admin.custom-requests.reject');
});

Route::middleware(['auth', 'role:supplier'])->group(function () {
    Route::get('/supplier', function () {
        return view('supplier.dashboard', ['title' => 'Supplier Dashboard']);
    })->name('supplier.dashboard');

    // Supplier Orders Routes
    Route::get('/supplier/orders', [SupplierOrderController::class, 'index'])->name('supplier.orders.index');
    Route::post('/supplier/orders/{order}/upload-delivery-proof', [SupplierOrderController::class, 'uploadDeliveryProof'])->name('supplier.orders.upload-delivery-proof');
    Route::get('/supplier/orders/{order}', [SupplierOrderController::class, 'show'])->name('supplier.orders.show');

    Route::get('/supplier/ingredients', function () {
        return view('supplier.ingredients', ['title' => 'Manage Ingredients']);
    })->name('supplier.ingredients');

    // Supplier Ingredients CRUD (minimal create/store)
    Route::get('/supplier/ingredients/create', [SupplierIngredientController::class, 'create'])->name('supplier.ingredients.create');
    Route::post('/supplier/ingredients', [SupplierIngredientController::class, 'store'])->name('supplier.ingredients.store');
    Route::get('/supplier/ingredients/{foodItem}/edit', [SupplierIngredientController::class, 'edit'])->name('supplier.ingredients.edit');
    Route::put('/supplier/ingredients/{foodItem}', [SupplierIngredientController::class, 'update'])->name('supplier.ingredients.update');
    Route::delete('/supplier/ingredients/{foodItem}', [SupplierIngredientController::class, 'destroy'])->name('supplier.ingredients.destroy');

    // Supplier Account Settings Routes
    Route::get('/supplier/settings/account', [SupplierAccountSettingsController::class, 'index'])
        ->name('supplier.settings.account');
    Route::put('/supplier/settings/account', [SupplierAccountSettingsController::class, 'update'])
        ->name('supplier.settings.account.update');
    Route::put('/supplier/settings/account/password', [SupplierAccountSettingsController::class, 'updatePassword'])
        ->name('supplier.settings.account.password');
});

Route::middleware(['auth', 'role:customer'])->group(function () {
    // Ingredients - Main page for customers
    Route::get('/customer/ingredients', [IngredientController::class, 'index'])->name('customer.ingredients');

    // Dashboard
    Route::get('/customer', [\App\Http\Controllers\Customer\FoodRequestController::class, 'dashboard'])
        ->name('customer.dashboard');

    // Cart Routes
    Route::get('/customer/cart', [CartController::class, 'index'])->name('customer.cart');
    Route::post('/customer/cart/add', [CartController::class, 'add'])->name('customer.cart.add');
    Route::put('/customer/cart/update/{itemId}', [CartController::class, 'update'])->name('customer.cart.update');
    Route::delete('/customer/cart/remove/{itemId}', [CartController::class, 'remove'])->name('customer.cart.remove');
    Route::delete('/customer/cart/clear', [CartController::class, 'clear'])->name('customer.cart.clear');
    Route::get('/customer/cart/count', [CartController::class, 'getCount'])->name('customer.cart.count');

    // Checkout Route (must be before resource routes to avoid route conflict)
    Route::get('customer/requests/checkout', [\App\Http\Controllers\Customer\FoodRequestController::class, 'checkout'])
        ->name('customer.requests.checkout');

    // Upload Payment Proof Route (must be before resource routes to avoid route conflict)
    Route::post('customer/requests/upload-payment-proof', [\App\Http\Controllers\Customer\FoodRequestController::class, 'uploadPaymentProof'])
        ->name('customer.requests.upload-payment-proof');

    // Upload Delivery Proof Route (must be before resource routes to avoid route conflict)
    Route::post('customer/requests/{requestId}/upload-delivery-proof', [\App\Http\Controllers\Customer\FoodRequestController::class, 'uploadDeliveryProof'])
        ->name('customer.requests.upload-delivery-proof');

    // Custom Request Routes (must be before resource routes)
    Route::get('customer/requests/custom/create', [\App\Http\Controllers\Customer\FoodRequestController::class, 'create'])
        ->name('customer.requests.custom.create');
    Route::post('customer/requests/custom/store', [\App\Http\Controllers\Customer\FoodRequestController::class, 'storeCustom'])
        ->name('customer.requests.custom.store');

    // Food Requests Routes
    Route::resource('customer/requests', \App\Http\Controllers\Customer\FoodRequestController::class)
        ->except(['create'])
        ->names([
            'index' => 'customer.requests.index',
            'store' => 'customer.requests.store',
            'show' => 'customer.requests.show',
            'edit' => 'customer.requests.edit',
            'update' => 'customer.requests.update',
            'destroy' => 'customer.requests.destroy',
        ]);

    // Account Settings Routes
    Route::get('/customer/settings/account', [AccountSettingsController::class, 'index'])
        ->name('customer.settings.account');
    Route::put('/customer/settings/account', [AccountSettingsController::class, 'update'])
        ->name('customer.settings.account.update');
    Route::put('/customer/settings/account/password', [AccountSettingsController::class, 'updatePassword'])
        ->name('customer.settings.account.password');

    // Delivery Addresses Routes
    Route::get('/customer/settings/delivery-addresses', [AccountSettingsController::class, 'deliveryAddresses'])
        ->name('customer.settings.delivery-addresses');
    Route::post('/customer/settings/delivery-addresses', [AccountSettingsController::class, 'storeAddress'])
        ->name('customer.settings.delivery-addresses.store');
    Route::put('/customer/settings/delivery-addresses/{id}', [AccountSettingsController::class, 'updateAddress'])
        ->name('customer.settings.delivery-addresses.update');
    Route::delete('/customer/settings/delivery-addresses/{id}', [AccountSettingsController::class, 'deleteAddress'])
        ->name('customer.settings.delivery-addresses.delete');

    // Reports
    Route::get('/customer/reports/purchases', [\App\Http\Controllers\Customer\PurchaseReportController::class, 'index'])
        ->name('customer.reports.purchases');
});
