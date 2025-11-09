@extends('layouts.app')

@section('title', 'Manage Ingredients - FSMS')

@section('content')
@php
    $products = \App\Models\FoodItem::where('supplier_id', auth()->id())->with('foodCategory.parent')->orderBy('created_at', 'desc')->get();
@endphp

<div class="flex bg-gray-100 min-h-screen w-full overflow-x-hidden">
    @include('supplier.partials.sidebar')

    <!-- Mobile Menu Button -->
    <button id="openSidebar" class="lg:hidden fixed top-4 left-4 z-50 p-2 bg-white rounded-lg shadow-lg hover:bg-gray-100 transition-colors">
        <i class="fas fa-bars text-gray-700 text-xl"></i>
    </button>

    <!-- Main Content -->
    <div class="w-full lg:ml-64 transition-all duration-300 overflow-x-hidden">
        <div class="flex-1 bg-gray-100 min-h-screen">
            <div class="w-full py-8">
                <!-- Header -->
                <div class="mb-8 px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">Manage Ingredients</h1>
                            <p class="mt-2 text-gray-600">Supplier ingredient and inventory management</p>
                        </div>
                        <a href="{{ route('supplier.ingredients.create') }}" class="hidden sm:flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold">
                            <i class="fas fa-plus-circle mr-2"></i>
                            Add Ingredient
                        </a>
                    </div>
                </div>

                <!-- Ingredients List -->
                <div class="bg-white rounded-lg shadow mx-4 sm:mx-6 lg:mx-8">
                    <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Ingredients Management</h3>
                    </div>

                    @if($products->count() > 0)
                        <!-- Desktop Table View -->
                        <div class="hidden md:block overflow-x-auto">
                            <table class="w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Name</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Category</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Price/Unit</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Min Purchase</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Max Purchase</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($products as $product)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                <div class="flex items-center space-x-3">
                                                    <div class="flex-shrink-0 w-10 h-10 rounded-lg overflow-hidden bg-gray-100 flex items-center justify-center">
                                                        @if($product->image)
                                                            <img src="{{ asset('storage/' . $product->image) }}"
                                                                 alt="{{ $product->name }}"
                                                                 class="w-full h-full object-cover">
                                                        @else
                                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                                            </svg>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <div class="text-sm font-semibold text-gray-900">{{ $product->name }}</div>
                                                        <div class="text-xs text-gray-500 mt-0.5">{{ Str::limit($product->description, 50) }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                @if($product->foodCategory->parent)
                                                    <div class="flex items-center gap-1">
                                                        <span class="inline-block px-2 py-1 text-xs font-semibold rounded" style="background: {{ $product->foodCategory->parent->color }}20; color: {{ $product->foodCategory->parent->color }}">{{ $product->foodCategory->parent->name }}</span>
                                                        <span class="text-gray-400">›</span>
                                                        <span class="inline-block px-2 py-1 text-xs font-semibold rounded" style="background: {{ $product->foodCategory->color }}20; color: {{ $product->foodCategory->color }}">{{ $product->foodCategory->name }}</span>
                                                    </div>
                                                @else
                                                    <span class="inline-block px-2 py-1 text-xs font-semibold rounded" style="background: {{ $product->foodCategory->color }}20; color: {{ $product->foodCategory->color }}">{{ $product->foodCategory->name }}</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}<span class="text-xs text-gray-500">/{{ $product->unit }}</span></div>
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $product->min_purchase }} {{ $product->unit }}</div>
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                @if($product->max_purchase)
                                                    <div class="text-sm font-medium text-gray-900">{{ $product->max_purchase }} {{ $product->unit }}</div>
                                                @else
                                                    <span class="text-xs text-gray-400 italic">No limit</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                @if($product->is_active)
                                                    <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Inactive</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                <div class="flex items-center justify-center space-x-1.5">
                                                    <a href="{{ route('supplier.ingredients.edit', $product) }}" class="inline-flex items-center justify-center w-9 h-9 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-colors" title="Edit">
                                                        <i class="fas fa-edit text-sm"></i>
                                                    </a>
                                                    <button onclick="confirmDeleteIngredient({{ $product->id }}, '{{ $product->name }}')" class="inline-flex items-center justify-center w-9 h-9 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 transition-colors" title="Delete">
                                                        <i class="fas fa-trash text-sm"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Mobile Card View -->
                        <div class="block md:hidden divide-y divide-gray-200">
                            @foreach($products as $product)
                                <div class="p-4 hover:bg-gray-50 transition-colors">
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="flex items-start space-x-3 flex-1 min-w-0">
                                            <div class="flex-shrink-0 w-10 h-10 rounded-lg overflow-hidden bg-gray-100 flex items-center justify-center">
                                                @if($product->image)
                                                    <img src="{{ asset('storage/' . $product->image) }}"
                                                         alt="{{ $product->name }}"
                                                         class="w-full h-full object-cover">
                                                @else
                                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                                    </svg>
                                                @endif
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="text-sm font-semibold text-gray-900 truncate">{{ $product->name }}</div>
                                                <div class="text-xs text-gray-500 truncate">{{ Str::limit($product->description, 50) }}</div>
                                                <div class="text-xs text-gray-600 mt-1">Rp {{ number_format($product->price, 0, ',', '.') }}/{{ $product->unit }}</div>
                                            </div>
                                        </div>
                                        @if($product->is_active)
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 flex-shrink-0">Active</span>
                                        @else
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 flex-shrink-0">Inactive</span>
                                        @endif
                                    </div>

                                    <div class="mb-3">
                                        @if($product->foodCategory->parent)
                                            <div class="flex items-center gap-1 mb-2">
                                                <span class="inline-block px-2 py-1 text-xs font-semibold rounded" style="background: {{ $product->foodCategory->parent->color }}20; color: {{ $product->foodCategory->parent->color }}">{{ $product->foodCategory->parent->name }}</span>
                                                <span class="text-gray-400">›</span>
                                                <span class="inline-block px-2 py-1 text-xs font-semibold rounded" style="background: {{ $product->foodCategory->color }}20; color: {{ $product->foodCategory->color }}">{{ $product->foodCategory->name }}</span>
                                            </div>
                                        @else
                                            <span class="inline-block px-2 py-1 text-xs font-semibold rounded mb-2" style="background: {{ $product->foodCategory->color }}20; color: {{ $product->foodCategory->color }}">{{ $product->foodCategory->name }}</span>
                                        @endif
                                        <div class="text-xs text-gray-600">
                                            <span>Min: {{ $product->min_purchase }} {{ $product->unit }}</span>
                                            @if($product->max_purchase)
                                                <span class="ml-2">Max: {{ $product->max_purchase }} {{ $product->unit }}</span>
                                            @else
                                                <span class="ml-2 text-gray-400">Max: No limit</span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Actions Mobile -->
                                    <div class="flex items-center justify-between pt-2 border-t border-gray-100">
                                        <div class="flex flex-wrap gap-2">
                                            <a href="{{ route('supplier.ingredients.edit', $product) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-700 rounded-md hover:bg-blue-100 text-sm font-medium transition-colors">
                                                <i class="fas fa-edit mr-1.5 text-xs"></i>Edit
                                            </a>
                                            <button onclick="confirmDeleteIngredient({{ $product->id }}, '{{ $product->name }}')" class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-700 rounded-md hover:bg-red-100 text-sm font-medium transition-colors">
                                                <i class="fas fa-trash mr-1.5 text-xs"></i>Delete
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
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

<!-- Confirmation Modal -->
<div id="confirmModal" class="fixed inset-0 z-[100] hidden" style="display: none;">
    <!-- Background overlay - transparent -->
    <div class="fixed inset-0 transition-opacity" style="background-color: rgba(0, 0, 0, 0.5);" onclick="closeConfirmModal()"></div>

    <!-- Modal panel - centered -->
    <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none" style="left: 0; right: 0; top: 0; bottom: 0;">
        <div class="bg-white bg-opacity-70 backdrop-blur-lg rounded-lg shadow-xl max-w-md w-full border border-white border-opacity-30 pointer-events-auto" onclick="event.stopPropagation()">
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
    if (!modal) {
        console.error('Confirm modal not found');
        return;
    }

    document.getElementById('confirmMessage').textContent = `Are you sure you want to delete "${name}"? This action cannot be undone.`;
    document.getElementById('deleteForm').action = `/supplier/ingredients/${id}`;

    // Show modal
    modal.classList.remove('hidden');
    modal.style.display = 'block';

    // Disable body scroll
    document.body.style.overflow = 'hidden';
}

function closeConfirmModal() {
    const modal = document.getElementById('confirmModal');
    if (!modal) {
        return;
    }

    modal.classList.add('hidden');
    modal.style.display = 'none';
    document.getElementById('deleteForm').action = '';

    // Enable body scroll
    document.body.style.overflow = '';
}

// Close modal when clicking outside
document.getElementById('confirmModal')?.addEventListener('click', function(e) {
    if (e.target === this || e.target.classList.contains('fixed') && e.target.classList.contains('inset-0')) {
        closeConfirmModal();
    }
});

// Close modal on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeConfirmModal();
    }
});

@if(session('status'))
    document.addEventListener('DOMContentLoaded', function() {
        const status = @json(session('status'));
        if (status && status.message) {
            const alertType = status.type === 'danger' || status.type === 'error' ? 'error' : 'success';
            const alertColor = alertType === 'error' ? 'red' : 'green';

            // Create alert element
            const alert = document.createElement('div');
            alert.className = `fixed top-4 right-4 bg-${alertColor}-100 border-l-4 border-${alertColor}-500 text-${alertColor}-700 p-4 rounded-lg shadow-lg z-50 max-w-md`;
            alert.innerHTML = `
                <div class="flex items-start">
                    <i class="fas fa-${alertType === 'error' ? 'exclamation-circle' : 'check-circle'} mr-3 mt-0.5"></i>
                    <div class="flex-1">
                        <p class="font-semibold mb-1">${alertType === 'error' ? 'Error' : 'Success'}</p>
                        <p class="text-sm">${status.message}</p>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-${alertColor}-600 hover:text-${alertColor}-800">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            document.body.appendChild(alert);

            // Remove after 5 seconds
            setTimeout(() => {
                if (alert && alert.parentElement) {
                    alert.style.transition = 'opacity 0.3s ease-out';
                    alert.style.opacity = '0';
                    setTimeout(() => {
                        if (alert && alert.parentElement) {
                            alert.remove();
                        }
                    }, 300);
                }
            }, 5000);
        }
    });
@endif

@if($errors->any())
    document.addEventListener('DOMContentLoaded', function() {
        const errorMessages = @json($errors->all());
        if (errorMessages && errorMessages.length > 0) {
            const alert = document.createElement('div');
            alert.className = 'fixed top-4 right-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-lg z-50 max-w-md';
            alert.innerHTML = `
                <div class="flex items-start">
                    <i class="fas fa-exclamation-circle mr-3 mt-0.5"></i>
                    <div class="flex-1">
                        <p class="font-semibold mb-1">Validation Error</p>
                        <ul class="text-sm list-disc list-inside space-y-1">
                            ${errorMessages.map(msg => `<li>${msg}</li>`).join('')}
                        </ul>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-red-600 hover:text-red-800">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            document.body.appendChild(alert);

            setTimeout(() => {
                if (alert && alert.parentElement) {
                    alert.style.transition = 'opacity 0.3s ease-out';
                    alert.style.opacity = '0';
                    setTimeout(() => {
                        if (alert && alert.parentElement) {
                            alert.remove();
                        }
                    }, 300);
                }
            }, 7000);
        }
    });
@endif
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
