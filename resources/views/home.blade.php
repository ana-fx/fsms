@extends('layouts.app')

@section('title', 'FSMS - FoodSupply Management System')

@section('content')
<div class="min-h-screen bg-white">
    <!-- Hero Section -->
    <div class="relative overflow-hidden bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 items-center py-20 lg:py-32">
                <!-- Left Column - Text Content -->
                <div class="text-center lg:text-left">
                    <h1 class="text-5xl lg:text-6xl font-bold text-gray-900 mb-4 leading-tight">
                        Food Supply<br>
                        <span class="text-green-600">Management</span>
                    </h1>
                    <p class="text-xl text-gray-600 mb-8 leading-relaxed max-w-xl mx-auto lg:mx-0">
                        An integrated system connecting customers, admins, and suppliers for transparent and efficient food distribution.
                    </p>
                    
                    <!-- CTA Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        @auth
                            @if(auth()->user()->isSuperAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center justify-center px-8 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium">
                                    <i class="fas fa-tachometer-alt mr-2"></i>
                                    Admin Dashboard
                                </a>
                            @elseif(auth()->user()->isSupplier())
                                <a href="{{ route('supplier.dashboard') }}" class="inline-flex items-center justify-center px-8 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium">
                                    <i class="fas fa-tachometer-alt mr-2"></i>
                                    Supplier Dashboard
                                </a>
                            @elseif(auth()->user()->isCustomer())
                                <a href="{{ route('customer.dashboard') }}" class="inline-flex items-center justify-center px-8 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium">
                                    <i class="fas fa-tachometer-alt mr-2"></i>
                                    Customer Dashboard
                                </a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-8 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium">
                                <i class="fas fa-sign-in-alt mr-2"></i>
                                Login
                            </a>
                            <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-3 bg-white text-green-600 rounded-lg hover:bg-gray-50 transition-colors font-medium border-2 border-green-600">
                                <i class="fas fa-user-plus mr-2"></i>
                                Register
                            </a>
                        @endauth
                    </div>
                </div>

                <!-- Right Column - Image -->
                <div class="relative">
                    <div class="relative rounded-2xl overflow-hidden shadow-2xl">
                        <img src="https://images.unsplash.com/photo-1556910103-1c02745aae4d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80" 
                             alt="Food Supply Management" 
                             class="w-full h-[400px] lg:h-[500px] object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- How It Works Section -->
    <div class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">How It Works</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Simple steps to manage food supply
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 lg:gap-12">
                <!-- Step 1 -->
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-green-600 text-white rounded-full mb-6 text-2xl font-bold">
                        1
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Customer Request</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Customers submit ingredient requests through the system
                    </p>
                </div>

                <!-- Step 2 -->
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-green-600 text-white rounded-full mb-6 text-2xl font-bold">
                        2
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Admin Review</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Admin reviews and assigns suppliers
                    </p>
                </div>

                <!-- Step 3 -->
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-green-600 text-white rounded-full mb-6 text-2xl font-bold">
                        3
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Supplier Delivery</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Suppliers deliver ingredients according to requests
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
