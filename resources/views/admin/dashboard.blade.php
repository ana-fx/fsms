@extends('layouts.app')

@section('title', 'Dashboard Super Admin - FSMS')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard Super Admin</h1>
        <p class="text-gray-600 mt-2">Kelola seluruh sistem pasokan bahan makanan dan tentukan harga maksimal</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6 card-hover">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-clipboard-list text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Request</p>
                    <p class="text-2xl font-semibold text-gray-900">45</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 card-hover">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-truck text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Supplier Aktif</p>
                    <p class="text-2xl font-semibold text-gray-900">12</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 card-hover">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-building text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Yayasan Terdaftar</p>
                    <p class="text-2xl font-semibold text-gray-900">8</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 card-hover">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-dollar-sign text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Budget</p>
                    <p class="text-2xl font-semibold text-gray-900">Rp 25M</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Aksi Cepat</h2>
        <div class="flex flex-wrap gap-4">
            <button class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition flex items-center">
                <i class="fas fa-cog mr-2"></i>
                Set Harga Maksimal
            </button>
            <button class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition flex items-center">
                <i class="fas fa-list mr-2"></i>
                Kelola Request
            </button>
            <button class="bg-purple-600 text-white px-6 py-3 rounded-lg hover:bg-purple-700 transition flex items-center">
                <i class="fas fa-users mr-2"></i>
                Kelola Supplier
            </button>
            <button class="bg-yellow-600 text-white px-6 py-3 rounded-lg hover:bg-yellow-700 transition flex items-center">
                <i class="fas fa-chart-line mr-2"></i>
                Laporan Sistem
            </button>
            <a href="{{ route('admin.accounts') }}" class="bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 transition flex items-center">
                <i class="fas fa-user-cog mr-2"></i>
                Manajemen Akun
            </a>
        </div>
    </div>

    <!-- Pending Requests -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-900">Request Menunggu Persetujuan</h2>
            <a href="#" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Lihat Semua</a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Request</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Yayasan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bahan Makanan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga Maksimal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#REQ001</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Yayasan Sejahtera</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Beras Premium</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">50 kg</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp 15.000/kg</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">25 Okt 2024</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700 mr-2">Setujui</button>
                            <button class="bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700">Tolak</button>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#REQ002</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Yayasan Peduli</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Minyak Goreng</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">20 liter</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp 18.000/liter</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">24 Okt 2024</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700 mr-2">Setujui</button>
                            <button class="bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700">Tolak</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Supplier Management -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-900">Kelola Supplier</h2>
            <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">Tambah Supplier</button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center mb-3">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-store text-green-600"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="font-semibold text-gray-900">Supplier ABC</h3>
                        <p class="text-sm text-gray-500">Status: Aktif</p>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Rating:</span>
                        <span class="text-yellow-500">★★★★☆</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Total Order:</span>
                        <span class="font-medium">25</span>
                    </div>
                </div>
                <div class="mt-3 flex space-x-2">
                    <button class="text-blue-600 hover:text-blue-800 text-sm">Edit</button>
                    <button class="text-red-600 hover:text-red-800 text-sm">Nonaktifkan</button>
                </div>
            </div>

            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center mb-3">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-store text-green-600"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="font-semibold text-gray-900">Supplier XYZ</h3>
                        <p class="text-sm text-gray-500">Status: Aktif</p>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Rating:</span>
                        <span class="text-yellow-500">★★★★★</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Total Order:</span>
                        <span class="font-medium">42</span>
                    </div>
                </div>
                <div class="mt-3 flex space-x-2">
                    <button class="text-blue-600 hover:text-blue-800 text-sm">Edit</button>
                    <button class="text-red-600 hover:text-red-800 text-sm">Nonaktifkan</button>
                </div>
            </div>

            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center mb-3">
                    <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-store text-red-600"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="font-semibold text-gray-900">Supplier DEF</h3>
                        <p class="text-sm text-gray-500">Status: Nonaktif</p>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Rating:</span>
                        <span class="text-yellow-500">★★☆☆☆</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Total Order:</span>
                        <span class="font-medium">8</span>
                    </div>
                </div>
                <div class="mt-3 flex space-x-2">
                    <button class="text-blue-600 hover:text-blue-800 text-sm">Edit</button>
                    <button class="text-green-600 hover:text-green-800 text-sm">Aktifkan</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
