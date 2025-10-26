@extends('layouts.app')

@section('title', 'Dashboard Supplier - FSMS')

@section('content')
<div class="flex min-h-screen">
    @include('supplier.partials.sidebar')

    <!-- Main Content -->
    <div class="flex-1 flex flex-col lg:ml-64 min-h-screen">
        <div class="flex-1 bg-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard Supplier</h1>
        <p class="text-gray-600 mt-2">Kelola produk Anda dan terima pesanan dari admin</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6 card-hover">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-box text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Produk</p>
                    <p class="text-2xl font-semibold text-gray-900">0</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 card-hover">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-shopping-cart text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Order Aktif</p>
                    <p class="text-2xl font-semibold text-gray-900">0</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 card-hover">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Selesai</p>
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
                    <p class="text-sm font-medium text-gray-600">Pendapatan</p>
                    <p class="text-2xl font-semibold text-gray-900">Rp 0</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Aksi Cepat</h2>
        <div class="flex flex-wrap gap-4">
            <button class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition flex items-center">
                <i class="fas fa-plus mr-2"></i>
                Tambah Produk
            </button>
            <button class="bg-green-700 text-white px-6 py-3 rounded-lg hover:bg-green-800 transition flex items-center">
                <i class="fas fa-list mr-2"></i>
                Kelola Produk
            </button>
            <button class="bg-green-800 text-white px-6 py-3 rounded-lg hover:bg-green-900 transition flex items-center">
                <i class="fas fa-truck mr-2"></i>
                Kelola Pengiriman
            </button>
            <button class="bg-green-900 text-white px-6 py-3 rounded-lg hover:bg-green-950 transition flex items-center">
                <i class="fas fa-chart-bar mr-2"></i>
                Laporan Penjualan
            </button>
        </div>
    </div>

    <!-- Active Orders -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-900">Order Aktif</h2>
            <a href="#" class="text-green-600 hover:text-green-800 text-sm font-medium">Lihat Semua</a>
        </div>

        <div class="text-center py-12">
            <i class="fas fa-shopping-cart text-4xl text-gray-400 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada order</h3>
            <p class="text-gray-500">Tidak ada order aktif saat ini</p>
        </div>
    </div>

    <!-- Product Management -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-900">Kelola Produk</h2>
            <button class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 text-sm">Tambah Produk</button>
        </div>

        <div class="text-center py-12">
            <i class="fas fa-box text-4xl text-gray-400 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada produk</h3>
            <p class="text-gray-500 mb-6">Mulai tambahkan produk pertama Anda</p>
            <button class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors font-semibold">
                <i class="fas fa-plus mr-2"></i>
                Tambah Produk
            </button>
        </div>
    </div>
        </div>
        </div>
    </div>
</div>
@endsection
