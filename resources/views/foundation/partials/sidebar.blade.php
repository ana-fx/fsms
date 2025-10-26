<!-- Foundation Sidebar -->
<div id="sidebar" class="absolute top-0 left-0 bottom-0 z-[60] w-64 bg-white shadow-lg transform transition-transform duration-300 ease-in-out -translate-x-full lg:relative lg:translate-x-0 overflow-y-auto" style="max-height: 100vh;">
    <!-- Logo Section -->
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center space-x-3 hover:opacity-80 transition-opacity">
                <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-green-600 to-green-700 rounded-lg shadow-md">
                    <i class="fas fa-utensils text-white text-lg"></i>
                </div>
                <div class="flex flex-col">
                    <span class="text-xl font-bold text-gray-900">FSMS</span>
                    <span class="text-xs text-gray-500 -mt-1">Foundation Panel</span>
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
                <p class="text-xs text-green-600 font-medium">Foundation</p>
            </div>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="mt-6">
        <div class="px-4 space-y-2">
            <!-- Dashboard -->
            <a href="{{ route('foundation.dashboard') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('foundation.dashboard') ? 'bg-green-100 text-green-700 border-r-2 border-green-600' : 'text-gray-700 hover:bg-gray-100 hover:text-green-600' }}">
                <i class="fas fa-tachometer-alt mr-3 text-lg"></i>
                Dashboard
            </a>

            <!-- Food Requests -->
            <div class="space-y-1">
                <button class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-green-600 rounded-lg transition-colors"
                        onclick="toggleSubmenu('requests')">
                    <div class="flex items-center">
                        <i class="fas fa-shopping-cart mr-3 text-lg"></i>
                        Permintaan Makanan
                    </div>
                    <i class="fas fa-chevron-down text-xs transition-transform" id="requests-arrow"></i>
                </button>
                <div id="requests-submenu" class="hidden ml-8 space-y-1">
                    <a href="{{ route('foundation.requests.index') }}"
                       class="block px-4 py-2 text-sm text-gray-600 hover:text-green-600 hover:bg-gray-50 rounded transition-colors {{ request()->routeIs('foundation.requests.index') ? 'text-green-600 bg-green-50' : '' }}">
                        <i class="fas fa-list mr-2"></i>
                        Semua Permintaan
                    </a>
                    <a href="{{ route('foundation.requests.create') }}"
                       class="block px-4 py-2 text-sm text-gray-600 hover:text-green-600 hover:bg-gray-50 rounded transition-colors {{ request()->routeIs('foundation.requests.create') ? 'text-green-600 bg-green-50' : '' }}">
                        <i class="fas fa-plus mr-2"></i>
                        Buat Permintaan Baru
                    </a>
                </div>
            </div>

            <!-- Programs -->
            <a href="{{ route('foundation.programs') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('foundation.programs') ? 'bg-green-100 text-green-700 border-r-2 border-green-600' : 'text-gray-700 hover:bg-gray-100 hover:text-green-600' }}">
                <i class="fas fa-project-diagram mr-3 text-lg"></i>
                Kelola Program
            </a>

            <!-- Settings -->
            <a href="#"
               class="flex items-center px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-green-600 rounded-lg transition-colors">
                <i class="fas fa-cog mr-3 text-lg"></i>
                Pengaturan
            </a>
        </div>
    </nav>

    <!-- Logout Section -->
    <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-200 bg-gray-50">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center px-4 py-3 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                <i class="fas fa-sign-out-alt mr-3"></i>
                Keluar
            </button>
        </form>
    </div>
</div>

<!-- Sidebar Overlay for Mobile -->
<div id="sidebarOverlay" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-[50] lg:hidden hidden"></div>

<!-- JavaScript for Sidebar Toggle -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const openBtn = document.getElementById('openSidebar');
    const mobileBtn = document.getElementById('mobile-menu-button');
    const closeBtn = document.getElementById('closeSidebar');

    // Open sidebar
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
});

function toggleSubmenu(menuId) {
    const submenu = document.getElementById(menuId + '-submenu');
    const arrow = document.getElementById(menuId + '-arrow');

    if (submenu.classList.contains('hidden')) {
        submenu.classList.remove('hidden');
        arrow.style.transform = 'rotate(180deg)';
    } else {
        submenu.classList.add('hidden');
        arrow.style.transform = 'rotate(0deg)';
    }
}
</script>
