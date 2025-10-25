<!-- Supplier Sidebar -->
<div class="w-64 bg-white shadow-lg border-r border-gray-200 h-screen fixed left-0 top-0 z-40">
    <!-- Logo Section -->
    <div class="p-6 border-b border-gray-200">
        <a href="{{ route('home') }}" class="flex items-center space-x-3 hover:opacity-80 transition-opacity">
            <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-green-600 to-green-700 rounded-lg shadow-md">
                <i class="fas fa-utensils text-white text-lg"></i>
            </div>
            <div class="flex flex-col">
                <span class="text-xl font-bold text-gray-900">FSMS</span>
                <span class="text-xs text-gray-500 -mt-1">Supplier Panel</span>
            </div>
        </a>
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
    <nav class="mt-6">
        <div class="px-4 space-y-2">
            <!-- Dashboard -->
            <a href="{{ route('supplier.dashboard') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('supplier.dashboard') ? 'bg-green-100 text-green-700 border-r-2 border-green-600' : 'text-gray-700 hover:bg-gray-100 hover:text-green-600' }}">
                <i class="fas fa-tachometer-alt mr-3 text-lg"></i>
                Dashboard
            </a>

            <!-- Product Management -->
            <div class="space-y-1">
                <button class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-green-600 rounded-lg transition-colors"
                        onclick="toggleSubmenu('products')">
                    <div class="flex items-center">
                        <i class="fas fa-box mr-3 text-lg"></i>
                        Kelola Produk
                    </div>
                    <i class="fas fa-chevron-down text-xs transition-transform" id="products-arrow"></i>
                </button>
                <div id="products-submenu" class="hidden ml-8 space-y-1">
                    <a href="{{ route('supplier.products') }}"
                       class="block px-4 py-2 text-sm text-gray-600 hover:text-green-600 hover:bg-gray-50 rounded transition-colors {{ request()->routeIs('supplier.products') ? 'text-green-600 bg-green-50' : '' }}">
                        <i class="fas fa-list mr-2"></i>
                        Daftar Produk
                    </a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:text-green-600 hover:bg-gray-50 rounded transition-colors">
                        <i class="fas fa-plus mr-2"></i>
                        Tambah Produk
                    </a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:text-green-600 hover:bg-gray-50 rounded transition-colors">
                        <i class="fas fa-tags mr-2"></i>
                        Kategori Produk
                    </a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:text-green-600 hover:bg-gray-50 rounded transition-colors">
                        <i class="fas fa-dollar-sign mr-2"></i>
                        Kelola Harga
                    </a>
                </div>
            </div>

            <!-- Orders -->
            <div class="space-y-1">
                <button class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-green-600 rounded-lg transition-colors"
                        onclick="toggleSubmenu('orders')">
                    <div class="flex items-center">
                        <i class="fas fa-shopping-cart mr-3 text-lg"></i>
                        Kelola Order
                    </div>
                    <i class="fas fa-chevron-down text-xs transition-transform" id="orders-arrow"></i>
                </button>
                <div id="orders-submenu" class="hidden ml-8 space-y-1">
                    <a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:text-green-600 hover:bg-gray-50 rounded transition-colors">
                        <i class="fas fa-clock mr-2"></i>
                        Order Pending
                    </a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:text-green-600 hover:bg-gray-50 rounded transition-colors">
                        <i class="fas fa-truck mr-2"></i>
                        Order Aktif
                    </a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:text-green-600 hover:bg-gray-50 rounded transition-colors">
                        <i class="fas fa-check-circle mr-2"></i>
                        Order Selesai
                    </a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:text-green-600 hover:bg-gray-50 rounded transition-colors">
                        <i class="fas fa-list mr-2"></i>
                        Semua Order
                    </a>
                </div>
            </div>

            <!-- Inventory -->
            <a href="#"
               class="flex items-center px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-green-600 rounded-lg transition-colors">
                <i class="fas fa-warehouse mr-3 text-lg"></i>
                Manajemen Stok
            </a>

            <!-- Reports -->
            <div class="space-y-1">
                <button class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-green-600 rounded-lg transition-colors"
                        onclick="toggleSubmenu('reports')">
                    <div class="flex items-center">
                        <i class="fas fa-chart-line mr-3 text-lg"></i>
                        Laporan
                    </div>
                    <i class="fas fa-chevron-down text-xs transition-transform" id="reports-arrow"></i>
                </button>
                <div id="reports-submenu" class="hidden ml-8 space-y-1">
                    <a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:text-green-600 hover:bg-gray-50 rounded transition-colors">
                        <i class="fas fa-chart-bar mr-2"></i>
                        Laporan Penjualan
                    </a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:text-green-600 hover:bg-gray-50 rounded transition-colors">
                        <i class="fas fa-chart-pie mr-2"></i>
                        Statistik Produk
                    </a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:text-green-600 hover:bg-gray-50 rounded transition-colors">
                        <i class="fas fa-file-export mr-2"></i>
                        Export Data
                    </a>
                </div>
            </div>

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

<!-- JavaScript for Submenu Toggle -->
<script>
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
