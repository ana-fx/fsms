@extends('layouts.app')

@section('title', 'Dashboard Supplier - FSMS')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard Supplier</h1>
        <p class="text-gray-600 mt-2">Kelola produk Anda dan terima pesanan dari admin</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6 card-hover">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-box text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Produk</p>
                    <p class="text-2xl font-semibold text-gray-900">28</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 card-hover">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-shopping-cart text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Order Aktif</p>
                    <p class="text-2xl font-semibold text-gray-900">5</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 card-hover">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Selesai</p>
                    <p class="text-2xl font-semibold text-gray-900">42</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 card-hover">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-dollar-sign text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pendapatan</p>
                    <p class="text-2xl font-semibold text-gray-900">Rp 15M</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Aksi Cepat</h2>
        <div class="flex flex-wrap gap-4">
            <button class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition flex items-center">
                <i class="fas fa-plus mr-2"></i>
                Tambah Produk
            </button>
            <button class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition flex items-center">
                <i class="fas fa-list mr-2"></i>
                Kelola Produk
            </button>
            <button class="bg-purple-600 text-white px-6 py-3 rounded-lg hover:bg-purple-700 transition flex items-center">
                <i class="fas fa-truck mr-2"></i>
                Kelola Pengiriman
            </button>
            <button class="bg-yellow-600 text-white px-6 py-3 rounded-lg hover:bg-yellow-700 transition flex items-center">
                <i class="fas fa-chart-bar mr-2"></i>
                Laporan Penjualan
            </button>
        </div>
    </div>

    <!-- Active Orders -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-900">Order Aktif</h2>
            <a href="#" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Lihat Semua</a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Order</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Yayasan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga Anda</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga Maksimal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#ORD001</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Yayasan Sejahtera</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Beras Premium</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">50 kg</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp 14.000/kg</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp 15.000/kg</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Dipilih</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700 mr-2">Kirim</button>
                            <button class="text-gray-600 hover:text-gray-800 text-sm">Detail</button>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#ORD002</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Yayasan Peduli</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Minyak Goreng</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">20 liter</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp 16.500/liter</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp 18.000/liter</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Menunggu</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button class="text-gray-400 cursor-not-allowed px-3 py-1 rounded text-sm">Kirim</button>
                            <button class="text-gray-600 hover:text-gray-800 text-sm">Detail</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Product Management -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-900">Kelola Produk</h2>
            <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">Tambah Produk</button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="bg-gray-200 h-32 rounded mb-3 flex items-center justify-center">
                    <i class="fas fa-image text-gray-400 text-2xl"></i>
                </div>
                <h3 class="font-semibold text-lg mb-2">Beras Premium</h3>
                <p class="text-gray-600 text-sm mb-2">Kualitas terbaik untuk kebutuhan yayasan</p>
                <div class="flex justify-between items-center mb-3">
                    <span class="text-green-600 font-bold">Rp 14.000/kg</span>
                    <span class="text-sm text-gray-500">Stok: 200 kg</span>
                </div>
                <div class="flex space-x-2">
                    <button class="text-blue-600 hover:text-blue-800 text-sm">Edit</button>
                    <button class="text-red-600 hover:text-red-800 text-sm">Hapus</button>
                </div>
            </div>

            <div class="border border-gray-200 rounded-lg p-4">
                <div class="bg-gray-200 h-32 rounded mb-3 flex items-center justify-center">
                    <i class="fas fa-image text-gray-400 text-2xl"></i>
                </div>
                <h3 class="font-semibold text-lg mb-2">Minyak Goreng</h3>
                <p class="text-gray-600 text-sm mb-2">Minyak goreng berkualitas tinggi</p>
                <div class="flex justify-between items-center mb-3">
                    <span class="text-green-600 font-bold">Rp 16.500/liter</span>
                    <span class="text-sm text-gray-500">Stok: 150 liter</span>
                </div>
                <div class="flex space-x-2">
                    <button class="text-blue-600 hover:text-blue-800 text-sm">Edit</button>
                    <button class="text-red-600 hover:text-red-800 text-sm">Hapus</button>
                </div>
            </div>

            <div class="border border-gray-200 rounded-lg p-4">
                <div class="bg-gray-200 h-32 rounded mb-3 flex items-center justify-center">
                    <i class="fas fa-image text-gray-400 text-2xl"></i>
                </div>
                <h3 class="font-semibold text-lg mb-2">Telur Ayam</h3>
                <p class="text-gray-600 text-sm mb-2">Telur segar dari peternak lokal</p>
                <div class="flex justify-between items-center mb-3">
                    <span class="text-green-600 font-bold">Rp 2.500/butir</span>
                    <span class="text-sm text-gray-500">Stok: 500 butir</span>
                </div>
                <div class="flex space-x-2">
                    <button class="text-blue-600 hover:text-blue-800 text-sm">Edit</button>
                    <button class="text-red-600 hover:text-red-800 text-sm">Hapus</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
