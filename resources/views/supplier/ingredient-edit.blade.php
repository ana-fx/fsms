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
                            <!-- Category (Parent) -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Category <span class="text-red-500">*</span></label>
                                <select name="parent_category_id" id="parent_category_id" required class="w-full px-4 py-2 border {{ $errors->has('parent_category_id') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                    <option value="">Select category</option>
                                    @foreach($categories as $parentCategory)
                                        <option value="{{ $parentCategory->id }}" {{ (old('parent_category_id', $currentParentCategory ? $currentParentCategory->id : '') == $parentCategory->id) ? 'selected' : '' }}>{{ $parentCategory->name }}</option>
                                    @endforeach
                                </select>
                                <p class="mt-1 text-xs text-gray-500">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Select the main category first
                                </p>
                                @error('parent_category_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <!-- Nama Barang (Sub-Category) -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Item Name <span class="text-red-500">*</span></label>
                                <select name="food_category_id" id="food_category_id" required class="w-full px-4 py-2 border {{ $errors->has('food_category_id') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                    <option value="">Select category first</option>
                                    @if($currentParentCategory && $ingredient->foodCategory)
                                        @php
                                            $subCategories = \App\Models\FoodCategory::where('parent_id', $currentParentCategory->id)->active()->ordered()->get();
                                        @endphp
                                        @foreach($subCategories as $subCategory)
                                            <option value="{{ $subCategory->id }}" {{ (old('food_category_id', $ingredient->food_category_id) == $subCategory->id) ? 'selected' : '' }}>{{ $subCategory->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <p class="mt-1 text-xs text-gray-500">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Select item name (e.g., Beras, Jagung, etc.)
                                </p>
                                @error('food_category_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <!-- Sub Nama (Optional) -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Additional Name <span class="text-gray-500 text-xs">(Optional)</span></label>
                                <input type="text" name="sub_name" id="sub_name" value="{{ old('sub_name', $ingredient->sub_name) }}" class="w-full px-4 py-2 border {{ $errors->has('sub_name') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="e.g., Merah, Premium, 2 KARUNG">
                                <p class="mt-1 text-xs text-gray-500">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Add your own additional name (e.g., "Merah", "Premium", "2 KARUNG")
                                </p>
                                @error('sub_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <!-- Display Final Name Preview -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Final Name Preview</label>
                                <div id="name_preview" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-600">
                                    <span class="text-sm">{{ $ingredient->name }}</span>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    This is how the ingredient name will appear
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Price <span class="text-red-500">*</span></label>
                                @php
                                    // Get max_price from category (sub-category), not from ingredient
                                    $categoryMaxPrice = $ingredient->foodCategory ? $ingredient->foodCategory->max_price : null;
                                @endphp
                                <input type="number" step="0.01" name="price" id="price" value="{{ old('price', $ingredient->price) }}" required
                                       @if($categoryMaxPrice && $categoryMaxPrice > 0)max="{{ $categoryMaxPrice }}"@endif
                                       data-max-price="{{ $categoryMaxPrice ? (float)$categoryMaxPrice : 0 }}"
                                       data-category-name="{{ $ingredient->foodCategory ? $ingredient->foodCategory->name : '' }}"
                                       class="w-full px-4 py-2 border {{ $errors->has('price') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="Base price per unit">
                                @if($categoryMaxPrice && $categoryMaxPrice > 0)
                                    <p class="mt-1 text-xs text-blue-600">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Maximum price limit for category "{{ $ingredient->foodCategory->name }}": <strong>Rp {{ number_format($categoryMaxPrice, 0, ',', '.') }}</strong> (set by administrator)
                                    </p>
                                @endif
                                @error('price')
                                    <p class="mt-1 text-sm text-red-600 font-semibold">{{ $message }}</p>
                                @enderror
                                @if(session('error'))
                                    <p class="mt-1 text-sm text-red-600 font-semibold">{{ session('error') }}</p>
                                @endif
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

<!-- Error Modal -->
<div id="errorModal" class="fixed inset-0 z-[100] hidden" style="display: none;">
    <!-- Background overlay -->
    <div class="fixed inset-0 transition-opacity" style="background-color: rgba(0, 0, 0, 0.5); left: 0; right: 0; top: 0; bottom: 0;" onclick="closeErrorModal()"></div>

    <!-- Modal panel -->
    <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none" id="errorModalContainer">
        <div class="bg-white bg-opacity-95 backdrop-blur-lg rounded-lg shadow-xl max-w-md w-full border border-red-200 border-opacity-50 pointer-events-auto transform transition-all" onclick="event.stopPropagation()">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-red-100 flex items-center justify-center mr-3">
                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Validation Error</h3>
                </div>
                <div class="mb-6">
                    <p id="errorMessage" class="text-sm text-gray-700"></p>
                </div>
                <div class="flex justify-end">
                    <button onclick="closeErrorModal()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium">
                        OK
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div id="confirmModal" class="fixed inset-0 z-[100] hidden" style="display: none;">
    <!-- Background overlay -->
    <div class="fixed inset-0 transition-opacity" style="background-color: rgba(0, 0, 0, 0.5); left: 0; right: 0; top: 0; bottom: 0;" onclick="closeConfirmModal()"></div>

    <!-- Modal panel -->
    <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none" id="confirmModalContainer">
        <div class="bg-white bg-opacity-95 backdrop-blur-lg rounded-lg shadow-xl max-w-lg w-full border border-white border-opacity-30 pointer-events-auto transform transition-all" onclick="event.stopPropagation()">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-green-100 flex items-center justify-center mr-3">
                        <i class="fas fa-check-circle text-green-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Confirm Update</h3>
                </div>
                <div class="mb-6">
                    <p id="confirmMessage" class="text-sm text-gray-700 whitespace-pre-line"></p>
                </div>
                <div class="flex justify-end space-x-3">
                    <button onclick="closeConfirmModal()" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                        Cancel
                    </button>
                    <button onclick="executeConfirmAction()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium">
                        Confirm
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Auto-Correct Price Modal -->
<div id="autoCorrectModal" class="fixed inset-0 z-[100] hidden" style="display: none;">
    <!-- Background overlay -->
    <div class="fixed inset-0 transition-opacity" style="background-color: rgba(0, 0, 0, 0.5); left: 0; right: 0; top: 0; bottom: 0;" onclick="handleAutoCorrectModalClose(false)"></div>

    <!-- Modal panel -->
    <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none" id="autoCorrectModalContainer">
        <div class="bg-white bg-opacity-95 backdrop-blur-lg rounded-lg shadow-xl max-w-md w-full border border-yellow-200 border-opacity-50 pointer-events-auto transform transition-all" onclick="event.stopPropagation()">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center mr-3">
                        <i class="fas fa-exclamation-circle text-yellow-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Price Exceeds Limit</h3>
                </div>
                <div class="mb-6">
                    <p id="autoCorrectMessage" class="text-sm text-gray-700"></p>
                    <p class="text-sm text-gray-600 mt-2">Do you want to set it to the maximum allowed price?</p>
                </div>
                <div class="flex justify-end space-x-3">
                    <button onclick="handleAutoCorrectModalClose(false)" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                        No
                    </button>
                    <button onclick="handleAutoCorrectModalClose(true)" class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors font-medium">
                        Yes, Set to Maximum
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Category and item name dropdowns
    const parentCategorySelect = document.getElementById('parent_category_id');
    const itemNameSelect = document.getElementById('food_category_id');
    const subNameInput = document.getElementById('sub_name');
    const namePreview = document.getElementById('name_preview');

    // Sub-categories data from backend (includes max_price for each category)
    const subCategoriesByParent = @json($subCategoriesByParent ?? []);

    // Price and purchase inputs
    const priceInput = document.getElementById('price');
    const minPurchaseInput = document.getElementById('min_purchase');
    const maxPurchaseInput = document.getElementById('max_purchase');
    const maxPurchaseInfo = document.getElementById('maxPurchaseInfo');
    const maxPurchaseValue = document.getElementById('maxPurchaseValue');
    const maxTotalPrice = document.getElementById('maxTotalPrice');
    const unitInput = document.querySelector('input[name="unit"]');
    
    // Get initial max_price from current category
    let currentMaxPrice = 0;
    const maxPriceAttr = priceInput.getAttribute('data-max-price');
    if (maxPriceAttr && maxPriceAttr !== '' && maxPriceAttr !== '0') {
        currentMaxPrice = parseFloat(maxPriceAttr);
    }
    
    // Function to update max_price based on selected category
    function updateMaxPriceFromCategory() {
        const selectedCategoryId = parseInt(itemNameSelect.value);
        if (selectedCategoryId) {
            // Find the selected category in subCategoriesByParent
            for (const parentId in subCategoriesByParent) {
                const subCategories = subCategoriesByParent[parentId];
                const selectedCategory = subCategories.find(cat => cat.id === selectedCategoryId);
                if (selectedCategory && selectedCategory.max_price) {
                    currentMaxPrice = parseFloat(selectedCategory.max_price);
                    // Update data attribute and HTML max attribute
                    priceInput.setAttribute('data-max-price', currentMaxPrice);
                    priceInput.setAttribute('max', currentMaxPrice);
                    // Update validation
                    validatePriceLimit();
                    return;
                }
            }
        }
        // If no max_price found, reset to 0
        currentMaxPrice = 0;
        priceInput.setAttribute('data-max-price', '0');
        priceInput.removeAttribute('max');
        validatePriceLimit();
    }

    // Update item name dropdown based on selected parent category
    function updateItemNameDropdown() {
        const parentId = parseInt(parentCategorySelect.value);
        const subCategories = subCategoriesByParent[parentId] || [];

        // Store current selected value
        const currentValue = itemNameSelect.value;

        // Clear existing options
        itemNameSelect.innerHTML = '<option value="">Select item name</option>';

        if (parentId && subCategories.length > 0) {
            // Add options for sub-categories
            subCategories.forEach(function(subCategory) {
                const option = document.createElement('option');
                option.value = subCategory.id;
                option.textContent = subCategory.name;
                itemNameSelect.appendChild(option);
            });

            // Restore selected value if it exists in the new options
            if (currentValue) {
                itemNameSelect.value = currentValue;
            }
        }

        // Update name preview
        updateNamePreview();
    }

    // Update name preview based on selected item and sub name
    function updateNamePreview() {
        const selectedOption = itemNameSelect.options[itemNameSelect.selectedIndex];
        const itemName = selectedOption && selectedOption.value ? selectedOption.textContent : '';
        const subName = subNameInput ? subNameInput.value.trim() : '';

        if (itemName && itemName !== 'Select item name' && itemName !== 'Select category first') {
            const finalName = subName ? subName + ' ' + itemName : itemName;
            namePreview.innerHTML = '<span class="text-sm font-medium text-gray-900">' + finalName + '</span>';
        } else {
            namePreview.innerHTML = '<span class="text-sm text-gray-500">Name will appear here...</span>';
        }
    }

    // Event listeners for category and name updates
    if (parentCategorySelect) {
        parentCategorySelect.addEventListener('change', updateItemNameDropdown);
    }

    if (itemNameSelect) {
        itemNameSelect.addEventListener('change', function() {
            updateNamePreview();
            updateMaxPriceFromCategory(); // Update max_price when category changes
        });
    }

    if (subNameInput) {
        subNameInput.addEventListener('input', updateNamePreview);
        subNameInput.addEventListener('change', updateNamePreview);
    }

    // Initialize on page load
    if (parentCategorySelect && parentCategorySelect.value) {
        updateItemNameDropdown();
    }
    updateNamePreview();

    // Initialize max_price on page load
    updateMaxPriceFromCategory();

    // Price limit warning
    function validatePriceLimit() {
        const price = parseFloat(priceInput.value) || 0;

        if (currentMaxPrice > 0 && price > currentMaxPrice) {
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
                                Maximum price limit: <strong>Rp ${currentMaxPrice.toLocaleString('id-ID')}</strong>
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
        priceInput.addEventListener('blur', function() {
            validatePriceLimit();
            // If price exceeds max, show auto-correct modal
            const price = parseFloat(priceInput.value) || 0;
            if (currentMaxPrice > 0 && price > currentMaxPrice) {
                showAutoCorrectModal(price, currentMaxPrice);
            }
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

    // Modal Functions - make them available globally
    window.showErrorModal = function(message) {
        const modal = document.getElementById('errorModal');
        const messageEl = document.getElementById('errorMessage');
        const container = document.getElementById('errorModalContainer');

        if (modal && messageEl && container) {
            messageEl.innerHTML = message;

            // Calculate center position considering sidebar on desktop
            const isDesktop = window.innerWidth >= 1024;
            const sidebarWidth = isDesktop ? 256 : 0;
            const viewportWidth = window.innerWidth;
            const availableWidth = viewportWidth - sidebarWidth;

            container.style.left = sidebarWidth + 'px';
            container.style.width = availableWidth + 'px';

            modal.classList.remove('hidden');
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        }
    };

    window.closeErrorModal = function() {
        const modal = document.getElementById('errorModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }
    };

    let confirmCallback = null;

    window.showConfirmModal = function(message, callback) {
        const modal = document.getElementById('confirmModal');
        const messageEl = document.getElementById('confirmMessage');
        const container = document.getElementById('confirmModalContainer');

        if (modal && messageEl && container) {
            messageEl.innerHTML = message;
            confirmCallback = callback;

            // Calculate center position considering sidebar on desktop
            const isDesktop = window.innerWidth >= 1024;
            const sidebarWidth = isDesktop ? 256 : 0;
            const viewportWidth = window.innerWidth;
            const availableWidth = viewportWidth - sidebarWidth;

            container.style.left = sidebarWidth + 'px';
            container.style.width = availableWidth + 'px';

            modal.classList.remove('hidden');
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        }
    };

    window.closeConfirmModal = function() {
        const modal = document.getElementById('confirmModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.style.display = 'none';
            confirmCallback = null;
            document.body.style.overflow = '';
        }
    };

    window.executeConfirmAction = function() {
        if (confirmCallback) {
            confirmCallback();
        }
        window.closeConfirmModal();
    };

    function showAutoCorrectModal(currentPrice, maxPrice) {
        const modal = document.getElementById('autoCorrectModal');
        const messageEl = document.getElementById('autoCorrectMessage');
        const container = document.getElementById('autoCorrectModalContainer');

        if (modal && messageEl && container) {
            messageEl.innerHTML = `Price exceeds maximum limit of <strong>Rp ${maxPrice.toLocaleString('id-ID')}</strong>.<br>Your input: <strong>Rp ${currentPrice.toLocaleString('id-ID')}</strong>`;

            // Calculate center position considering sidebar on desktop
            const isDesktop = window.innerWidth >= 1024;
            const sidebarWidth = isDesktop ? 256 : 0;
            const viewportWidth = window.innerWidth;
            const availableWidth = viewportWidth - sidebarWidth;

            container.style.left = sidebarWidth + 'px';
            container.style.width = availableWidth + 'px';

            modal.classList.remove('hidden');
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        }
    }

    function closeAutoCorrectModal(accept) {
        const modal = document.getElementById('autoCorrectModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.style.display = 'none';
            document.body.style.overflow = '';

            if (accept && priceInput && maxPrice > 0) {
                priceInput.value = maxPrice;
                validatePriceLimit();
                updateMaxPurchaseInfo();
            } else if (!accept && priceInput) {
                priceInput.focus();
            }
        }
    }

    // Wrapper function for onclick handlers
    window.handleAutoCorrectModalClose = function(accept) {
        closeAutoCorrectModal(accept);
    };

    // Handle ESC key to close modals
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            window.closeErrorModal();
            window.closeConfirmModal();
            closeAutoCorrectModal(false);
        }
    });

    // Handle window resize to recalculate modal position
    window.addEventListener('resize', function() {
        const errorModal = document.getElementById('errorModal');
        const confirmModal = document.getElementById('confirmModal');
        const autoCorrectModal = document.getElementById('autoCorrectModal');

        if (errorModal && errorModal.style.display === 'block') {
            window.showErrorModal(document.getElementById('errorMessage').innerHTML);
        }
        if (confirmModal && confirmModal.style.display === 'block') {
            window.showConfirmModal(document.getElementById('confirmMessage').innerHTML, confirmCallback);
        }
        if (autoCorrectModal && autoCorrectModal.style.display === 'block') {
            const messageEl = document.getElementById('autoCorrectMessage');
            if (messageEl) {
                const price = parseFloat(priceInput.value) || 0;
                showAutoCorrectModal(price, maxPrice);
            }
        }
    });

    // Form submission notification
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const price = parseFloat(priceInput.value) || 0;
            const maxPurchase = parseInt(maxPurchaseInput.value) || 0;
            const minPurchase = parseInt(minPurchaseInput.value) || 0;
            const unit = unitInput.value || '';

            // CRITICAL: Get max_price from selected category
            const selectedCategoryId = parseInt(itemNameSelect.value);
            let categoryMaxPrice = 0;
            if (selectedCategoryId) {
                // Find max_price from selected category
                for (const parentId in subCategoriesByParent) {
                    const subCategories = subCategoriesByParent[parentId];
                    const selectedCategory = subCategories.find(cat => cat.id === selectedCategoryId);
                    if (selectedCategory && selectedCategory.max_price) {
                        categoryMaxPrice = parseFloat(selectedCategory.max_price);
                        break;
                    }
                }
            }

            // Validate price against max_price limit - STRICT VALIDATION
            if (categoryMaxPrice > 0) {
                const inputPrice = parseFloat(price) || 0;
                const maxAllowed = parseFloat(categoryMaxPrice) || 0;

                // Double check: compare with exact precision
                if (inputPrice > maxAllowed) {
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();
                    window.showErrorModal(`Price cannot exceed the maximum price limit of Rp ${maxAllowed.toLocaleString('id-ID')} set by administrator!<br><br>Your input: <strong>Rp ${inputPrice.toLocaleString('id-ID')}</strong><br>Maximum allowed: <strong>Rp ${maxAllowed.toLocaleString('id-ID')}</strong><br><br>Please adjust your price to be within the allowed limit.`);
                    priceInput.focus();
                    priceInput.select();
                    return false;
                }
            }

            // Validate max_purchase >= min_purchase if max_purchase is set
            if (maxPurchase > 0 && minPurchase > 0 && maxPurchase < minPurchase) {
                e.preventDefault();
                window.showErrorModal('Maximum purchase must be greater than or equal to minimum purchase!');
                return false;
            }

            // Get final name
            const selectedOption = itemNameSelect.options[itemNameSelect.selectedIndex];
            const itemName = selectedOption && selectedOption.value ? selectedOption.textContent : '';
            const subName = subNameInput ? subNameInput.value.trim() : '';
            const finalName = itemName ? (subName ? subName + ' ' + itemName : itemName) : 'N/A';

            // Show confirmation modal
            let message = `Ingredient will be updated with:<br>` +
                `• Name: <strong>${finalName}</strong><br>` +
                `• Price: <strong>Rp ${price.toLocaleString('id-ID')}</strong><br>`;

            // Get max_price for confirmation message
            let confirmMaxPrice = 0;
            if (selectedCategoryId) {
                for (const parentId in subCategoriesByParent) {
                    const subCategories = subCategoriesByParent[parentId];
                    const selectedCategory = subCategories.find(cat => cat.id === selectedCategoryId);
                    if (selectedCategory && selectedCategory.max_price) {
                        confirmMaxPrice = parseFloat(selectedCategory.max_price);
                        break;
                    }
                }
            }
            if (confirmMaxPrice > 0) {
                const categoryName = itemNameSelect.options[itemNameSelect.selectedIndex].textContent;
                message += `• Maximum Price Limit for "${categoryName}": <strong>Rp ${confirmMaxPrice.toLocaleString('id-ID')}</strong> (set by admin)<br>`;
            }

            message += `• Minimum Purchase: <strong>${minPurchase} ${unit}</strong><br>`;

            if (maxPurchase > 0) {
                const maxTotal = price * maxPurchase;
                message += `• Maximum Purchase: <strong>${maxPurchase} ${unit}</strong><br>` +
                    `• Maximum Total Price: <strong>Rp ${maxTotal.toLocaleString('id-ID')}</strong><br><br>` +
                    `Customers will not be able to purchase more than ${maxPurchase} ${unit} per order.`;
            }

            // Validate that category and item name are selected
            if (!parentCategorySelect.value || !itemNameSelect.value) {
                e.preventDefault();
                window.showErrorModal('Please select both Category and Item Name before submitting!');
                return false;
            }

            e.preventDefault();
            window.showConfirmModal(message, function() {
                form.submit();
            });
            return false;
        });
    }
});
</script>
@endpush
@endsection

