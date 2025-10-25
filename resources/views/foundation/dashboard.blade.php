@extends('layouts.app')

@section('title', 'Dashboard Yayasan - FSMS')

@section('content')
<div class="flex h-screen bg-gray-100">
    @include('foundation.partials.sidebar')

    <!-- Main Content -->
    <div class="flex-1 overflow-y-auto ml-64">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard Yayasan</h1>
        <p class="text-gray-600 mt-2">Kelola permintaan bahan makanan untuk program yayasan Anda</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6 card-hover">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-shopping-cart text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Request</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\FoodRequest::where('foundation_id', auth()->id())->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 card-hover">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Disetujui</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\FoodRequest::where('foundation_id', auth()->id())->where('status', 'approved')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 card-hover">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-clock text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Menunggu</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\FoodRequest::where('foundation_id', auth()->id())->where('status', 'pending')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 card-hover">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-600">
                    <i class="fas fa-times-circle text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Ditolak</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\FoodRequest::where('foundation_id', auth()->id())->where('status', 'rejected')->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Aksi Cepat</h2>
        <div class="flex flex-wrap gap-4">
            <a href="{{ route('foundation.requests.create') }}" class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition flex items-center">
                <i class="fas fa-plus mr-2"></i>
                Buat Request Baru
            </a>
            <a href="{{ route('foundation.requests.index') }}" class="bg-green-700 text-white px-6 py-3 rounded-lg hover:bg-green-800 transition flex items-center">
                <i class="fas fa-list mr-2"></i>
                Lihat Semua Request
            </a>
            <button class="bg-green-800 text-white px-6 py-3 rounded-lg hover:bg-green-900 transition flex items-center">
                <i class="fas fa-chart-bar mr-2"></i>
                Laporan Bulanan
            </button>
        </div>
    </div>

    <!-- Recent Requests -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-900">Request Terbaru</h2>
            <a href="{{ route('foundation.requests.index') }}" class="text-green-600 hover:text-green-800 text-sm font-medium">Lihat Semua</a>
        </div>

        <div class="text-center py-12">
            <i class="fas fa-inbox text-4xl text-gray-400 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada permintaan</h3>
            <p class="text-gray-500 mb-6">Mulai buat permintaan bahan makanan pertama Anda</p>
            <a href="{{ route('foundation.requests.create') }}" class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors font-semibold">
                <i class="fas fa-plus mr-2"></i>
                Buat Permintaan Baru
            </a>
        </div>
    </div>
        </div>
    </div>
</div>
@endsection
