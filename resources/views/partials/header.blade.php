<!-- Modern Header -->
@php
    $hideHeaderRoutes = [
        'customer.products',
        'customer.dashboard',
        'customer.programs',
        'customer.cart',
        'customer.requests.index',
        'customer.requests.create',
        'customer.requests.show',
        'customer.requests.edit'
    ];
    $currentRoute = request()->route() ? request()->route()->getName() : null;
    $shouldHideHeader = $currentRoute && (in_array($currentRoute, $hideHeaderRoutes) || str_starts_with($currentRoute, 'customer.requests.'));
@endphp
@if(!$shouldHideHeader)
<header class="bg-white shadow-lg border-b border-gray-200 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo Section -->
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex items-center space-x-3 hover:opacity-80 transition-opacity">
                    <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-green-600 to-green-700 rounded-lg shadow-md">
                        <i class="fas fa-utensils text-white text-lg"></i>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xl font-bold text-gray-900">FSMS</span>
                        <span class="text-xs text-gray-500 -mt-1">FoodSupply Management</span>
                    </div>
                </a>
            </div>

            <!-- Navigation Menu -->
            <nav class="hidden md:flex items-center space-x-8">
                <!-- Navigation menu removed -->
            </nav>

            <!-- User Section -->
            <div class="flex items-center space-x-4">
                @auth
                    <!-- User Profile Dropdown -->
                    <div class="relative group">
                        <button class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="flex items-center justify-center w-8 h-8 bg-green-100 text-green-700 rounded-full font-semibold text-sm">
                                {{ auth()->user()->initials() }}
                            </div>
                            <div class="hidden md:block text-left">
                                <div class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</div>
                                <div class="text-xs text-gray-500">{{ auth()->user()->roles->first()->display_name ?? 'No Role' }}</div>
                            </div>
                            <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                        </button>

                        <!-- Dropdown Menu -->
                        <div class="absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-lg border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                            <div class="p-4 border-b border-gray-100">
                                <div class="flex items-center space-x-3">
                                    <div class="flex items-center justify-center w-10 h-10 bg-green-100 text-green-700 rounded-full font-semibold">
                                        {{ auth()->user()->initials() }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">{{ auth()->user()->name }}</div>
                                        <div class="text-sm text-gray-500">{{ auth()->user()->email }}</div>
                                        <div class="text-xs text-green-600 font-medium">{{ auth()->user()->roles->first()->display_name ?? 'No Role' }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="p-2">
                                @if(auth()->user()->isSuperAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md transition-colors">
                                        <i class="fas fa-tachometer-alt mr-3 text-green-600"></i>
                                        Admin Dashboard
                                    </a>
                                    <a href="{{ route('admin.users') }}" class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md transition-colors">
                                        <i class="fas fa-user-cog mr-3 text-green-600"></i>
                                        Manajemen User
                                    </a>
                                @elseif(auth()->user()->isSupplier())
                                    <a href="{{ route('supplier.dashboard') }}" class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md transition-colors">
                                        <i class="fas fa-tachometer-alt mr-3 text-green-600"></i>
                                        Supplier Dashboard
                                    </a>
                                    <a href="{{ route('supplier.products') }}" class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md transition-colors">
                                        <i class="fas fa-box mr-3 text-green-600"></i>
                                        Kelola Produk
                                    </a>
                                @elseif(auth()->user()->isCustomer())
                                    <a href="{{ route('customer.products') }}" class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md transition-colors">
                                        <i class="fas fa-box mr-3 text-green-600"></i>
                                        Product
                                    </a>
                                    <a href="{{ route('customer.dashboard') }}" class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md transition-colors">
                                        <i class="fas fa-tachometer-alt mr-3 text-green-600"></i>
                                        Customer Dashboard
                                    </a>
                                    <a href="{{ route('customer.requests.index') }}" class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md transition-colors">
                                        <i class="fas fa-shopping-cart mr-3 text-green-600"></i>
                                        Permintaan Bahan Makanan
                                    </a>
                                    <a href="{{ route('customer.programs') }}" class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md transition-colors">
                                        <i class="fas fa-project-diagram mr-3 text-green-600"></i>
                                        Kelola Program
                                    </a>
                                @endif

                                <div class="border-t border-gray-100 my-2"></div>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded-md transition-colors">
                                        <i class="fas fa-sign-out-alt mr-3"></i>
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Login/Register Buttons -->
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-green-600 transition-colors font-medium">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors font-medium">
                            Daftar
                        </a>
                    </div>
                @endauth

                <!-- Mobile Menu Button for Sidebar -->
                <button id="mobile-menu-button" class="lg:hidden p-2 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="fas fa-bars text-gray-700 text-xl"></i>
                </button>
            </div>
        </div>
    </div>

</header>
@endif
