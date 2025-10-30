@extends('layouts.app')

@section('title', 'Login - FSMS')

@section('content')
<div class="bg-gray-50 min-h-screen w-full overflow-x-hidden">
    <div class="max-w-6xl w-full mx-auto py-10 px-4">
        <div class="bg-white rounded-2xl shadow-xl grid grid-cols-1 lg:grid-cols-2 overflow-hidden">
            <!-- Left Visual Panel (Desktop only) -->
            <div class="hidden lg:block relative">
                <div class="absolute inset-0" style="background: linear-gradient(135deg, #0f172a 0%, #1f2937 100%);"></div>
                <img src="https://images.unsplash.com/photo-1540189549336-e6e99c3679fe?q=80&w=1600&auto=format&fit=crop" alt="Fresh ingredients" class="absolute inset-0 w-full h-full object-cover opacity-60">
                <div class="relative h-full w-full p-8 flex flex-col justify-between text-white">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-utensils text-2xl"></i>
                            <span class="text-xl font-bold">FSMS</span>
                        </div>
                        <a href="{{ route('home') }}" class="px-3 py-1 bg-white/10 hover:bg-white/20 rounded-md text-sm">Back to website</a>
                    </div>
                    <div class="mt-auto">
                        <h2 class="text-2xl font-semibold">Sourcing Ingredients,</h2>
                        <p class="text-white/80">Nourishing Communities</p>
                    </div>
                </div>
            </div>

            <!-- Right Form Panel -->
            <div class="p-8 lg:p-10">
                <!-- Logo and Header -->
                <div class="mb-8">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-utensils text-2xl text-green-700 mr-2"></i>
                        <span class="text-2xl font-bold text-gray-900">FSMS</span>
                    </div>
                    <h1 class="text-xl font-semibold text-gray-800">FoodSupply Management System</h1>
                    <p class="text-gray-600 mt-1">Sign in to your account</p>
                </div>

                <!-- Login Form -->
                <div class="bg-white">
            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-envelope mr-2"></i>Email Address
                    </label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="email"
                        class="w-full px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent {{ $errors->has('email') ? 'border-red-500' : 'border-gray-300' }} border"
                        placeholder="Enter your email"
                    >
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-lock mr-2"></i>Password
                    </label>
                    <div class="relative">
                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            class="w-full px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent {{ $errors->has('password') ? 'border-red-500' : 'border-gray-300' }} border"
                            placeholder="Enter your password"
                        >
                        <button type="button" onclick="togglePassword()" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                            <i id="password-icon" class="fas fa-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input
                            id="remember"
                            type="checkbox"
                            name="remember"
                            class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded"
                        >
                        <label for="remember" class="ml-2 block text-sm text-gray-700">
                            Remember me
                        </label>
                    </div>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm text-green-600 hover:text-green-800">
                            Forgot password?
                        </a>
                    @endif
                </div>

                <!-- Login Button -->
                <button
                    type="submit"
                    class="w-full bg-green-600 text-white py-3 px-4 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition duration-200 font-semibold"
                >
                    <i class="fas fa-sign-in-alt mr-2"></i>Login
                </button>
            </form>

            <!-- Register Link -->
            @if (Route::has('register'))
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        Don't have an account?
                        <a href="{{ route('register') }}" class="text-green-600 hover:text-green-800 font-medium">
                            Register now
                        </a>
                    </p>
                </div>
            @endif

        </div>

        <!-- Footer -->
        <div class="text-center mt-8 text-sm text-gray-500">
            <p>&copy; 2024 FSMS. All rights reserved.</p>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const passwordIcon = document.getElementById('password-icon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.classList.remove('fa-eye');
                passwordIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                passwordIcon.classList.remove('fa-eye-slash');
                passwordIcon.classList.add('fa-eye');
            }
        }
    </script>
    </div>
</div>
@endsection
