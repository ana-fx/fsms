@extends('layouts.app')

@section('title', 'Dashboard Super Admin - FSMS')

@section('content')
<div class="flex h-screen bg-gray-100">
    @include('admin.partials.sidebar')

    <!-- Main Content -->
    <div class="flex-1 overflow-y-auto lg:ml-64">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Dashboard Super Admin</h1>
        <p class="text-sm lg:text-base text-gray-600 mt-2">Kelola seluruh sistem pasokan bahan makanan dan tentukan harga maksimal</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6 card-hover">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-clipboard-list text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Request</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\FoodRequest::count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 card-hover">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-truck text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Supplier Aktif</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\User::whereHas('roles', function($q) { $q->where('name', 'supplier'); })->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 card-hover">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-building text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Yayasan Terdaftar</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\User::whereHas('roles', function($q) { $q->where('name', 'foundation'); })->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 card-hover">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-dollar-sign text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Budget</p>
                    <p class="text-2xl font-semibold text-gray-900">Rp 0</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Requests -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-900">Request Menunggu Persetujuan</h2>
            <a href="#" class="text-green-600 hover:text-green-800 text-sm font-medium">Lihat Semua</a>
        </div>

        <div class="text-center py-12">
            <i class="fas fa-inbox text-4xl text-gray-400 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada request</h3>
            <p class="text-gray-500">Tidak ada request yang menunggu persetujuan saat ini</p>
        </div>
    </div>

    <!-- Supplier Management -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-900">Kelola Supplier</h2>
            <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">Tambah Supplier</button>
        </div>

        <div class="text-center py-12">
            <i class="fas fa-store text-4xl text-gray-400 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada supplier</h3>
            <p class="text-gray-500 mb-6">Mulai tambahkan supplier pertama Anda</p>
            <button class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                <i class="fas fa-plus mr-2"></i>
                Tambah Supplier
            </button>
        </div>
    </div>
        </div>
    </div>
</div>
@endsection
