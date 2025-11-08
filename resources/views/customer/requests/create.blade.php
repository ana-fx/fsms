@extends('layouts.app')

@section('title', 'Create Custom Request - FSMS')

@section('content')
<div class="flex bg-gray-100 min-h-screen w-full overflow-x-hidden">
    @include('customer.partials.sidebar')

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
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Create Custom Request</h1>
                        <p class="mt-2 text-gray-600">Submit a custom ingredient request manually</p>
                    </div>
                </div>

                <!-- Custom Request Form -->
                <div class="bg-white rounded-lg shadow mx-4 sm:mx-6 lg:mx-8">
                    <form method="POST" action="{{ route('customer.requests.custom.store') }}" class="p-6" id="customRequestForm">
                        @csrf

                        <!-- Hidden field for selected address ID -->
                        <input type="hidden" name="selected_address_id" id="selected_address_id" value="">

                        <!-- Ingredient Information Section -->
                        <div class="mb-8">
                            <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                                <i class="fas fa-box mr-3 text-green-600"></i>
                                Ingredient Information
                            </h2>

                            <!-- Food Category -->
                            <div class="mb-6">
                                <label for="food_category_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Category <span class="text-red-500">*</span>
                                </label>
                                <select name="food_category_id" id="food_category_id" required
                                        class="w-full px-4 py-3 border {{ $errors->has('food_category_id') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('food_category_id') == $category->id ? 'selected' : '' }}
                                                style="color: {{ $category->color }}">
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('food_category_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Title -->
                            <div class="mb-6">
                                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                    Ingredient Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="title" id="title" required
                                       value="{{ old('title') }}"
                                       placeholder="e.g., Fresh Chicken Breast, Organic Rice"
                                       class="w-full px-4 py-3 border {{ $errors->has('title') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                @error('title')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="mb-6">
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                    Description
                                </label>
                                <textarea name="description" id="description" rows="3"
                                          placeholder="Add any specific details about the ingredient (brand, quality, specifications, etc.)"
                                          class="w-full px-4 py-3 border {{ $errors->has('description') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Quantity and Unit -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                        Quantity <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" name="quantity" id="quantity" required step="0.01" min="0.01"
                                           value="{{ old('quantity') }}"
                                           placeholder="0.00"
                                           class="w-full px-4 py-3 border {{ $errors->has('quantity') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                    @error('quantity')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="unit" class="block text-sm font-medium text-gray-700 mb-2">
                                        Unit <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="unit" id="unit" required
                                           value="{{ old('unit') }}"
                                           placeholder="e.g., kg, liter, pcs, box"
                                           class="w-full px-4 py-3 border {{ $errors->has('unit') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                    @error('unit')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Notes -->
                            <div class="mb-6">
                                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                    Additional Notes
                                </label>
                                <textarea name="notes" id="notes" rows="2"
                                          placeholder="Any special requirements or instructions for suppliers"
                                          class="w-full px-4 py-3 border {{ $errors->has('notes') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Needed Date -->
                            <div class="mb-6">
                                <label for="needed_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    Needed Date <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="needed_date" id="needed_date" required
                                       value="{{ old('needed_date') }}"
                                       min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                       class="w-full px-4 py-3 border {{ $errors->has('needed_date') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                @error('needed_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Please select a date in the future</p>
                            </div>
                        </div>

                        <!-- Delivery Address Section -->
                        <div class="mb-8 border-t border-gray-200 pt-8">
                            <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                                <i class="fas fa-map-marker-alt mr-3 text-green-600"></i>
                                Delivery Address
                            </h2>

                            <!-- Select Saved Address -->
                            @if($addresses && $addresses->count() > 0)
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-3">
                                        Select Saved Address
                                    </label>
                                    <div class="space-y-2 mb-4">
                                        @foreach($addresses as $address)
                                            <div class="border border-gray-200 rounded-lg p-3 cursor-pointer hover:border-green-400 transition-colors address-option {{ $address->is_default && !old('use_new_address') ? 'border-green-400 bg-green-50' : '' }}"
                                                 onclick="selectAddress({{ $address->id }}, '{{ $address->recipient_name }}', '{{ $address->recipient_phone }}', '{{ addslashes($address->delivery_address) }}', '{{ $address->city }}', '{{ $address->postal_code }}')">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex-1">
                                                        <div class="flex items-center mb-1">
                                                            @if($address->label)
                                                                <span class="font-semibold text-gray-900 mr-2">{{ $address->label }}</span>
                                                            @endif
                                                            @if($address->is_default)
                                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                                    <i class="fas fa-star mr-1"></i>Default
                                                                </span>
                                                            @endif
                                                        </div>
                                                        <p class="text-sm text-gray-700">{{ $address->recipient_name }} - {{ $address->recipient_phone }}</p>
                                                        <p class="text-xs text-gray-600">{{ $address->delivery_address }}, {{ $address->city }}</p>
                                                    </div>
                                                    <i class="fas fa-check-circle text-green-600 hidden address-check-icon"></i>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="mb-4">
                                        <label class="flex items-center cursor-pointer">
                                            <input type="radio" name="address_option" value="saved" checked
                                                   class="rounded border-gray-300 text-green-600 focus:ring-green-500"
                                                   onchange="toggleAddressForm('saved')">
                                            <span class="ml-2 text-sm text-gray-700">Use selected address</span>
                                        </label>
                                    </div>
                                    <div class="mb-6">
                                        <label class="flex items-center cursor-pointer">
                                            <input type="radio" name="address_option" value="new"
                                                   class="rounded border-gray-300 text-green-600 focus:ring-green-500"
                                                   onchange="toggleAddressForm('new')">
                                            <span class="ml-2 text-sm text-gray-700">Use new address for this order</span>
                                        </label>
                                    </div>
                                </div>
                            @endif

                            <!-- Delivery Address Form -->
                            <div id="deliveryAddressForm" class="{{ $addresses && $addresses->count() > 0 ? 'hidden' : '' }}">
                                <!-- Recipient Name -->
                                <div class="mb-6">
                                    <label for="recipient_name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Recipient Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="recipient_name" id="recipient_name"
                                           value="{{ old('recipient_name', $user->name ?? '') }}"
                                           required
                                           class="w-full px-4 py-3 border {{ $errors->has('recipient_name') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                    @error('recipient_name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Recipient Phone -->
                                <div class="mb-6">
                                    <label for="recipient_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                        Recipient Phone <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="recipient_phone" id="recipient_phone"
                                           value="{{ old('recipient_phone', $user->phone ?? '') }}"
                                           required
                                           class="w-full px-4 py-3 border {{ $errors->has('recipient_phone') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                    @error('recipient_phone')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Delivery Address -->
                                <div class="mb-6">
                                    <label for="delivery_address" class="block text-sm font-medium text-gray-700 mb-2">
                                        Delivery Address <span class="text-red-500">*</span>
                                    </label>
                                    <textarea name="delivery_address" id="delivery_address" rows="3" required
                                              class="w-full px-4 py-3 border {{ $errors->has('delivery_address') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">{{ old('delivery_address') }}</textarea>
                                    @error('delivery_address')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- City and Postal Code -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                    <div>
                                        <label for="city" class="block text-sm font-medium text-gray-700 mb-2">
                                            City <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" name="city" id="city" required
                                               value="{{ old('city') }}"
                                               class="w-full px-4 py-3 border {{ $errors->has('city') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                        @error('city')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-2">
                                            Postal Code
                                        </label>
                                        <input type="text" name="postal_code" id="postal_code"
                                               value="{{ old('postal_code') }}"
                                               class="w-full px-4 py-3 border {{ $errors->has('postal_code') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                        @error('postal_code')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Delivery Notes -->
                                <div class="mb-6">
                                    <label for="delivery_notes" class="block text-sm font-medium text-gray-700 mb-2">
                                        Delivery Notes
                                    </label>
                                    <textarea name="delivery_notes" id="delivery_notes" rows="2"
                                              placeholder="Any special delivery instructions"
                                              class="w-full px-4 py-3 border {{ $errors->has('delivery_notes') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">{{ old('delivery_notes') }}</textarea>
                                    @error('delivery_notes')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                            <a href="{{ route('customer.requests.index') }}" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                                <i class="fas fa-times mr-2"></i>Cancel
                            </a>
                            <button type="submit" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold">
                                <i class="fas fa-paper-plane mr-2"></i>Submit Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Confirmation/Review Modal -->
<div id="confirmationModal" class="fixed inset-0 z-[100] hidden" style="display: none;">
    <!-- Background overlay - transparent -->
    <div class="fixed inset-0 transition-opacity" style="background-color: rgba(0, 0, 0, 0.5);" onclick="closeConfirmationModal()"></div>

    <!-- Modal panel - centered in content area (accounting for sidebar) -->
    <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none" style="left: 0; right: 0; top: 0; bottom: 0;">
        <div class="bg-white bg-opacity-70 backdrop-blur-lg rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto border border-white border-opacity-30 pointer-events-auto" onclick="event.stopPropagation()">
        <div class="p-6">
            <div class="flex items-center mb-6">
                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-yellow-100 mr-4">
                    <i class="fas fa-check-circle text-yellow-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900">Review Your Request</h3>
            </div>
            <p class="text-gray-600 mb-6">Please review your information below to ensure all details are correct before submitting:</p>

            <!-- Review Content -->
            <div id="reviewContent" class="space-y-4 mb-6">
                <!-- Content will be populated by JavaScript -->
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                <button onclick="closeConfirmationModal()" class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors font-medium">
                    Cancel
                </button>
                <button onclick="confirmSubmit()" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium">
                    <i class="fas fa-check mr-2"></i>Confirm & Submit
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Select saved address
function selectAddress(id, name, phone, address, city, postalCode) {
    // Update hidden field
    document.getElementById('selected_address_id').value = id;

    // Update form fields
    document.getElementById('recipient_name').value = name;
    document.getElementById('recipient_phone').value = phone;
    document.getElementById('delivery_address').value = address;
    document.getElementById('city').value = city;
    document.getElementById('postal_code').value = postalCode || '';

    // Update radio button
    document.querySelector('input[name="address_option"][value="saved"]').checked = true;

    // Update UI
    document.querySelectorAll('.address-option').forEach(option => {
        option.classList.remove('border-green-400', 'bg-green-50');
        option.querySelector('.address-check-icon').classList.add('hidden');
    });

    event.currentTarget.classList.add('border-green-400', 'bg-green-50');
    event.currentTarget.querySelector('.address-check-icon').classList.remove('hidden');
}

// Toggle address form
function toggleAddressForm(option) {
    const form = document.getElementById('deliveryAddressForm');
    if (option === 'new') {
        form.classList.remove('hidden');
        // Clear selected address
        document.getElementById('selected_address_id').value = '';
        document.querySelectorAll('.address-option').forEach(option => {
            option.classList.remove('border-green-400', 'bg-green-50');
            option.querySelector('.address-check-icon').classList.add('hidden');
        });
    } else {
        form.classList.add('hidden');
        // Select default address if exists
        const defaultAddress = document.querySelector('.address-option.border-green-400');
        if (defaultAddress) {
            defaultAddress.click();
        }
    }
}

// Set minimum date to tomorrow
document.addEventListener('DOMContentLoaded', function() {
    const neededDateInput = document.getElementById('needed_date');
    if (neededDateInput && !neededDateInput.value) {
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        neededDateInput.value = tomorrow.toISOString().split('T')[0];
    }

    // Auto-select default address on page load
    const defaultAddress = document.querySelector('.address-option.border-green-400');
    if (defaultAddress && document.querySelector('input[name="address_option"][value="saved"]').checked) {
        defaultAddress.click();
    }

});

// Track if form is being submitted
let isSubmitting = false;

// Form validation and submission
document.getElementById('customRequestForm')?.addEventListener('submit', function(e) {
    // Prevent double submission
    if (isSubmitting) {
        e.preventDefault();
        return false;
    }

    // Validate required fields
    const requiredFields = {
        'food_category_id': 'Category',
        'title': 'Ingredient Name',
        'quantity': 'Quantity',
        'unit': 'Unit',
        'needed_date': 'Needed Date',
        'recipient_name': 'Recipient Name',
        'recipient_phone': 'Recipient Phone',
        'delivery_address': 'Delivery Address',
        'city': 'City'
    };

    let isValid = true;
    let missingFields = [];

    // Check if using saved address or new address
    const addressOption = document.querySelector('input[name="address_option"]:checked')?.value;
    const selectedAddressId = document.getElementById('selected_address_id').value;
    const deliveryAddressForm = document.getElementById('deliveryAddressForm');

    // If using saved address, skip delivery form validation
    const isUsingSavedAddress = addressOption === 'saved' && selectedAddressId &&
                                 (deliveryAddressForm.classList.contains('hidden') || !deliveryAddressForm);

    // Validate required fields
    for (const [fieldId, fieldName] of Object.entries(requiredFields)) {
        const field = document.getElementById(fieldId);

        // Skip delivery address fields if using saved address
        if (isUsingSavedAddress && ['recipient_name', 'recipient_phone', 'delivery_address', 'city'].includes(fieldId)) {
            continue;
        }

        if (field && field.hasAttribute('required')) {
            if (!field.value || field.value.trim() === '') {
                isValid = false;
                missingFields.push(fieldName);
                field.classList.add('border-red-500');
            } else {
                field.classList.remove('border-red-500');
            }
        }
    }

    // If validation fails, show error and prevent submission
    if (!isValid) {
        e.preventDefault();
        showErrorModal('Please fill in all required fields: ' + missingFields.join(', '));
        return false;
    }

    // If validation passes, show confirmation modal to review data
    e.preventDefault();
    isSubmitting = true;

    // Show confirmation modal with review data
    const form = this;
    showConfirmationModal(form);

    // Store form reference for submission after confirmation
    window.pendingFormSubmission = form;

    return false;
});

function showConfirmationModal(form) {
    const modal = document.getElementById('confirmationModal');
    const reviewContent = document.getElementById('reviewContent');

    if (!modal || !reviewContent) {
        console.error('Confirmation modal not found');
        return;
    }

    // Get form values
    const categorySelect = document.getElementById('food_category_id');
    const categoryName = categorySelect.options[categorySelect.selectedIndex]?.text || 'Not selected';
    const title = document.getElementById('title').value || 'N/A';
    const description = document.getElementById('description').value || 'N/A';
    const quantity = document.getElementById('quantity').value || 'N/A';
    const unit = document.getElementById('unit').value || 'N/A';
    const notes = document.getElementById('notes').value || 'N/A';
    const neededDate = document.getElementById('needed_date').value || 'N/A';

    // Get address information
    const addressOption = document.querySelector('input[name="address_option"]:checked')?.value;
    const selectedAddressId = document.getElementById('selected_address_id').value;
    const deliveryAddressForm = document.getElementById('deliveryAddressForm');
    const isUsingSavedAddress = addressOption === 'saved' && selectedAddressId &&
                                 (deliveryAddressForm.classList.contains('hidden') || !deliveryAddressForm);

    let recipientName, recipientPhone, deliveryAddress, city, postalCode, deliveryNotes;

    if (isUsingSavedAddress) {
        // Using saved address - get from form fields that were populated by selectAddress function
        // The form fields are populated even if hidden, so we can read them
        recipientName = document.getElementById('recipient_name').value || 'N/A';
        recipientPhone = document.getElementById('recipient_phone').value || 'N/A';
        deliveryAddress = document.getElementById('delivery_address').value || 'N/A';
        city = document.getElementById('city').value || 'N/A';
        postalCode = document.getElementById('postal_code').value || 'N/A';
    } else {
        // Using new address
        recipientName = document.getElementById('recipient_name').value || 'N/A';
        recipientPhone = document.getElementById('recipient_phone').value || 'N/A';
        deliveryAddress = document.getElementById('delivery_address').value || 'N/A';
        city = document.getElementById('city').value || 'N/A';
        postalCode = document.getElementById('postal_code').value || 'N/A';
    }
    deliveryNotes = document.getElementById('delivery_notes').value || 'N/A';

    // Format date
    const formattedDate = neededDate !== 'N/A' ? new Date(neededDate).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    }) : 'N/A';

    // Build review content HTML
    reviewContent.innerHTML = `
        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
            <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                <i class="fas fa-box text-green-600 mr-2"></i>Ingredient Information
            </h4>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Category:</span>
                    <span class="font-medium text-gray-900">${categoryName}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Ingredient Name:</span>
                    <span class="font-medium text-gray-900">${title}</span>
                </div>
                ${description !== 'N/A' ? `
                <div class="flex justify-between">
                    <span class="text-gray-600">Description:</span>
                    <span class="font-medium text-gray-900 text-right max-w-xs">${description}</span>
                </div>
                ` : ''}
                <div class="flex justify-between">
                    <span class="text-gray-600">Quantity:</span>
                    <span class="font-medium text-gray-900">${quantity} ${unit}</span>
                </div>
                ${notes !== 'N/A' ? `
                <div class="flex justify-between">
                    <span class="text-gray-600">Additional Notes:</span>
                    <span class="font-medium text-gray-900 text-right max-w-xs">${notes}</span>
                </div>
                ` : ''}
                <div class="flex justify-between">
                    <span class="text-gray-600">Needed Date:</span>
                    <span class="font-medium text-gray-900">${formattedDate}</span>
                </div>
            </div>
        </div>

        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
            <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                <i class="fas fa-map-marker-alt text-green-600 mr-2"></i>Delivery Address
            </h4>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Recipient Name:</span>
                    <span class="font-medium text-gray-900">${recipientName}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Recipient Phone:</span>
                    <span class="font-medium text-gray-900">${recipientPhone}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Delivery Address:</span>
                    <span class="font-medium text-gray-900 text-right max-w-xs">${deliveryAddress}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">City:</span>
                    <span class="font-medium text-gray-900">${city}</span>
                </div>
                ${postalCode !== 'N/A' ? `
                <div class="flex justify-between">
                    <span class="text-gray-600">Postal Code:</span>
                    <span class="font-medium text-gray-900">${postalCode}</span>
                </div>
                ` : ''}
                ${deliveryNotes !== 'N/A' ? `
                <div class="flex justify-between">
                    <span class="text-gray-600">Delivery Notes:</span>
                    <span class="font-medium text-gray-900 text-right max-w-xs">${deliveryNotes}</span>
                </div>
                ` : ''}
            </div>
        </div>
    `;

    // Disable body scroll
    document.body.style.overflow = 'hidden';

    // Calculate center position considering sidebar on desktop
    const isDesktop = window.innerWidth >= 1024; // lg breakpoint
    const sidebarWidth = isDesktop ? 256 : 0; // 64 * 4 = 256px (lg:ml-64)

    // Get viewport dimensions
    const viewportWidth = window.innerWidth;

    // Calculate available width (viewport minus sidebar)
    const availableWidth = viewportWidth - sidebarWidth;

    // Center modal in available content area
    const modalContainer = modal.querySelector('.fixed.inset-0.flex');
    if (modalContainer) {
        modalContainer.style.left = sidebarWidth + 'px';
        modalContainer.style.width = availableWidth + 'px';
    }

    // Show modal
    modal.classList.remove('hidden');
    modal.style.display = 'block';
}

function closeConfirmationModal() {
    const modal = document.getElementById('confirmationModal');
    if (modal) {
        // Enable body scroll
        document.body.style.overflow = '';

        // Hide modal
        modal.classList.add('hidden');
        modal.style.display = 'none';
    }

    // Reset submission flag
    isSubmitting = false;
    window.pendingFormSubmission = null;
}

function confirmSubmit() {
    // Submit form after confirmation
    if (window.pendingFormSubmission) {
        window.pendingFormSubmission.submit();
        window.pendingFormSubmission = null;
    }
}

function showErrorModal(message) {
    // Create temporary error modal
    const errorModal = document.createElement('div');
    errorModal.className = 'fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center';
    errorModal.innerHTML = `
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="flex items-center justify-center w-12 h-12 rounded-full bg-red-100 mr-4">
                        <i class="fas fa-exclamation-circle text-red-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Validation Error</h3>
                </div>
                <p class="text-gray-600 mb-6">${message}</p>
                <div class="flex justify-end">
                    <button onclick="this.parentElement.parentElement.parentElement.remove()" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium">
                        OK
                    </button>
                </div>
            </div>
        </div>
    `;
    document.body.appendChild(errorModal);

    // Auto remove after 5 seconds
    setTimeout(() => {
        if (errorModal && errorModal.parentElement) {
            errorModal.remove();
        }
    }, 5000);
}

// Close modal on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeConfirmationModal();
    }
});

// Handle window resize to recalculate modal position
let resizeTimeout;
window.addEventListener('resize', function() {
    const modal = document.getElementById('confirmationModal');
    if (modal && !modal.classList.contains('hidden')) {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(function() {
            // Recalculate modal position on resize
            const isDesktop = window.innerWidth >= 1024;
            const sidebarWidth = isDesktop ? 256 : 0;
            const viewportWidth = window.innerWidth;
            const availableWidth = viewportWidth - sidebarWidth;

            const modalContainer = modal.querySelector('.fixed.inset-0.flex');
            if (modalContainer) {
                modalContainer.style.left = sidebarWidth + 'px';
                modalContainer.style.width = availableWidth + 'px';
            }
        }, 100);
    }
});
</script>
@endpush
@endsection

