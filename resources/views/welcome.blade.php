@extends('layouts.app')

@section('title', 'FoodSupply Management System')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-purple-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Hero Section -->
        <div class="text-center mb-16">
            <div class="flex items-center justify-center mb-6">
                <i class="fas fa-utensils text-6xl text-blue-600 mr-4"></i>
                <h1 class="text-5xl font-bold text-gray-900">FSMS</h1>
            </div>
            <h2 class="text-3xl font-semibold text-gray-800 mb-4">FoodSupply Management System</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Sistem manajemen pasokan bahan makanan yang menghubungkan yayasan, admin, dan supplier
                untuk memastikan distribusi makanan yang efisien dan transparan.
            </p>
        </div>

        <!-- Login Section -->
        <div class="text-center mb-16">
            <a href="{{ route('login') }}" class="bg-blue-600 text-white px-8 py-4 rounded-lg hover:bg-blue-700 transition inline-block text-lg font-semibold">
                <i class="fas fa-sign-in-alt mr-2"></i>
                Masuk ke Sistem
            </a>
        </div>

        <!-- Features -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <i class="fas fa-shield-alt text-3xl text-blue-600 mb-4"></i>
                <h4 class="font-semibold text-gray-900 mb-2">Keamanan</h4>
                <p class="text-gray-600 text-sm">Sistem keamanan berlapis dengan role-based access</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <i class="fas fa-chart-line text-3xl text-green-600 mb-4"></i>
                <h4 class="font-semibold text-gray-900 mb-2">Transparansi</h4>
                <p class="text-gray-600 text-sm">Pelacakan real-time untuk semua transaksi</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <i class="fas fa-mobile-alt text-3xl text-purple-600 mb-4"></i>
                <h4 class="font-semibold text-gray-900 mb-2">Responsif</h4>
                <p class="text-gray-600 text-sm">Akses mudah dari berbagai perangkat</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <i class="fas fa-heart text-3xl text-red-600 mb-4"></i>
                <h4 class="font-semibold text-gray-900 mb-2">Kepedulian</h4>
                <p class="text-gray-600 text-sm">Membantu distribusi makanan untuk yang membutuhkan</p>
            </div>
        </div>
    </div>
</div>
@endsection
