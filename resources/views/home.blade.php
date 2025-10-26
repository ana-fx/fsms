@extends('layouts.app')

@section('title', 'FSMS - FoodSupply Management System')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 to-emerald-50">
    <!-- Hero Section -->
    <div class="relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="text-center">
                <!-- Logo & Title -->
                <div class="flex items-center justify-center mb-8">
                    <div class="bg-green-600 p-4 rounded-2xl shadow-lg mr-6">
                        <i class="fas fa-utensils text-4xl text-white"></i>
                    </div>
                    <div class="text-left">
                        <h1 class="text-6xl font-bold text-gray-900 mb-2">FSMS</h1>
                        <p class="text-lg text-gray-600 font-medium">FoodSupply Management System</p>
                    </div>
                </div>

                <!-- Subtitle -->
                <h2 class="text-4xl font-bold text-gray-800 mb-6">
                    Kelola Pasokan Makanan dengan <span class="text-green-600">Efisien</span>
                </h2>

                <p class="text-xl text-gray-600 max-w-4xl mx-auto mb-12 leading-relaxed">
                    Sistem terintegrasi yang menghubungkan customer, admin, dan supplier untuk memastikan
                    distribusi bahan makanan yang transparan, efisien, dan tepat sasaran.
                </p>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    @auth
                        @if(auth()->user()->isSuperAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="bg-green-600 text-white px-8 py-4 rounded-xl hover:bg-green-700 transition-all duration-300 transform hover:scale-105 shadow-lg font-semibold text-lg">
                                <i class="fas fa-tachometer-alt mr-2"></i>
                                Admin Dashboard
                            </a>
                        @elseif(auth()->user()->isSupplier())
                            <a href="{{ route('supplier.dashboard') }}" class="bg-green-600 text-white px-8 py-4 rounded-xl hover:bg-green-700 transition-all duration-300 transform hover:scale-105 shadow-lg font-semibold text-lg">
                                <i class="fas fa-tachometer-alt mr-2"></i>
                                Supplier Dashboard
                            </a>
                        @elseif(auth()->user()->isCustomer())
                            <a href="{{ route('customer.dashboard') }}" class="bg-green-600 text-white px-8 py-4 rounded-xl hover:bg-green-700 transition-all duration-300 transform hover:scale-105 shadow-lg font-semibold text-lg">
                                <i class="fas fa-tachometer-alt mr-2"></i>
                                Customer Dashboard
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="bg-green-600 text-white px-8 py-4 rounded-xl hover:bg-green-700 transition-all duration-300 transform hover:scale-105 shadow-lg font-semibold text-lg">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Masuk ke Sistem
                        </a>
                        <a href="{{ route('register') }}" class="bg-white text-green-600 px-8 py-4 rounded-xl hover:bg-gray-50 transition-all duration-300 transform hover:scale-105 shadow-lg font-semibold text-lg border-2 border-green-600">
                            <i class="fas fa-user-plus mr-2"></i>
                            Daftar Sekarang
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Background Pattern -->
        <div class="absolute inset-0 -z-10 overflow-hidden">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-green-200 rounded-full opacity-20"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-emerald-200 rounded-full opacity-20"></div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h3 class="text-3xl font-bold text-gray-900 mb-4">Mengapa Memilih FSMS?</h3>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Solusi terbaik untuk mengelola pasokan makanan dengan teknologi modern
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Feature 1 -->
                <div class="text-center group">
                    <div class="bg-green-100 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-green-200 transition-colors duration-300">
                        <i class="fas fa-shield-alt text-3xl text-green-600"></i>
                    </div>
                    <h4 class="text-xl font-semibold text-gray-900 mb-3">Keamanan Tinggi</h4>
                    <p class="text-gray-600 leading-relaxed">
                        Sistem keamanan berlapis dengan role-based access control untuk melindungi data sensitif
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="text-center group">
                    <div class="bg-green-100 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-green-200 transition-colors duration-300">
                        <i class="fas fa-chart-line text-3xl text-green-600"></i>
                    </div>
                    <h4 class="text-xl font-semibold text-gray-900 mb-3">Transparansi Total</h4>
                    <p class="text-gray-600 leading-relaxed">
                        Pelacakan real-time untuk semua transaksi dan distribusi makanan
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="text-center group">
                    <div class="bg-green-100 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-green-200 transition-colors duration-300">
                        <i class="fas fa-mobile-alt text-3xl text-green-600"></i>
                    </div>
                    <h4 class="text-xl font-semibold text-gray-900 mb-3">Responsif & Modern</h4>
                    <p class="text-gray-600 leading-relaxed">
                        Interface modern yang dapat diakses dari berbagai perangkat
                    </p>
                </div>

                <!-- Feature 4 -->
                <div class="text-center group">
                    <div class="bg-green-100 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-green-200 transition-colors duration-300">
                        <i class="fas fa-heart text-3xl text-green-600"></i>
                    </div>
                    <h4 class="text-xl font-semibold text-gray-900 mb-3">Kepedulian Sosial</h4>
                    <p class="text-gray-600 leading-relaxed">
                        Membantu distribusi makanan untuk mereka yang membutuhkan
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- How It Works Section -->
    <div class="py-20 bg-gradient-to-br from-green-50 to-emerald-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h3 class="text-3xl font-bold text-gray-900 mb-4">Cara Kerja Sistem</h3>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Proses sederhana untuk mengelola pasokan makanan secara efisien
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Step 1 -->
                <div class="text-center">
                    <div class="bg-green-600 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6 text-white font-bold text-xl">
                        1
                    </div>
                    <h4 class="text-xl font-semibold text-gray-900 mb-3">Customer Request</h4>
                    <p class="text-gray-600 leading-relaxed">
                        Customer mengajukan permintaan bahan makanan melalui sistem
                    </p>
                </div>

                <!-- Step 2 -->
                <div class="text-center">
                    <div class="bg-green-600 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6 text-white font-bold text-xl">
                        2
                    </div>
                    <h4 class="text-xl font-semibold text-gray-900 mb-3">Admin Review</h4>
                    <p class="text-gray-600 leading-relaxed">
                        Admin meninjau dan menentukan harga maksimal serta supplier
                    </p>
                </div>

                <!-- Step 3 -->
                <div class="text-center">
                    <div class="bg-green-600 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6 text-white font-bold text-xl">
                        3
                    </div>
                    <h4 class="text-xl font-semibold text-gray-900 mb-3">Supplier Delivery</h4>
                    <p class="text-gray-600 leading-relaxed">
                        Supplier mengirimkan bahan makanan sesuai permintaan
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="py-20 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h3 class="text-3xl font-bold text-gray-900 mb-4">Siap Memulai?</h3>
            <p class="text-lg text-gray-600 mb-8">
                Bergabunglah dengan berbagai customer dan supplier yang telah mempercayai FSMS
            </p>

            @guest
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('register') }}" class="bg-green-600 text-white px-8 py-4 rounded-xl hover:bg-green-700 transition-all duration-300 transform hover:scale-105 shadow-lg font-semibold text-lg">
                        <i class="fas fa-rocket mr-2"></i>
                        Daftar Sekarang
                    </a>
                    <a href="{{ route('login') }}" class="bg-white text-green-600 px-8 py-4 rounded-xl hover:bg-gray-50 transition-all duration-300 transform hover:scale-105 shadow-lg font-semibold text-lg border-2 border-green-600">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Masuk ke Sistem
                    </a>
                </div>
            @endguest
        </div>
    </div>
</div>
@endsection
