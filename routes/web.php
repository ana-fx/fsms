<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

Route::get('/', function () {
    return view('home');
})->name('home');

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

    Route::get('/admin/users', function () {
        return view('admin.users', ['title' => 'Manage Users']);
    })->name('admin.users');

    Route::get('/admin/accounts', function () {
        return view('admin.accounts', ['title' => 'Manajemen Akun']);
    })->name('admin.accounts');
});

Route::middleware(['auth', 'role:supplier'])->group(function () {
    Route::get('/supplier', function () {
        return view('supplier.dashboard', ['title' => 'Supplier Dashboard']);
    })->name('supplier.dashboard');

    Route::get('/supplier/products', function () {
        return view('supplier.products', ['title' => 'Manage Products']);
    })->name('supplier.products');
});

Route::middleware(['auth', 'role:foundation'])->group(function () {
    Route::get('/foundation', function () {
        return view('foundation.dashboard', ['title' => 'Foundation Dashboard']);
    })->name('foundation.dashboard');

    Route::get('/foundation/programs', function () {
        return view('foundation.programs', ['title' => 'Manage Programs']);
    })->name('foundation.programs');
});
