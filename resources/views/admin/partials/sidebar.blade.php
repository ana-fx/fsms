<!-- Admin Sidebar -->
<div id="sidebar" class="fixed top-0 left-0 bottom-0 z-[60] w-64 bg-white shadow-lg transform transition-transform duration-300 ease-in-out -translate-x-full lg:translate-x-0 flex flex-col" style="height: 100vh; overflow-y: auto; scrollbar-width: thin;">
    <!-- Logo Section -->
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center space-x-3 hover:opacity-80 transition-opacity">
                <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-green-600 to-green-700 rounded-lg shadow-md">
                    <i class="fas fa-utensils text-white text-lg"></i>
                </div>
                <div class="flex flex-col">
                    <span class="text-xl font-bold text-gray-900">FSMS</span>
                    <span class="text-xs text-gray-500 -mt-1">Admin Panel</span>
                </div>
            </a>
            <!-- Close button for mobile -->
            <button id="closeSidebar" class="lg:hidden text-gray-600 hover:text-gray-900">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
    </div>

    <!-- User Info -->
    <div class="p-4 border-b border-gray-200">
        <div class="flex items-center space-x-3">
            <div class="flex items-center justify-center w-10 h-10 bg-green-100 text-green-700 rounded-full font-semibold">
                {{ auth()->user()->initials() }}
            </div>
            <div>
                <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                <p class="text-xs text-green-600 font-medium">Super Admin</p>
            </div>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="mt-6 flex-1 overflow-y-auto">
        <div class="px-4 space-y-6">
            <!-- Main Section -->
            <div class="space-y-1">
                <div class="px-4 py-2">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Main</p>
                </div>
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-green-100 text-green-700 border-r-2 border-green-600' : 'text-gray-700 hover:bg-gray-100 hover:text-green-600' }}">
                    <i class="fas fa-tachometer-alt mr-3 text-lg"></i>
                    Dashboard
                </a>
            </div>

            <!-- Management Section -->
            <div class="space-y-1">
                <div class="px-4 py-2">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Management</p>
                </div>

                <!-- Custom Requests -->
                <a href="{{ route('admin.custom-requests.index') }}"
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.custom-requests.*') ? 'bg-green-100 text-green-700 border-r-2 border-green-600' : 'text-gray-700 hover:bg-gray-100 hover:text-green-600' }}">
                    <i class="fas fa-clipboard-list mr-3 text-lg"></i>
                    <span class="flex-1">Custom Requests</span>
                    @php
                        $pendingCustomRequests = \App\Models\FoodRequest::whereNull('food_item_id')
                            ->where('status', 'pending')
                            ->count();
                    @endphp
                    @if($pendingCustomRequests > 0)
                        <span class="bg-orange-500 text-white text-xs font-semibold px-2 py-0.5 rounded-full animate-pulse" title="{{ $pendingCustomRequests }} pending">
                            {{ $pendingCustomRequests }}
                        </span>
                    @endif
                </a>

                <!-- Ingredients -->
                <a href="{{ route('admin.max-price') }}"
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.max-price') ? 'bg-green-100 text-green-700 border-r-2 border-green-600' : 'text-gray-700 hover:bg-gray-100 hover:text-green-600' }}">
                    <i class="fas fa-carrot mr-3 text-lg"></i>
                    Ingredients
                </a>

                <!-- Customer Supplier Access -->
                <a href="{{ route('admin.customer-access.index') }}"
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.customer-access.*') ? 'bg-green-100 text-green-700 border-r-2 border-green-600' : 'text-gray-700 hover:bg-gray-100 hover:text-green-600' }}">
                    <i class="fas fa-link mr-3 text-lg"></i>
                    Access Control
                </a>

                <!-- User Management -->
                <a href="{{ route('admin.users') }}"
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.users') ? 'bg-green-100 text-green-700 border-r-2 border-green-600' : 'text-gray-700 hover:bg-gray-100 hover:text-green-600' }}">
                    <i class="fas fa-users mr-3 text-lg"></i>
                    Users
                </a>

                <!-- Transaction Report -->
                <a href="{{ route('admin.transactions.index') }}"
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.transactions.*') ? 'bg-green-100 text-green-700 border-r-2 border-green-600' : 'text-gray-700 hover:bg-gray-100 hover:text-green-600' }}">
                    <i class="fas fa-chart-line mr-3 text-lg"></i>
                    Transaction Report
                </a>
            </div>

            <!-- Settings Section -->
            <div class="space-y-1">
                <div class="px-4 py-2">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Settings</p>
                </div>
                <a href="{{ route('admin.settings.account') }}"
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.settings.*') ? 'bg-green-100 text-green-700 border-r-2 border-green-600' : 'text-gray-700 hover:bg-gray-100 hover:text-green-600' }}">
                    <i class="fas fa-user-circle mr-3 text-lg"></i>
                    Account Settings
                </a>
            </div>
        </div>
    </nav>

    <!-- Logout Section -->
    <div class="mt-auto p-4 border-t border-gray-200 bg-gray-50 flex-shrink-0">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center px-4 py-3 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                <i class="fas fa-sign-out-alt mr-3"></i>
                Logout
            </button>
        </form>
    </div>
</div>

<!-- Sidebar Overlay for Mobile -->
<div id="sidebarOverlay" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-[50] lg:hidden hidden"></div>

<!-- JavaScript for Sidebar Toggle -->
<script>
// Sidebar Toggle Functions
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const openBtn = document.getElementById('openSidebar');
    const mobileBtn = document.getElementById('mobile-menu-button');
    const closeBtn = document.getElementById('closeSidebar');

    // Open sidebar - can be triggered from any button with id="openSidebar" or "mobile-menu-button"
    if (openBtn) {
        openBtn.addEventListener('click', function() {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
        });
    }

    if (mobileBtn) {
        mobileBtn.addEventListener('click', function() {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
        });
    }

    // Also listen for any element with class "open-sidebar"
    document.querySelectorAll('.open-sidebar').forEach(function(btn) {
        btn.addEventListener('click', function() {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
        });
    });

    // Close sidebar
    if (closeBtn) {
        closeBtn.addEventListener('click', function() {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        });
    }

    // Close sidebar when clicking overlay
    if (overlay) {
        overlay.addEventListener('click', function() {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        });
    }

    // Close sidebar on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !sidebar.classList.contains('-translate-x-full')) {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        }
    });
});

</script>
