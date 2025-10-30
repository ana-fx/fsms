@extends('layouts.app')

@section('title', 'Register - FSMS')

@section('content')
<div class="bg-gray-50 min-h-screen w-full overflow-x-hidden">
    <div class="max-w-md w-full mx-auto py-10">
        <!-- Logo and Header -->
        <div class="text-center mb-8">
            <div class="flex items-center justify-center mb-4">
                <i class="fas fa-utensils text-4xl text-green-700 mr-3"></i>
                <span class="text-3xl font-bold text-gray-900">FSMS</span>
            </div>
            <h1 class="text-2xl font-semibold text-gray-800">FoodSupply Management System</h1>
            <p class="text-gray-600 mt-2">Create a new account</p>
        </div>

        <!-- Register Form -->
        <div class="bg-white rounded-lg shadow-lg p-8">
            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf

                <!-- Name Field -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user mr-2"></i>Full Name
                    </label>
                    <input
                        id="name"
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        autofocus
                        autocomplete="name"
                        class="w-full px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent {{ $errors->has('name') ? 'border-red-500' : 'border-gray-300' }} border"
                        placeholder="Enter your full name"
                    >
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

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
                            autocomplete="new-password"
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

                <!-- Confirm Password Field -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-lock mr-2"></i>Confirm Password
                    </label>
                    <div class="relative">
                        <input
                            id="password_confirmation"
                            type="password"
                            name="password_confirmation"
                            required
                            autocomplete="new-password"
                            class="w-full px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent border border-gray-300"
                            placeholder="Confirm your password"
                        >
                        <button type="button" onclick="togglePasswordConfirmation()" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                            <i id="password-confirmation-icon" class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- Role Selection -->
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user-tag mr-2"></i>Select Role
                    </label>
                    <select
                        id="role"
                        name="role"
                        class="w-full px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent {{ $errors->has('role') ? 'border-red-500' : 'border-gray-300' }} border"
                    >
                        <option value="">Select your role</option>
                        <option value="supplier" {{ old('role') == 'supplier' ? 'selected' : '' }}>Supplier</option>
                        <option value="customer" {{ old('role') == 'customer' ? 'selected' : '' }}>Customer</option>
                    </select>
                    @error('role')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Terms and Conditions -->
                <div class="flex items-center">
                    <input
                        id="terms"
                        type="checkbox"
                        name="terms"
                        required
                        class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded"
                    >
                    <label for="terms" class="ml-2 block text-sm text-gray-700">
                        I agree to the
                        <a href="#" class="text-green-600 hover:text-green-800">terms and conditions</a>
                    </label>
                </div>

                <!-- Register Button -->
                <button
                    type="submit"
                    class="w-full bg-green-600 text-white py-3 px-4 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition duration-200 font-semibold"
                >
                    <i class="fas fa-user-plus mr-2"></i>Register
                </button>
            </form>

            <!-- Login Link -->
            <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                    Already have an account?
                        <a href="{{ route('login') }}" class="text-green-600 hover:text-green-800 font-medium">
                        Login here
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>

@section('scripts')
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

        function togglePasswordConfirmation() {
            const passwordInput = document.getElementById('password_confirmation');
            const passwordIcon = document.getElementById('password-confirmation-icon');

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
@endsection
@endsection
