<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});

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
