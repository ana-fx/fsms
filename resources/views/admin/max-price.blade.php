@extends('layouts.app')

@section('title', 'Set Harga Maksimal - FSMS')

@section('content')
@php
    $categories = \App\Models\FoodCategory::with('maxPriceSetting')->get();
@endphp

<div class="flex bg-gray-100 min-h-screen w-full overflow-x-hidden">
    @include('admin.partials.sidebar')

    <!-- Mobile Menu Button -->
    <button id="openSidebar" class="lg:hidden fixed top-4 left-4 z-50 p-2 bg-white rounded-lg shadow-lg hover:bg-gray-100 transition-colors">
        <i class="fas fa-bars text-gray-700 text-xl"></i>
    </button>

    <!-- Main Content -->
    <div class="w-full lg:ml-64 transition-all duration-300">
        <div class="flex-1 bg-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Set Harga Maksimal</h1>
            <p class="text-sm lg:text-base text-gray-600 mt-2">Manage maximum price per food category</p>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        <!-- Price Settings Table -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="mb-6">
                <h2 class="text-xl font-semibold text-gray-900">Price Categories</h2>
                <p class="text-sm text-gray-600 mt-1">Atur harga maksimal untuk setiap kategori makanan</p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga Maksimal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Terakhir Diupdate</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($categories as $category)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="p-2 rounded-lg" style="background: {{ $category->color }}20">
                                        <i class="{{ $category->icon }}" style="color: {{ $category->color }}"></i>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $category->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($category->maxPriceSetting)
                                    <span class="text-sm font-semibold text-green-600">Rp {{ number_format($category->maxPriceSetting->max_price, 0, ',', '.') }}</span>
                                @else
                                    <span class="text-sm text-gray-400">Belum diatur</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $category->maxPriceSetting->unit ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ Str::limit($category->maxPriceSetting->notes ?? 'Tidak ada catatan', 50) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $category->maxPriceSetting ? $category->maxPriceSetting->updated_at->format('d M Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button onclick="openEditModal({{ $category->id }}, '{{ $category->name }}', {{ $category->maxPriceSetting->max_price ?? 'null' }}, '{{ $category->maxPriceSetting->unit ?? 'kg' }}', '{{ $category->maxPriceSetting->notes ?? '' }}', {{ $category->maxPriceSetting->id ?? 'null' }})" class="text-green-600 hover:text-green-900">
                                    <i class="fas fa-edit mr-1"></i>Edit
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        </div>
        </div>
    </div>
</div>

<!-- Modal untuk Edit Max Price -->
<div id="editPriceModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Set Harga Maksimal</h3>
                <form id="priceForm" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                        <input type="text" id="categoryName" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100" readonly>
                        <input type="hidden" id="categoryId" name="category_id">
                    </div>
                    <div class="mb-4">
                        <label for="maxPrice" class="block text-sm font-medium text-gray-700 mb-2">Harga Maksimal</label>
                        <input type="number" id="maxPrice" name="max_price" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" required>
                    </div>
                    <div class="mb-4">
                        <label for="unit" class="block text-sm font-medium text-gray-700 mb-2">Unit</label>
                        <select id="unit" name="unit" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" required>
                            <option value="kg">Kilogram (kg)</option>
                            <option value="liter">Liter</option>
                            <option value="pcs">Pieces</option>
                            <option value="pak">Pak</option>
                            <option value="dus">Dus</option>
                            <option value="karton">Karton</option>
                            <option value="ikat">Ikat</option>
                        </select>
                    </div>
                    <div class="mb-6">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                        <textarea id="notes" name="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"></textarea>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeModal()" class="px-4 py-2 text-gray-700 bg-gray-200 rounded hover:bg-gray-300 transition">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function openEditModal(categoryId, categoryName, maxPrice, unit, notes, settingId) {
    document.getElementById('categoryId').value = categoryId;
    document.getElementById('categoryName').value = categoryName;
    document.getElementById('maxPrice').value = maxPrice;
    document.getElementById('unit').value = unit;
    document.getElementById('notes').value = notes;

    // Set form action
    const form = document.getElementById('priceForm');
    form.action = settingId ? `/admin/max-price/${settingId}` : '/admin/max-price';
    form.method = settingId ? 'POST' : 'POST';

    if (settingId) {
        form.innerHTML += '<input type="hidden" name="_method" value="PUT">';
    }

    document.getElementById('editPriceModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('editPriceModal').classList.add('hidden');
    document.getElementById('priceForm').reset();
}
</script>
@endsection

