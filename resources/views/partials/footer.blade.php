<!-- Modern Footer -->
@php
    $sidebarRoutes = [
        // Customer routes
        'customer.ingredients',
        'customer.dashboard',
        'customer.cart',
        'customer.requests.index',
        'customer.requests.checkout',
        'customer.requests.show',
        'customer.requests.edit',
        'customer.purchase-report',
        'customer.settings.account',
        'customer.settings.delivery-addresses',
        'customer.settings.account.password',
        // Admin routes
        'admin.dashboard',
        'admin.users',
        'admin.max-price',
        'admin.settings.account',
        'admin.settings.account.password',
        // Supplier routes
        'supplier.dashboard',
        'supplier.ingredients',
        'supplier.settings.account',
        'supplier.settings.account.password'
    ];
    $currentRoute = request()->route() ? request()->route()->getName() : null;
    $hasSidebar = $currentRoute && (
        in_array($currentRoute, $sidebarRoutes) || 
        str_starts_with($currentRoute, 'customer.requests.') ||
        str_starts_with($currentRoute, 'customer.settings.') ||
        str_starts_with($currentRoute, 'admin.') ||
        str_starts_with($currentRoute, 'supplier.')
    );
@endphp
<footer class="bg-gray-900 text-white w-full {{ $hasSidebar ? 'lg:pl-64' : '' }} transition-all duration-300 print:hidden">
    <!-- Main Footer Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Company Info -->
            <div class="space-y-4">
                <div class="flex items-center space-x-3">
                    <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-green-600 to-green-700 rounded-lg shadow-md">
                        <i class="fas fa-utensils text-white text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold">FSMS</h3>
                        <p class="text-sm text-gray-400">FoodSupply Management</p>
                    </div>
                </div>
                <p class="text-gray-300 text-sm leading-relaxed">
                    A food supply management system connecting customers, admins, and suppliers to ensure efficient and transparent distribution.
                </p>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-400 hover:text-green-400 transition-colors">
                        <i class="fab fa-facebook-f text-lg"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-green-400 transition-colors">
                        <i class="fab fa-twitter text-lg"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-green-400 transition-colors">
                        <i class="fab fa-linkedin-in text-lg"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-green-400 transition-colors">
                        <i class="fab fa-instagram text-lg"></i>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="space-y-4">
                <h4 class="text-lg font-semibold text-white">Quick Links</h4>
                <ul class="space-y-2">
                    @auth
                        @if(auth()->user()->isSuperAdmin())
                            <li>
                                <a href="{{ route('admin.dashboard') }}" class="text-gray-300 hover:text-green-400 transition-colors text-sm">
                                    <i class="fas fa-tachometer-alt mr-2"></i>Admin Panel
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.users') }}" class="text-gray-300 hover:text-green-400 transition-colors text-sm">
                                    <i class="fas fa-user-cog mr-2"></i>User Management
                                </a>
                            </li>
                        @elseif(auth()->user()->isSupplier())
                            <li>
                                <a href="{{ route('supplier.dashboard') }}" class="text-gray-300 hover:text-green-400 transition-colors text-sm">
                                    <i class="fas fa-tachometer-alt mr-2"></i>Supplier Panel
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('supplier.ingredients') }}" class="text-gray-300 hover:text-green-400 transition-colors text-sm">
                                    <i class="fas fa-box mr-2"></i>Manage Ingredients
                                </a>
                            </li>
                        @elseif(auth()->user()->isCustomer())
                            <li>
                                <a href="{{ route('customer.dashboard') }}" class="text-gray-300 hover:text-green-400 transition-colors text-sm">
                                    <i class="fas fa-tachometer-alt mr-2"></i>Customer Panel
                                </a>
                            </li>
                        @endif
                    @else
                        <li>
                            <a href="{{ route('login') }}" class="text-gray-300 hover:text-green-400 transition-colors text-sm">
                                <i class="fas fa-sign-in-alt mr-2"></i>Login
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('register') }}" class="text-gray-300 hover:text-green-400 transition-colors text-sm">
                                <i class="fas fa-user-plus mr-2"></i>Register
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>

            <!-- Features -->
            <div class="space-y-4">
                <h4 class="text-lg font-semibold text-white">System Features</h4>
                <ul class="space-y-2">
                    <li class="text-gray-300 text-sm">
                        <i class="fas fa-check-circle text-green-400 mr-2"></i>
                        Role & Access Management
                    </li>
                    <li class="text-gray-300 text-sm">
                        <i class="fas fa-check-circle text-green-400 mr-2"></i>
                        Request Tracking
                    </li>
                    <li class="text-gray-300 text-sm">
                        <i class="fas fa-check-circle text-green-400 mr-2"></i>
                        Real-time Reports
                    </li>
                    <li class="text-gray-300 text-sm">
                        <i class="fas fa-check-circle text-green-400 mr-2"></i>
                        Automated Notifications
                    </li>
                    <li class="text-gray-300 text-sm">
                        <i class="fas fa-check-circle text-green-400 mr-2"></i>
                        Interactive Dashboard
                    </li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div class="space-y-4">
                <h4 class="text-lg font-semibold text-white">Contact</h4>
                <div class="space-y-3">
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-map-marker-alt text-green-400 mt-1"></i>
                        <div>
                            <p class="text-gray-300 text-sm">Address</p>
                            <p class="text-gray-400 text-xs">Jl. Teknologi No. 123<br>Jakarta Selatan, Indonesia</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-phone text-green-400 mt-1"></i>
                        <div>
                            <p class="text-gray-300 text-sm">Phone</p>
                            <p class="text-gray-400 text-xs">+62 21 1234 5678</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-envelope text-green-400 mt-1"></i>
                        <div>
                            <p class="text-gray-300 text-sm">Email</p>
                            <p class="text-gray-400 text-xs">info@fsms.com</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Bar -->
    <div class="border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                <div class="text-gray-400 text-sm">
                    © {{ date('Y') }} FoodSupply Management System. All rights reserved.
                </div>
                <div class="flex items-center space-x-6 text-sm">
                    <a href="#" class="text-gray-400 hover:text-green-400 transition-colors">Privacy Policy</a>
                    <a href="#" class="text-gray-400 hover:text-green-400 transition-colors">Terms of Service</a>
                    <a href="#" class="text-gray-400 hover:text-green-400 transition-colors">Support</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Back to Top Button -->
    <button id="back-to-top" class="fixed bottom-6 right-6 bg-green-600 text-white p-2 rounded-full shadow-lg hover:bg-green-700 transition-all duration-300 opacity-0 invisible z-50 w-10 h-10 flex items-center justify-center">
        <i class="fas fa-arrow-up text-sm"></i>
    </button>
</footer>

<!-- JavaScript for Back to Top -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const backToTopButton = document.getElementById('back-to-top');

    if (backToTopButton) {
        // Show/hide button based on scroll position
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                backToTopButton.classList.remove('opacity-0', 'invisible');
                backToTopButton.classList.add('opacity-100', 'visible');
            } else {
                backToTopButton.classList.add('opacity-0', 'invisible');
                backToTopButton.classList.remove('opacity-100', 'visible');
            }
        });

        // Scroll to top when clicked
        backToTopButton.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
});
</script>
