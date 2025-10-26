<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\MaxPriceController;

Route::get('/', function () {
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
});

Route::middleware(['auth', 'role:supplier'])->group(function () {
    Route::get('/supplier', function () {
        return view('supplier.dashboard', ['title' => 'Supplier Dashboard']);
    })->name('supplier.dashboard');

    Route::get('/supplier/products', function () {
        return view('supplier.products', ['title' => 'Manage Products']);
    })->name('supplier.products');
});

Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/customer', function () {
        return view('customer.dashboard', ['title' => 'Customer Dashboard']);
    })->name('customer.dashboard');

    Route::get('/customer/programs', function () {
        return view('customer.programs', ['title' => 'Manage Programs']);
    })->name('customer.programs');

    // Food Requests Routes
    Route::resource('customer/requests', \App\Http\Controllers\Customer\FoodRequestController::class)
        ->names([
            'index' => 'customer.requests.index',
            'create' => 'customer.requests.create',
            'store' => 'customer.requests.store',
            'show' => 'customer.requests.show',
            'edit' => 'customer.requests.edit',
            'update' => 'customer.requests.update',
            'destroy' => 'customer.requests.destroy',
        ]);
});
