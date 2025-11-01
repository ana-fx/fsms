<!-- Supplier Sidebar -->
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
                    <span class="text-xs text-gray-500 -mt-1">Supplier Panel</span>
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
                <p class="text-xs text-green-600 font-medium">Supplier</p>
            </div>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="mt-6 flex-1 overflow-y-auto">
        <div class="px-4 space-y-2">
            <!-- Dashboard -->
            @php
                // Get pending orders count for this supplier
                $ingredientIds = \App\Models\FoodItem::where('supplier_id', auth()->id())->pluck('id');
                $pendingOrdersCount = \App\Models\FoodRequest::whereIn('food_item_id', $ingredientIds)
                    ->where('status', 'pending')
                    ->count();
            @endphp
            <a href="{{ route('supplier.dashboard') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('supplier.dashboard') ? 'bg-green-100 text-green-700 border-r-2 border-green-600' : 'text-gray-700 hover:bg-gray-100 hover:text-green-600' }}">
                <i class="fas fa-tachometer-alt mr-3 text-lg"></i>
                Dashboard
                @if($pendingOrdersCount > 0)
                    <span class="ml-auto bg-yellow-500 text-white text-xs font-semibold px-2 py-1 rounded-full">{{ $pendingOrdersCount }}</span>
                @endif
            </a>

            <!-- Ingredients Management -->
            <div class="space-y-1">
                <button class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-green-600 rounded-lg transition-colors"
                        onclick="toggleSubmenu('products')">
                    <div class="flex items-center">
                        <i class="fas fa-box mr-3 text-lg"></i>
                        Manage Ingredients
                    </div>
                    <i class="fas fa-chevron-down text-xs transition-transform" id="products-arrow"></i>
                </button>
                <div id="products-submenu" class="hidden ml-8 space-y-1">
                    <a href="{{ route('supplier.ingredients.create') }}"
                       class="block px-4 py-2 text-sm text-gray-600 hover:text-green-600 hover:bg-gray-50 rounded transition-colors {{ request()->routeIs('supplier.ingredients.create') ? 'text-green-600 bg-green-50' : '' }}">
                        <i class="fas fa-plus mr-2"></i>
                        Add Ingredient
                    </a>
                    <a href="{{ route('supplier.ingredients') }}"
                       class="block px-4 py-2 text-sm text-gray-600 hover:text-green-600 hover:bg-gray-50 rounded transition-colors {{ request()->routeIs('supplier.ingredients') ? 'text-green-600 bg-green-50' : '' }}">
                        <i class="fas fa-list mr-2"></i>
                        Ingredients List
                    </a>
                </div>
            </div>

            <!-- Settings -->
            <div class="space-y-1">
                <button class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-green-600 rounded-lg transition-colors"
                        onclick="toggleSubmenu('settings')">
                    <div class="flex items-center">
                        <i class="fas fa-cog mr-3 text-lg"></i>
                        Settings
                    </div>
                    <i class="fas fa-chevron-down text-xs transition-transform" id="settings-arrow"></i>
                </button>
                <div id="settings-submenu" class="hidden ml-8 space-y-1">
                    <a href="{{ route('supplier.settings.account') }}"
                       class="block px-4 py-2 text-sm text-gray-600 hover:text-green-600 hover:bg-gray-50 rounded transition-colors {{ request()->routeIs('supplier.settings.account') ? 'text-green-600 bg-green-50' : '' }}">
                        <i class="fas fa-user-circle mr-2"></i>
                        Account Settings
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Logout Section -->
    <div class="mt-auto p-4 border-t border-gray-200 bg-gray-50 flex-shrink-0">
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
