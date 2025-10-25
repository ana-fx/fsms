<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'FoodSupply Management System')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .card-hover {
            transition: transform 0.2s ease-in-out;
        }
        .card-hover:hover {
            transform: translateY(-2px);
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="gradient-bg text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <i class="fas fa-utensils text-2xl mr-2"></i>
                        <span class="text-xl font-bold">FSMS</span>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    @auth
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-user-circle text-lg"></i>
                            <span>{{ auth()->user()->name }}</span>
                            <span class="bg-white bg-opacity-20 px-2 py-1 rounded-full text-sm">
                                {{ auth()->user()->roles->first()->display_name ?? 'No Role' }}
                            </span>
                        </div>
                        <a href="{{ route('dashboard') }}" class="hover:text-gray-200 transition">
                            <i class="fas fa-home mr-1"></i>Dashboard
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="hover:text-gray-200 transition">
                                <i class="fas fa-sign-out-alt mr-1"></i>Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="hover:text-gray-200 transition">Login</a>
                        <a href="{{ route('register') }}" class="hover:text-gray-200 transition">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="min-h-screen">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="flex items-center justify-center mb-4">
                    <i class="fas fa-utensils text-2xl mr-2"></i>
                    <span class="text-xl font-bold">FoodSupply Management System</span>
                </div>
                <p class="text-gray-400">Sistem manajemen pasokan bahan makanan untuk yayasan</p>
                <div class="mt-4 text-sm text-gray-500">
                    © 2024 FSMS. All rights reserved.
                </div>
            </div>
        </div>
    </footer>

    @yield('scripts')
</body>
</html>
