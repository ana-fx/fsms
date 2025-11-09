@extends('layouts.app')

@section('title', 'Edit Ingredient - FSMS')

@section('content')
<div class="flex bg-gray-100 min-h-screen w-full overflow-x-hidden">
    @include('supplier.partials.sidebar')

    <button id="openSidebar" class="lg:hidden fixed top-4 left-4 z-50 p-2 bg-white rounded-lg shadow-lg hover:bg-gray-100 transition-colors">
        <i class="fas fa-bars text-gray-700 text-xl"></i>
    </button>

    <div class="w-full lg:ml-64 transition-all duration-300">
        <div class="flex-1 bg-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900">Edit Ingredient</h1>
                    <p class="text-gray-600 mt-2">Update ingredient details</p>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <form action="{{ route('supplier.ingredients.update', $ingredient) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                                <input type="text" name="name" value="{{ old('name', $ingredient->name) }}" required class="w-full px-4 py-2 border {{ $errors->has('name') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                                <select name="food_category_id" required class="w-full px-4 py-2 border {{ $errors->has('food_category_id') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                    <option value="">Select category</option>
                                    @foreach($categories as $parentCategory)
                                        @if($parentCategory->children->count() > 0)
                                            <optgroup label="{{ $parentCategory->name }}">
                                                @foreach($parentCategory->children as $subCategory)
                                                    <option value="{{ $subCategory->id }}" {{ (old('food_category_id', $ingredient->food_category_id) == $subCategory->id) ? 'selected' : '' }}>{{ $subCategory->name }}</option>
                                                @endforeach
                                            </optgroup>
                                        @else
                                            <option value="{{ $parentCategory->id }}" {{ (old('food_category_id', $ingredient->food_category_id) == $parentCategory->id) ? 'selected' : '' }}>{{ $parentCategory->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('food_category_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Price <span class="text-red-500">*</span></label>
                                <input type="number" step="0.01" name="price" id="price" value="{{ old('price', $ingredient->price) }}" required
                                       max="{{ $ingredient->max_price ?? '' }}"
                                       data-max-price="{{ $ingredient->max_price ?? '' }}"
                                       class="w-full px-4 py-2 border {{ $errors->has('price') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="Base price per unit">
                                @if($ingredient->max_price)
                                    <p class="mt-1 text-xs text-blue-600">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Maximum price limit: <strong>Rp {{ number_format($ingredient->max_price, 0, ',', '.') }}</strong> (set by administrator)
                                    </p>
                                @endif
                                @error('price')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Unit</label>
                                <input type="text" name="unit" value="{{ old('unit', $ingredient->unit) }}" required class="w-full px-4 py-2 border {{ $errors->has('unit') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                @error('unit')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Stock <span class="text-red-500">*</span></label>
                                <input type="number" name="stock" value="{{ old('stock', $ingredient->stock) }}" required class="w-full px-4 py-2 border {{ $errors->has('stock') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="Available stock">
                                @error('stock')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Minimum Purchase <span class="text-red-500">*</span></label>
                                <input type="number" name="min_purchase" id="min_purchase" value="{{ old('min_purchase', $ingredient->min_purchase) }}" required class="w-full px-4 py-2 border {{ $errors->has('min_purchase') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="Minimum quantity per order">
                                @error('min_purchase')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Maximum Purchase <span class="text-gray-500 text-xs">(Optional)</span></label>
                                <input type="number" name="max_purchase" id="max_purchase" value="{{ old('max_purchase', $ingredient->max_purchase) }}" class="w-full px-4 py-2 border {{ $errors->has('max_purchase') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="Maximum quantity per order (leave empty for no limit)">
                                <p class="mt-1 text-xs text-gray-500">Set maximum purchase limit to control order quantity</p>
                                @error('max_purchase')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <!-- Max Purchase Information -->
                        <div id="maxPurchaseInfo" class="hidden bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                            <div class="flex items-start">
                                <i class="fas fa-info-circle text-blue-600 mt-0.5 mr-3"></i>
                                <div class="flex-1">
                                    <h4 class="text-sm font-semibold text-blue-900 mb-1">Maximum Purchase Information</h4>
                                    <p class="text-sm text-blue-800">
                                        Maximum purchase: <span id="maxPurchaseValue" class="font-semibold"></span>
                                    </p>
                                    <p class="text-sm text-blue-800 mt-1">
                                        Maximum total price: <span id="maxTotalPrice" class="font-semibold text-green-600"></span>
                                    </p>
                                    <p class="text-xs text-blue-700 mt-2">
                                        <i class="fas fa-lightbulb mr-1"></i>
                                        Customers will not be able to purchase more than this limit per order.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea name="description" rows="4" class="w-full px-4 py-2 border {{ $errors->has('description') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">{{ old('description', $ingredient->description) }}</textarea>
                            @error('description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Image (optional)</label>
                            <input type="file" name="image" accept="image/*" class="w-full px-4 py-2 border {{ $errors->has('image') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            @if($ingredient->image)
                                <p class="text-xs text-gray-500 mt-1">Current: <a href="{{ asset('storage/'.$ingredient->image) }}" class="text-green-600 hover:text-green-800" target="_blank">View</a></p>
                            @endif
                            @error('image')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div class="flex items-center justify-between">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="is_active" value="1" class="h-4 w-4 text-green-600 border-gray-300 rounded" {{ old('is_active', $ingredient->is_active) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700">Active</span>
                            </label>
                            <div class="space-x-3">
                                <a href="{{ route('supplier.ingredients') }}" class="px-5 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</a>
                                <button type="submit" class="px-5 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Save Changes</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const priceInput = document.getElementById('price');
    const minPurchaseInput = document.getElementById('min_purchase');
    const maxPurchaseInput = document.getElementById('max_purchase');
    const maxPurchaseInfo = document.getElementById('maxPurchaseInfo');
    const maxPurchaseValue = document.getElementById('maxPurchaseValue');
    const maxTotalPrice = document.getElementById('maxTotalPrice');
    const unitInput = document.querySelector('input[name="unit"]');
    const maxPrice = parseFloat(priceInput.getAttribute('data-max-price')) || 0;

    // Price limit warning
    function validatePriceLimit() {
        const price = parseFloat(priceInput.value) || 0;

        if (maxPrice > 0 && price > maxPrice) {
            priceInput.classList.add('border-red-500');
            priceInput.classList.remove('border-gray-300');

            // Show warning
            let warning = document.getElementById('priceLimitWarning');
            if (!warning) {
                warning = document.createElement('div');
                warning.id = 'priceLimitWarning';
                warning.className = 'mt-2 p-3 bg-red-50 border border-red-200 rounded-lg';
                warning.innerHTML = `
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-red-600 mt-0.5 mr-2"></i>
                        <div>
                            <p class="text-sm font-semibold text-red-900">Price Exceeds Maximum Limit!</p>
                            <p class="text-xs text-red-700 mt-1">
                                Maximum price limit: <strong>Rp ${maxPrice.toLocaleString('id-ID')}</strong>
                            </p>
                            <p class="text-xs text-red-700">
                                Your price: <strong>Rp ${price.toLocaleString('id-ID')}</strong>
                            </p>
                        </div>
                    </div>
                `;
                priceInput.parentElement.appendChild(warning);
            } else {
                warning.querySelector('.text-red-700:last-child').innerHTML =
                    `Your price: <strong>Rp ${price.toLocaleString('id-ID')}</strong>`;
            }
        } else {
            priceInput.classList.remove('border-red-500');
            priceInput.classList.add('border-gray-300');

            // Remove warning
            const warning = document.getElementById('priceLimitWarning');
            if (warning) {
                warning.remove();
            }
        }
    }

    function updateMaxPurchaseInfo() {
        const price = parseFloat(priceInput.value) || 0;
        const maxPurchase = parseInt(maxPurchaseInput.value) || 0;
        const unit = unitInput.value || '';

        if (maxPurchase > 0 && price > 0) {
            const maxTotal = price * maxPurchase;
            maxPurchaseValue.textContent = maxPurchase + ' ' + unit;
            maxTotalPrice.textContent = 'Rp ' + maxTotal.toLocaleString('id-ID');
            maxPurchaseInfo.classList.remove('hidden');
        } else {
            maxPurchaseInfo.classList.add('hidden');
        }
    }

    // Add event listeners for price limit validation
    if (priceInput) {
        priceInput.addEventListener('input', function() {
            validatePriceLimit();
            updateMaxPurchaseInfo();
        });
        priceInput.addEventListener('change', function() {
            validatePriceLimit();
            updateMaxPurchaseInfo();
        });
    }

    // Add event listeners for max purchase
    if (maxPurchaseInput) {
        maxPurchaseInput.addEventListener('input', updateMaxPurchaseInfo);
        maxPurchaseInput.addEventListener('change', updateMaxPurchaseInfo);
    }

    if (unitInput) {
        unitInput.addEventListener('input', updateMaxPurchaseInfo);
        unitInput.addEventListener('change', updateMaxPurchaseInfo);
    }

    // Initial validation
    validatePriceLimit();
    updateMaxPurchaseInfo();

    // Form submission notification
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const price = parseFloat(priceInput.value) || 0;
            const maxPurchase = parseInt(maxPurchaseInput.value) || 0;
            const minPurchase = parseInt(minPurchaseInput.value) || 0;
            const unit = unitInput.value || '';

            // Validate price against max_price limit
            if (maxPrice > 0 && price > maxPrice) {
                e.preventDefault();
                alert(`Price cannot exceed the maximum price limit of Rp ${maxPrice.toLocaleString('id-ID')} set by administrator!`);
                priceInput.focus();
                return false;
            }

            // Validate max_purchase >= min_purchase if max_purchase is set
            if (maxPurchase > 0 && minPurchase > 0 && maxPurchase < minPurchase) {
                e.preventDefault();
                alert('Maximum purchase must be greater than or equal to minimum purchase!');
                return false;
            }

            // Show confirmation notification
            let message = `Ingredient will be updated with:\n` +
                `- Price: Rp ${price.toLocaleString('id-ID')}\n`;

            if (maxPrice > 0) {
                message += `- Maximum Price Limit: Rp ${maxPrice.toLocaleString('id-ID')} (set by admin)\n`;
            }

            message += `- Minimum Purchase: ${minPurchase} ${unit}\n`;

            if (maxPurchase > 0) {
                const maxTotal = price * maxPurchase;
                message += `- Maximum Purchase: ${maxPurchase} ${unit}\n` +
                    `- Maximum Total Price: Rp ${maxTotal.toLocaleString('id-ID')}\n\n` +
                    `Customers will not be able to purchase more than ${maxPurchase} ${unit} per order.`;
            }

            if (!confirm(message)) {
                e.preventDefault();
                return false;
            }
        });
    }
});
</script>
@endpush
@endsection

