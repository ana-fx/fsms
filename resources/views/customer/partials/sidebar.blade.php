<!-- Customer Sidebar -->
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
                    <span class="text-xs text-gray-500 -mt-1">Customer Panel</span>
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
                <p class="text-xs text-green-600 font-medium">Customer</p>
            </div>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 overflow-y-auto py-4">
        <div class="space-y-1 px-3">
            <!-- Dashboard -->
            <a href="{{ route('customer.dashboard') }}"
               class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('customer.dashboard') ? 'bg-green-50 text-green-700 border-r-2 border-green-600 shadow-sm' : 'text-gray-700 hover:bg-gray-50 hover:text-green-600' }}">
                <i class="fas fa-home w-5 text-center mr-3"></i>
                <span>Dashboard</span>
            </a>

            <!-- Divider -->
            <div class="my-3 border-t border-gray-200"></div>

            <!-- Shopping Section -->
            <div class="px-3 mb-2">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Shopping</p>
                    </div>
            
            <a href="{{ route('customer.ingredients') }}"
               class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('customer.ingredients') ? 'bg-green-50 text-green-700 border-r-2 border-green-600 shadow-sm' : 'text-gray-700 hover:bg-gray-50 hover:text-green-600' }}">
                <i class="fas fa-box w-5 text-center mr-3"></i>
                <span>Ingredients</span>
            </a>
            
            <a href="{{ route('customer.cart') }}"
               class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('customer.cart') ? 'bg-green-50 text-green-700 border-r-2 border-green-600 shadow-sm' : 'text-gray-700 hover:bg-gray-50 hover:text-green-600' }}">
                <i class="fas fa-shopping-cart w-5 text-center mr-3"></i>
                <span>Cart</span>
                @php
                    $cartCount = array_sum(array_column(session('cart', []), 'quantity'));
                @endphp
                @if($cartCount > 0)
                    <span id="cart-badge" class="ml-auto bg-green-600 text-white text-xs font-bold px-2 py-0.5 rounded-full min-w-[20px] text-center">{{ $cartCount }}</span>
                @else
                    <span id="cart-badge" class="hidden ml-auto bg-green-600 text-white text-xs font-bold px-2 py-0.5 rounded-full min-w-[20px] text-center">0</span>
                @endif
            </a>

            <!-- Divider -->
            <div class="my-3 border-t border-gray-200"></div>

            <!-- Orders Section -->
            <div class="px-3 mb-2">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Orders</p>
            </div>

            <a href="{{ route('customer.requests.index') }}"
               class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('customer.requests.index') || request()->routeIs('customer.requests.show') || request()->routeIs('customer.requests.edit') ? 'bg-green-50 text-green-700 border-r-2 border-green-600 shadow-sm' : 'text-gray-700 hover:bg-gray-50 hover:text-green-600' }}">
                <i class="fas fa-list-alt w-5 text-center mr-3"></i>
                <span>My Requests</span>
                @php
                    $pendingPaymentCount = \App\Models\FoodRequest::where('customer_id', auth()->id())
                        ->where('status', 'payment_pending')
                        ->count();
                @endphp
                @if($pendingPaymentCount > 0)
                    <span class="ml-auto bg-orange-500 text-white text-xs font-bold px-2 py-0.5 rounded-full min-w-[20px] text-center animate-pulse" title="{{ $pendingPaymentCount }} payment(s) pending">
                        {{ $pendingPaymentCount }}
                    </span>
                @endif
            </a>
            
            <a href="{{ route('customer.requests.custom.create') }}"
               class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('customer.requests.custom.*') ? 'bg-green-50 text-green-700 border-r-2 border-green-600 shadow-sm' : 'text-gray-700 hover:bg-gray-50 hover:text-green-600' }}">
                <i class="fas fa-plus-circle w-5 text-center mr-3"></i>
                <span>Custom Request</span>
            </a>
            
            <a href="{{ route('customer.purchase-report') }}"
               class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('customer.purchase-report') ? 'bg-green-50 text-green-700 border-r-2 border-green-600 shadow-sm' : 'text-gray-700 hover:bg-gray-50 hover:text-green-600' }}">
                <i class="fas fa-chart-line w-5 text-center mr-3"></i>
                <span>Purchase Report</span>
            </a>

            <!-- Divider -->
            <div class="my-3 border-t border-gray-200"></div>

            <!-- Settings Section -->
            <div class="px-3 mb-2">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Account</p>
            </div>
            
            @php
                $isSettingsActive = request()->routeIs('customer.settings.*');
            @endphp
            
            <button class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ $isSettingsActive ? 'bg-green-50 text-green-700 border-r-2 border-green-600 shadow-sm' : 'text-gray-700 hover:bg-gray-50 hover:text-green-600' }}"
                    onclick="toggleSubmenu('settings')">
                <div class="flex items-center">
                    <i class="fas fa-cog w-5 text-center mr-3"></i>
                    <span>Settings</span>
                </div>
                <i class="fas fa-chevron-down text-xs transition-transform duration-200 {{ $isSettingsActive ? 'rotate-180' : '' }}" id="settings-arrow"></i>
            </button>
            
            <div id="settings-submenu" class="{{ $isSettingsActive ? '' : 'hidden' }} ml-4 mt-1 space-y-1 border-l-2 border-gray-200 pl-3">
                <a href="{{ route('customer.settings.account') }}"
                   class="flex items-center px-3 py-2 text-sm rounded-md transition-all duration-200 {{ request()->routeIs('customer.settings.account') ? 'text-green-600 bg-green-50 font-medium' : 'text-gray-600 hover:text-green-600 hover:bg-gray-50' }}">
                    <i class="fas fa-user-circle w-4 text-center mr-2 text-xs"></i>
                    <span>Account</span>
                </a>
                <a href="{{ route('customer.settings.delivery-addresses') }}"
                   class="flex items-center px-3 py-2 text-sm rounded-md transition-all duration-200 {{ request()->routeIs('customer.settings.delivery-addresses') ? 'text-green-600 bg-green-50 font-medium' : 'text-gray-600 hover:text-green-600 hover:bg-gray-50' }}">
                    <i class="fas fa-map-marker-alt w-4 text-center mr-2 text-xs"></i>
                    <span>Addresses</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Logout Section -->
    <div class="mt-auto border-t border-gray-200 bg-gray-50 flex-shrink-0">
        <form method="POST" action="{{ route('logout') }}" class="p-3">
            @csrf
            <button type="submit" class="w-full flex items-center px-3 py-2.5 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition-all duration-200">
                <i class="fas fa-sign-out-alt w-5 text-center mr-3"></i>
                <span>Logout</span>
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
        arrow.classList.add('rotate-180');
        arrow.style.transform = 'rotate(180deg)';
    } else {
        submenu.classList.add('hidden');
        arrow.classList.remove('rotate-180');
        arrow.style.transform = 'rotate(0deg)';
    }
}

// Auto-expand settings submenu on page load if active
document.addEventListener('DOMContentLoaded', function() {
    @if(request()->routeIs('customer.settings.*'))
    const settingsSubmenu = document.getElementById('settings-submenu');
    const settingsArrow = document.getElementById('settings-arrow');
    if (settingsSubmenu && settingsArrow) {
        settingsSubmenu.classList.remove('hidden');
        settingsArrow.classList.add('rotate-180');
        settingsArrow.style.transform = 'rotate(180deg)';
    }
    @endif
});
</script>
