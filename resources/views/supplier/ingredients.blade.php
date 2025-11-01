@extends('layouts.app')

@section('title', 'Manage Ingredients - FSMS')

@section('content')
@php
    $products = \App\Models\FoodItem::where('supplier_id', auth()->id())->with('foodCategory')->get();
@endphp

<div class="flex bg-gray-100 min-h-screen w-full overflow-x-hidden">
    @include('supplier.partials.sidebar')

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
            <h1 class="text-3xl font-bold text-gray-900">Manage Ingredients</h1>
            <p class="text-gray-600 mt-2">Supplier ingredient and inventory management</p>
        </div>
        @if(session('status'))
            @php $alert = session('status'); $type = $alert['type'] ?? 'success'; @endphp
            <div class="mb-6">
                @if($type === 'danger')
                    <div class="rounded-md bg-red-50 p-4 border border-red-200">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-red-600"></i>
                </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-red-800">{{ $alert['message'] ?? $alert }}</p>
                        </div>
                        </div>
                    </div>
                @else
                    <div class="rounded-md bg-green-50 p-4 border border-green-200">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-green-600"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">{{ $alert['message'] ?? $alert }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold">Ingredients Management</h2>
                    <a href="{{ route('supplier.ingredients.create') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition inline-flex items-center">
                        <i class="fas fa-plus mr-2"></i>Add Ingredient
                    </a>
                </div>

                @if($products->count())
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price/Unit</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Min Purchase</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($products as $product)
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="font-medium text-gray-900">{{ $product->name }}</div>
                                    <div class="text-sm text-gray-500">{{ Str::limit($product->description, 60) }}</div>
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    <span class="inline-block px-2 py-1 text-xs font-semibold rounded" style="background: {{ $product->foodCategory->color }}20; color: {{ $product->foodCategory->color }}">{{ $product->foodCategory->name }}</span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}<span class="text-xs text-gray-500">/{{ $product->unit }}</span></td>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $product->min_purchase }} {{ $product->unit }}</td>
                                <td class="px-4 py-3 text-sm">
                                    @if($product->is_active)
                                        <span class="px-2 py-1 text-xs font-semibold rounded bg-green-100 text-green-700">Active</span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded bg-gray-100 text-gray-600">Inactive</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right space-x-2">
                                    <a href="{{ route('supplier.ingredients.edit', $product) }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 text-sm font-medium py-1 px-2 border border-blue-600 rounded hover:bg-blue-50 transition"><i class="fas fa-edit mr-1"></i>Edit</a>
                                    <button onclick="confirmDeleteIngredient({{ $product->id }}, '{{ $product->name }}')" class="inline-flex items-center text-red-600 hover:text-red-800 text-sm font-medium py-1 px-2 border border-red-600 rounded hover:bg-red-50 transition"><i class="fas fa-trash mr-1"></i>Delete</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                        </div>
                @else
                <div class="text-center py-12">
                    <i class="fas fa-box text-4xl text-gray-400 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No ingredients yet</h3>
                    <p class="text-gray-500 mb-6">Start by adding your first ingredient</p>
                    <a href="{{ route('supplier.ingredients.create') }}" class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors font-semibold inline-block">
                        <i class="fas fa-plus mr-2"></i>Add Ingredient
                    </a>
                </div>
                @endif
            </div>
        </div>
        </div>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div id="confirmModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 hidden items-center justify-center" style="display: none;">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
        <div class="p-6">
            <div class="flex items-center mb-4">
                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-yellow-100 mr-4">
                    <i class="fas fa-exclamation-triangle text-yellow-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">Confirm Delete</h3>
            </div>
            <p id="confirmMessage" class="text-gray-600 mb-6"></p>
            <form id="deleteForm" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeConfirmModal()" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium">
                        Delete
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function showNotification(message, type = 'success') {
    const colors = {
        success: { bg: 'bg-green-50', border: 'border-green-200', text: 'text-green-800', icon: 'fa-check-circle', iconColor: 'text-green-600' },
        error: { bg: 'bg-red-50', border: 'border-red-200', text: 'text-red-800', icon: 'fa-exclamation-circle', iconColor: 'text-red-600' },
        warning: { bg: 'bg-yellow-50', border: 'border-yellow-200', text: 'text-yellow-800', icon: 'fa-exclamation-triangle', iconColor: 'text-yellow-600' },
        info: { bg: 'bg-blue-50', border: 'border-blue-200', text: 'text-blue-800', icon: 'fa-info-circle', iconColor: 'text-blue-600' }
    };

    const color = colors[type] || colors.success;

    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 ${color.bg} ${color.border} border rounded-lg shadow-lg z-50 flex items-center space-x-3 p-4 animate-slide-in`;
    notification.style.minWidth = '300px';
    notification.innerHTML = `
        <div class="flex-shrink-0">
            <i class="fas ${color.icon} ${color.iconColor} text-xl"></i>
        </div>
        <div class="flex-1">
            <p class="${color.text} font-medium">${message}</p>
        </div>
        <button onclick="this.parentElement.remove()" class="flex-shrink-0 ${color.text} hover:opacity-70 transition-opacity">
            <i class="fas fa-times"></i>
        </button>
    `;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.style.animation = 'slide-out 0.3s ease-out';
        setTimeout(() => notification.remove(), 300);
    }, 5000);
}

function confirmDeleteIngredient(id, name) {
    const modal = document.getElementById('confirmModal');
    document.getElementById('confirmMessage').textContent = `Are you sure you want to delete "${name}"? This action cannot be undone.`;
    document.getElementById('deleteForm').action = `/supplier/ingredients/${id}`;
    modal.classList.remove('hidden');
    modal.style.display = 'flex';
}

function closeConfirmModal() {
    const modal = document.getElementById('confirmModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
    document.getElementById('deleteForm').action = '';
}

// Close modal when clicking outside
document.getElementById('confirmModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeConfirmModal();
    }
});
</script>
<style>
@keyframes slide-in {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slide-out {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(100%);
        opacity: 0;
    }
}

.animate-slide-in {
    animation: slide-in 0.3s ease-out;
}
</style>
@endpush
@endsection
