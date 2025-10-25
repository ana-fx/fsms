@extends('layouts.app')

@section('title', 'Kelola Produk - FSMS')

@section('content')
<div class="flex h-screen bg-gray-100">
    @include('supplier.partials.sidebar')

    <!-- Main Content -->
    <div class="flex-1 overflow-y-auto ml-64">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Kelola Produk</h1>
            <p class="text-gray-600 mt-2">Manajemen produk dan inventori supplier</p>
        </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-2xl font-bold mb-4">Product Management</h2>

                <div class="mb-6">
                    <button class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Add New Product</button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Sample Product Cards -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="bg-gray-200 h-48 rounded mb-4 flex items-center justify-center">
                            <span class="text-gray-500">Product Image</span>
                        </div>
                        <h3 class="font-semibold text-lg mb-2">Sample Product 1</h3>
                        <p class="text-gray-600 mb-2">Description of the product goes here...</p>
                        <div class="flex justify-between items-center">
                            <span class="text-green-600 font-bold">$99.99</span>
                            <span class="text-sm text-gray-500">Stock: 50</span>
                        </div>
                        <div class="mt-3 space-x-2">
                            <button class="text-blue-600 hover:text-blue-800 text-sm">Edit</button>
                            <button class="text-red-600 hover:text-red-800 text-sm">Delete</button>
                        </div>
                    </div>

                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="bg-gray-200 h-48 rounded mb-4 flex items-center justify-center">
                            <span class="text-gray-500">Product Image</span>
                        </div>
                        <h3 class="font-semibold text-lg mb-2">Sample Product 2</h3>
                        <p class="text-gray-600 mb-2">Description of the product goes here...</p>
                        <div class="flex justify-between items-center">
                            <span class="text-green-600 font-bold">$149.99</span>
                            <span class="text-sm text-gray-500">Stock: 25</span>
                        </div>
                        <div class="mt-3 space-x-2">
                            <button class="text-blue-600 hover:text-blue-800 text-sm">Edit</button>
                            <button class="text-red-600 hover:text-red-800 text-sm">Delete</button>
                        </div>
                    </div>

                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="bg-gray-200 h-48 rounded mb-4 flex items-center justify-center">
                            <span class="text-gray-500">Product Image</span>
                        </div>
                        <h3 class="font-semibold text-lg mb-2">Sample Product 3</h3>
                        <p class="text-gray-600 mb-2">Description of the product goes here...</p>
                        <div class="flex justify-between items-center">
                            <span class="text-green-600 font-bold">$79.99</span>
                            <span class="text-sm text-gray-500">Stock: 100</span>
                        </div>
                        <div class="mt-3 space-x-2">
                            <button class="text-blue-600 hover:text-blue-800 text-sm">Edit</button>
                            <button class="text-red-600 hover:text-red-800 text-sm">Delete</button>
                        </div>
                    </div>
                </div>

                <div class="mt-8">
                    <h3 class="text-xl font-semibold mb-4">Product Statistics</h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="bg-green-50 p-4 rounded-lg text-center">
                            <div class="text-2xl font-bold text-green-600">15</div>
                            <div class="text-sm text-gray-600">Total Products</div>
                        </div>
                        <div class="bg-blue-50 p-4 rounded-lg text-center">
                            <div class="text-2xl font-bold text-blue-600">3</div>
                            <div class="text-sm text-gray-600">Low Stock</div>
                        </div>
                        <div class="bg-purple-50 p-4 rounded-lg text-center">
                            <div class="text-2xl font-bold text-purple-600">$2,450</div>
                            <div class="text-sm text-gray-600">Total Value</div>
                        </div>
                        <div class="bg-yellow-50 p-4 rounded-lg text-center">
                            <div class="text-2xl font-bold text-yellow-600">5</div>
                            <div class="text-sm text-gray-600">Pending Orders</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>
@endsection
