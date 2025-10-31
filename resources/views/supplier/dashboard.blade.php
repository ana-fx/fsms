@extends('layouts.app')

@section('title', 'Dashboard Supplier - FSMS')

@section('content')
@php
    $ingredients = \App\Models\FoodItem::where('supplier_id', auth()->id())->with('foodCategory')->latest()->get();
    $totalIngredients = $ingredients->count();
    $activeIngredients = $ingredients->where('is_active', true)->count();
@endphp
<div class="flex bg-gray-100 min-h-screen w-full overflow-x-hidden">
    @include('supplier.partials.sidebar')

    <!-- Mobile Menu Button -->
    <button id="openSidebar" class="lg:hidden fixed top-4 left-4 z-50 p-2 bg-white rounded-lg shadow-lg hover:bg-gray-100 transition-colors">
        <i class="fas fa-bars text-gray-700 text-xl"></i>
    </button>

    <!-- Main Content -->
    <div class="w-full lg:ml-64 transition-all duration-300">
        <div class="flex-1 bg-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard Supplier</h1>
        <p class="text-gray-600 mt-2">Manage your ingredients and receive orders from admin</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6 card-hover">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-box text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Ingredients</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $totalIngredients }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 card-hover">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Active Ingredients</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $activeIngredients }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 card-hover">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-shopping-cart text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Active Orders</p>
                    <p class="text-2xl font-semibold text-gray-900">0</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 card-hover">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-dollar-sign text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Revenue</p>
                    <p class="text-2xl font-semibold text-gray-900">Rp 0</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Orders -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-900">Active Orders</h2>
            <a href="#" class="text-green-600 hover:text-green-800 text-sm font-medium">View All</a>
        </div>

        <div class="text-center py-12">
            <i class="fas fa-shopping-cart text-4xl text-gray-400 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No orders yet</h3>
            <p class="text-gray-500">No active orders at the moment</p>
        </div>
    </div>

    <!-- Ingredients Management -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-900">Manage Ingredients</h2>
            <div class="flex space-x-2">
                <a href="{{ route('supplier.ingredients.create') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition text-sm font-medium inline-flex items-center">
                    <i class="fas fa-plus mr-2"></i>Add Ingredient
                </a>
                <a href="{{ route('supplier.ingredients') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition text-sm font-medium inline-flex items-center">
                    <i class="fas fa-list mr-2"></i>View All
                </a>
            </div>
        </div>

        @if($ingredients->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price/Unit</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Min Purchase</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($ingredients->take(5) as $ingredient)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="font-medium text-gray-900">{{ $ingredient->name }}</div>
                                <div class="text-sm text-gray-500">{{ Str::limit($ingredient->description, 40) }}</div>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <span class="inline-block px-2 py-1 text-xs font-semibold rounded" style="background: {{ $ingredient->foodCategory->color }}20; color: {{ $ingredient->foodCategory->color }}">{{ $ingredient->foodCategory->name }}</span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900">Rp {{ number_format($ingredient->price, 0, ',', '.') }}<span class="text-xs text-gray-500">/{{ $ingredient->unit }}</span></td>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $ingredient->min_purchase }} {{ $ingredient->unit }}</td>
                            <td class="px-4 py-3 text-sm">
                                @if($ingredient->is_active)
                                    <span class="px-2 py-1 text-xs font-semibold rounded bg-green-100 text-green-700">Active</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded bg-gray-100 text-gray-600">Inactive</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('supplier.ingredients.edit', $ingredient) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Edit</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @if($ingredients->count() > 5)
                    <div class="mt-4 text-center">
                        <a href="{{ route('supplier.ingredients') }}" class="text-green-600 hover:text-green-800 text-sm font-medium">
                            View all {{ $ingredients->count() }} ingredients <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                @endif
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-box text-4xl text-gray-400 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No ingredients yet</h3>
                <p class="text-gray-500 mb-6">Start by adding your first ingredient</p>
                <a href="{{ route('supplier.ingredients.create') }}" class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors font-semibold inline-block">
                    <i class="fas fa-plus mr-2"></i>Add Ingredient
                </a>
            </div>
        @endif
    </div>
            </div>
        </div>
    </div>
</div>
@endsection
