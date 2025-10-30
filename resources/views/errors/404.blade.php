<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found | FSMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #065f46 0%, #047857 100%);
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <!-- Logo and Header -->
        <div class="mb-8">
            <div class="flex items-center justify-center mb-6">
                <i class="fas fa-utensils text-6xl text-green-700 mr-4"></i>
                <span class="text-4xl font-bold text-gray-900">FSMS</span>
            </div>
            <h1 class="text-2xl font-semibold text-gray-800 mb-2">FoodSupply Management System</h1>
        </div>

        <!-- 404 Content -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <!-- 404 Icon -->
            <div class="mb-6">
                <i class="fas fa-exclamation-triangle text-8xl text-green-600"></i>
            </div>

            <!-- Error Message -->
            <div class="mb-8">
                <h2 class="text-6xl font-bold text-gray-900 mb-4">404</h2>
                <h3 class="text-2xl font-semibold text-gray-800 mb-4">Page Not Found</h3>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Sorry, the page you are looking for could not be found. It may have been moved, deleted, or the URL is incorrect.
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center mb-8">
                <a href="{{ route('home') }}" class="bg-green-600 text-white px-8 py-3 rounded-lg hover:bg-green-700 transition font-semibold">
                    <i class="fas fa-home mr-2"></i>
                    Back to Home
                </a>
                
                @auth
                    @if(auth()->user()->isSuperAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="bg-green-700 text-white px-8 py-3 rounded-lg hover:bg-green-800 transition font-semibold">
                            <i class="fas fa-tachometer-alt mr-2"></i>
                            Admin Dashboard
                        </a>
                    @elseif(auth()->user()->isSupplier())
                        <a href="{{ route('supplier.dashboard') }}" class="bg-green-700 text-white px-8 py-3 rounded-lg hover:bg-green-800 transition font-semibold">
                            <i class="fas fa-tachometer-alt mr-2"></i>
                            Supplier Dashboard
                        </a>
                    @elseif(auth()->user()->isCustomer())
                        <a href="{{ route('customer.dashboard') }}" class="bg-green-700 text-white px-8 py-3 rounded-lg hover:bg-green-800 transition font-semibold">
                            <i class="fas fa-tachometer-alt mr-2"></i>
                            Customer Dashboard
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="bg-green-700 text-white px-8 py-3 rounded-lg hover:bg-green-800 transition font-semibold">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Login to System
                    </a>
                @endauth
            </div>

            <!-- Help Section -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-4">Need Help?</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600">
                    <div class="flex items-center justify-center">
                        <i class="fas fa-search text-green-600 mr-2"></i>
                        <span>Please check the URL you entered</span>
                    </div>
                    <div class="flex items-center justify-center">
                        <i class="fas fa-arrow-left text-green-600 mr-2"></i>
                        <span>Use your browser's back button</span>
                    </div>
                    <div class="flex items-center justify-center">
                        <i class="fas fa-home text-green-600 mr-2"></i>
                        <span>Return to the homepage</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center text-gray-500 text-sm">
            <p>&copy; 2024 FoodSupply Management System. All rights reserved.</p>
        </div>
    </div>

    <!-- JavaScript untuk animasi -->
    <script>
        // Animasi fade in saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            document.body.style.opacity = '0';
            document.body.style.transition = 'opacity 0.5s ease-in-out';
            
            setTimeout(function() {
                document.body.style.opacity = '1';
            }, 100);
        });

        // Animasi hover untuk tombol
        document.querySelectorAll('a').forEach(function(link) {
            link.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
                this.style.transition = 'transform 0.2s ease-in-out';
            });
            
            link.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    </script>
</body>
</html>
