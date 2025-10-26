<!-- Modern Head Section -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>@yield('title', 'FSMS - FoodSupply Management System')</title>
<meta name="description" content="@yield('description', 'Sistem manajemen pasokan bahan makanan yang menghubungkan yayasan, admin, dan supplier untuk distribusi makanan yang efisien dan transparan.')">

<!-- Favicon -->
<link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

<!-- Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

<!-- Tailwind CSS -->
<script src="https://cdn.tailwindcss.com"></script>

    <!-- Custom Styles -->
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #065f46 0%, #047857 100%);
        }

        .card-hover {
            transition: transform 0.2s ease-in-out;
        }

        .card-hover:hover {
            transform: translateY(-2px);
        }
    </style>

    <!-- CSRF Token Setup -->
    <script>
        window.Laravel = {
            csrfToken: '{{ csrf_token() }}'
        };

        // Setup CSRF token for AJAX requests
        document.addEventListener('DOMContentLoaded', function() {
            const token = document.querySelector('meta[name="csrf-token"]');
            if (token) {
                // Only setup axios if it's available
                if (typeof window.axios !== 'undefined' && window.axios.defaults) {
                    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
                }
            }

            // Refresh CSRF token every 30 minutes
            setInterval(function() {
                fetch('/csrf-token')
                    .then(response => response.json())
                    .then(data => {
                        document.querySelector('meta[name="csrf-token"]').setAttribute('content', data.csrf_token);
                        window.Laravel.csrfToken = data.csrf_token;
                    })
                    .catch(error => console.log('CSRF token refresh failed:', error));
            }, 30 * 60 * 1000); // 30 minutes
        });
    </script>

<!-- Modern Header -->
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
                                @elseif(auth()->user()->isFoundation())
                                    <a href="{{ route('foundation.dashboard') }}" class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md transition-colors">
                                        <i class="fas fa-tachometer-alt mr-3 text-green-600"></i>
                                        Foundation Dashboard
                                    </a>
                                    <a href="{{ route('foundation.requests.index') }}" class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md transition-colors">
                                        <i class="fas fa-shopping-cart mr-3 text-green-600"></i>
                                        Permintaan Bahan Makanan
                                    </a>
                                    <a href="{{ route('foundation.programs') }}" class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md transition-colors">
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
