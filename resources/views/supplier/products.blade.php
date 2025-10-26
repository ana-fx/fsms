@extends('layouts.app')

@section('title', 'Kelola Produk - FSMS')

@section('content')
@php
    $products = \App\Models\FoodItem::where('supplier_id', auth()->id())->with('foodCategory')->get();
    $totalProducts = $products->count();
    $lowStockProducts = $products->where('stock', '<=', $products->first()->min_stock ?? 0)->count();
    $totalValue = $products->sum(fn($p) => $p->price * $p->stock);
@endphp

<div class="flex min-h-screen">
    @include('supplier.partials.sidebar')

    <!-- Main Content -->
    <div class="flex-1 flex flex-col lg:ml-64 min-h-screen">
        <div class="flex-1 bg-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Kelola Produk</h1>
            <p class="text-gray-600 mt-2">Manajemen produk dan inventori supplier</p>
        </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold">Product Management</h2>
                    <button class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                        <i class="fas fa-plus mr-2"></i>Tambah Produk
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($products as $product)
                    <!-- Product Card -->
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-center h-32 rounded mb-4" style="background: linear-gradient(135deg, {{ $product->foodCategory->color }}20 0%, {{ $product->foodCategory->color }}40 100%);">
                            <i class="{{ $product->foodCategory->icon }} text-4xl" style="color: {{ $product->foodCategory->color }}"></i>
                        </div>
                        <h3 class="font-semibold text-lg mb-2 text-gray-900">{{ $product->name }}</h3>
                        <p class="text-gray-600 mb-2 text-sm">{{ Str::limit($product->description, 60) }}</p>
                        <div class="mb-2">
                            <span class="inline-block px-2 py-1 text-xs font-semibold rounded" style="background: {{ $product->foodCategory->color }}20; color: {{ $product->foodCategory->color }}">
                                {{ $product->foodCategory->name }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-green-600 font-bold text-lg">Rp {{ number_format($product->price, 0, ',', '.') }}<span class="text-xs text-gray-500">/{{ $product->unit }}</span></span>
                            <span class="text-sm {{ $product->stock <= $product->min_stock ? 'text-red-600' : 'text-gray-500' }}">
                                Stock: {{ $product->stock }} {{ $product->unit }}
                            </span>
                        </div>
                        <div class="flex space-x-2">
                            <button class="flex-1 text-blue-600 hover:text-blue-800 text-sm font-medium py-2 border border-blue-600 rounded hover:bg-blue-50 transition">
                                <i class="fas fa-edit mr-1"></i>Edit
                            </button>
                            <button class="flex-1 text-red-600 hover:text-red-800 text-sm font-medium py-2 border border-red-600 rounded hover:bg-red-50 transition">
                                <i class="fas fa-trash mr-1"></i>Hapus
                            </button>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full">
                        <div class="text-center py-12">
                            <i class="fas fa-box text-4xl text-gray-400 mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada produk</h3>
                            <p class="text-gray-500 mb-6">Mulai tambahkan produk pertama Anda</p>
                            <button class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors font-semibold">
                                <i class="fas fa-plus mr-2"></i>Tambah Produk
                            </button>
                        </div>
                    </div>
                    @endforelse
                </div>

                @if($totalProducts > 0)
                <div class="mt-8">
                    <h3 class="text-xl font-semibold mb-4">Product Statistics</h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="bg-green-50 p-4 rounded-lg text-center border border-green-200">
                            <div class="text-2xl font-bold text-green-600">{{ $totalProducts }}</div>
                            <div class="text-sm text-gray-600">Total Produk</div>
                        </div>
                        <div class="bg-blue-50 p-4 rounded-lg text-center border border-blue-200">
                            <div class="text-2xl font-bold text-blue-600">{{ $lowStockProducts }}</div>
                            <div class="text-sm text-gray-600">Stok Rendah</div>
                        </div>
                        <div class="bg-purple-50 p-4 rounded-lg text-center border border-purple-200">
                            <div class="text-2xl font-bold text-purple-600">Rp {{ number_format($totalValue, 0, ',', '.') }}</div>
                            <div class="text-sm text-gray-600">Total Nilai</div>
                        </div>
                        <div class="bg-yellow-50 p-4 rounded-lg text-center border border-yellow-200">
                            <div class="text-2xl font-bold text-yellow-600">{{ $products->sum('stock') }}</div>
                            <div class="text-sm text-gray-600">Total Stock</div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
        </div>
        </div>
    </div>
</div>
@endsection
