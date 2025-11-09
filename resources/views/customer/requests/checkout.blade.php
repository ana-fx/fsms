@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="flex bg-gray-100 min-h-screen w-full overflow-x-hidden">
    @include('customer.partials.sidebar')

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
                    <div class="flex items-center">
                        <a href="{{ route('customer.cart') }}" class="text-gray-500 hover:text-gray-700 mr-4">
                            <i class="fas fa-arrow-left text-xl"></i>
                        </a>
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">Checkout</h1>
                            <p class="mt-2 text-gray-600">Complete your order by providing delivery address</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Delivery Address Form -->
                    <div class="lg:col-span-2 lg:order-1">
                        <div class="bg-white rounded-lg shadow">
                            <form method="POST" action="{{ isset($request) && $request ? route('customer.requests.update', $request) : route('customer.requests.store') }}" class="p-6" id="checkoutForm">
                                @if(isset($request) && $request)
                                    @method('PUT')
                                @endif
                                @csrf

                                <!-- Hidden field for selected address ID -->
                                <input type="hidden" name="selected_address_id" id="selected_address_id" value="">

                                <h2 class="text-xl font-bold text-gray-900 mb-6">Delivery Address</h2>

                                <!-- Select Saved Address -->
                                @if(isset($addresses) && $addresses->count() > 0)
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
                                <div id="deliveryAddressForm" class="{{ isset($addresses) && $addresses->count() > 0 ? 'hidden' : '' }}">
                                    <!-- Recipient Name -->
                                    <div class="mb-6">
                                        <label for="recipient_name" class="block text-sm font-medium text-gray-700 mb-2">
                                            Recipient Name <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" name="recipient_name" id="recipient_name"
                                               value="{{ old('recipient_name', isset($request) && $request ? $request->recipient_name : ($defaultAddress->recipient_name ?? $user->name ?? '')) }}"
                                               class="w-full px-3 py-2 border {{ $errors->has('recipient_name') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                               placeholder="Enter recipient name" data-required="true">
                                        @error('recipient_name')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Recipient Phone -->
                                    <div class="mb-6">
                                        <label for="recipient_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                            Phone Number <span class="text-red-500">*</span>
                                        </label>
                                        <input type="tel" name="recipient_phone" id="recipient_phone"
                                               value="{{ old('recipient_phone', isset($request) && $request ? $request->recipient_phone : ($defaultAddress->recipient_phone ?? '')) }}"
                                               class="w-full px-3 py-2 border {{ $errors->has('recipient_phone') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                               placeholder="Enter phone number" data-required="true">
                                        @error('recipient_phone')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Delivery Address -->
                                    <div class="mb-6">
                                        <label for="delivery_address" class="block text-sm font-medium text-gray-700 mb-2">
                                            Delivery Address <span class="text-red-500">*</span>
                                        </label>
                                        <textarea name="delivery_address" id="delivery_address" rows="3"
                                                  class="w-full px-3 py-2 border {{ $errors->has('delivery_address') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                                  placeholder="Enter complete delivery address" data-required="true">{{ old('delivery_address', isset($request) && $request ? $request->delivery_address : ($defaultAddress->delivery_address ?? '')) }}</textarea>
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
                                            <input type="text" name="city" id="city"
                                                   value="{{ old('city', isset($request) && $request ? $request->city : ($defaultAddress->city ?? '')) }}"
                                                   class="w-full px-3 py-2 border {{ $errors->has('city') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                                   placeholder="Enter city" data-required="true">
                                            @error('city')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-2">
                                                Postal Code
                                            </label>
                                            <input type="text" name="postal_code" id="postal_code"
                                                   value="{{ old('postal_code', isset($request) && $request ? $request->postal_code : ($defaultAddress->postal_code ?? '')) }}"
                                                   class="w-full px-3 py-2 border {{ $errors->has('postal_code') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                                   placeholder="Enter postal code">
                                            @error('postal_code')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Needed Date -->
                                <div class="mb-6">
                                    <label for="needed_date_display" class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-calendar-alt mr-2 text-green-600"></i>
                                        Needed Date <span class="text-red-500">*</span>
                                    </label>
                                    <!-- Hidden input for form submission -->
                                    <input type="hidden" name="needed_date" id="needed_date" value="{{ old('needed_date', isset($request) && $request ? $request->needed_date->format('Y-m-d') : '') }}" required>

                                    <!-- Display input (readonly, triggers calendar modal) -->
                                    <div class="relative">
                                        <input type="text" id="needed_date_display" readonly
                                               value="{{ old('needed_date', isset($request) && $request ? $request->needed_date->format('Y-m-d') : '') }}"
                                               class="w-full px-4 py-3 pr-12 border {{ $errors->has('needed_date') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white font-medium text-gray-900 hover:border-green-400 transition-colors cursor-pointer"
                                               onclick="toggleCalendar()"
                                               placeholder="Select delivery date" required>
                                        <i class="fas fa-calendar-check absolute right-4 top-1/2 transform -translate-y-1/2 text-green-600 pointer-events-none"></i>
                                    </div>
                                    @error('needed_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Calendar Modal -->
                                <div id="calendarModal" class="fixed inset-0 z-[100] hidden" style="display: none;">
                                    <!-- Background overlay - transparent -->
                                    <div class="fixed inset-0 bg-transparent transition-opacity" onclick="toggleCalendar()"></div>
                                    
                                    <!-- Modal panel - centered in content area (accounting for sidebar) -->
                                    <div class="fixed inset-0 flex items-center justify-center p-2 sm:p-4 pointer-events-none" style="left: 0; right: 0; top: 0; bottom: 0;">
                                        <div class="relative bg-white rounded-xl shadow-2xl max-w-sm w-full overflow-hidden pointer-events-auto" onclick="event.stopPropagation()">
                                            <!-- Calendar Header -->
                                            <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4 flex justify-between items-center">
                                                <button onclick="changeMonth(-1)" class="text-white hover:bg-white hover:bg-opacity-20 rounded-lg p-2 transition-colors">
                                                    <i class="fas fa-chevron-left"></i>
                                                </button>
                                                <h3 id="calendarMonthYear" class="text-white font-bold text-lg">Month Year</h3>
                                                <button onclick="changeMonth(1)" class="text-white hover:bg-white hover:bg-opacity-20 rounded-lg p-2 transition-colors">
                                                    <i class="fas fa-chevron-right"></i>
                                                </button>
                                            </div>

                                            <!-- Calendar Body -->
                                            <div class="p-4">
                                                <!-- Weekday Headers -->
                                                <div class="grid grid-cols-7 gap-1 mb-2">
                                                    <div class="text-center text-xs font-semibold text-gray-600 py-2">Sun</div>
                                                    <div class="text-center text-xs font-semibold text-gray-600 py-2">Mon</div>
                                                    <div class="text-center text-xs font-semibold text-gray-600 py-2">Tue</div>
                                                    <div class="text-center text-xs font-semibold text-gray-600 py-2">Wed</div>
                                                    <div class="text-center text-xs font-semibold text-gray-600 py-2">Thu</div>
                                                    <div class="text-center text-xs font-semibold text-gray-600 py-2">Fri</div>
                                                    <div class="text-center text-xs font-semibold text-gray-600 py-2">Sat</div>
                                                </div>

                                                <!-- Calendar Days -->
                                                <div id="calendarDays" class="grid grid-cols-7 gap-1">
                                                    <!-- Days will be generated by JavaScript -->
                                                </div>
                                            </div>

                                            <!-- Calendar Footer -->
                                            <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-2">
                                                <button onclick="toggleCalendar()" class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors font-medium">
                                                    Cancel
                                                </button>
                                                <button onclick="selectToday()" class="px-4 py-2 bg-green-600 text-white hover:bg-green-700 rounded-lg transition-colors font-medium">
                                                    Today
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Delivery Notes -->
                                <div class="mb-6">
                                    <label for="delivery_notes" class="block text-sm font-medium text-gray-700 mb-2">
                                        Delivery Notes
                                    </label>
                                    <textarea name="delivery_notes" id="delivery_notes" rows="3"
                                              class="w-full px-3 py-2 border {{ $errors->has('delivery_notes') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                              placeholder="Additional delivery instructions (optional)">{{ old('delivery_notes', isset($request) && $request ? $request->delivery_notes : '') }}</textarea>
                                    @error('delivery_notes')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Submit Buttons -->
                                <div class="flex justify-end space-x-4">
                                    <a href="{{ route('customer.cart') }}"
                                       class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-semibold">
                                        <i class="fas fa-arrow-left mr-2"></i>Back to Cart
                                    </a>
                                    <button type="submit"
                                            class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold">
                                        <i class="fas fa-check mr-2"></i>
                                        {{ isset($request) ? 'Update Order' : 'Place Order' }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="lg:col-span-1 lg:order-2">
                        <div class="bg-white rounded-lg shadow p-6 sticky top-4">
                            <h2 class="text-xl font-bold text-gray-900 mb-6">Order Summary</h2>

                            <div class="space-y-4 mb-6">
                                @foreach($cartItems as $cartItem)
                                    <div class="pb-4 border-b border-gray-200 last:border-0">
                                        <div class="flex items-start space-x-3">
                                            <div class="flex items-center justify-center w-16 h-16 rounded-lg flex-shrink-0 overflow-hidden bg-gray-100">
                                                @if($cartItem['product']->image)
                                                    <img src="{{ asset('storage/' . $cartItem['product']->image) }}"
                                                         alt="{{ $cartItem['product']->name }}"
                                                         class="w-full h-full object-cover">
                                                @else
                                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                                    </svg>
                                                @endif
                                            </div>

                                            <div class="flex-1 min-w-0">
                                                <h4 class="font-semibold text-gray-900 mb-1">{{ $cartItem['product']->name }}</h4>
                                                <div class="flex items-center gap-2 mb-2">
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium"
                                                          style="background-color: {{ $cartItem['product']->foodCategory->color }}20; color: {{ $cartItem['product']->foodCategory->color }}">
                                                        <i class="{{ $cartItem['product']->foodCategory->icon }} mr-1"></i>
                                                        {{ $cartItem['product']->foodCategory->name }}
                                                    </span>
                                                </div>
                                                <div class="flex justify-between items-center">
                                                    <span class="text-sm text-gray-600">
                                                        {{ $cartItem['quantity'] }} {{ $cartItem['product']->unit }} × Rp {{ number_format($cartItem['final_price'], 0, ',', '.') }}
                                                    </span>
                                                    <span class="text-lg font-bold text-green-600">
                                                        Rp {{ number_format($cartItem['subtotal'], 0, ',', '.') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="border-t border-gray-200 pt-4">
                                <div class="space-y-2 mb-4">
                                    @php
                                        $totalItems = collect($cartItems)->sum('quantity');
                                    @endphp
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Items ({{ count($cartItems) }}):</span>
                                        <span class="text-sm text-gray-900 font-medium">{{ $totalItems }} {{ $totalItems == 1 ? 'item' : 'items' }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600">Subtotal:</span>
                                        <span class="text-gray-900 font-semibold">Rp {{ number_format($total, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                                <div class="flex justify-between items-center pt-3 border-t border-gray-200">
                                    <span class="text-lg font-bold text-gray-900">Total:</span>
                                    <span class="text-2xl font-bold text-green-600">Rp {{ number_format($total, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let selectedAddressId = null;

// Initialize all functionality on page load
document.addEventListener('DOMContentLoaded', function() {
    // ============ CALENDAR INITIALIZATION ============
    const existingDate = document.getElementById('needed_date').value;
    if (existingDate) {
        const date = new Date(existingDate);
        currentMonth = date.getMonth();
        currentYear = date.getFullYear();
    }
    renderCalendar();

    // ============ ADDRESS INITIALIZATION ============
    @if(isset($defaultAddress) && $defaultAddress)
        const defaultOption = document.querySelector('.address-option');
        if (defaultOption) {
            defaultOption.classList.add('border-green-400', 'bg-green-50');
            defaultOption.querySelector('.address-check-icon').classList.remove('hidden');

            // Set default address data
            document.getElementById('selected_address_id').value = {{ $defaultAddress->id }};
            document.getElementById('recipient_name').value = '{{ addslashes($defaultAddress->recipient_name) }}';
            document.getElementById('recipient_phone').value = '{{ $defaultAddress->recipient_phone }}';
            document.getElementById('delivery_address').value = '{{ addslashes($defaultAddress->delivery_address) }}';
            document.getElementById('city').value = '{{ $defaultAddress->city }}';
            document.getElementById('postal_code').value = '{{ $defaultAddress->postal_code ?? '' }}';
            selectedAddressId = {{ $defaultAddress->id }};

            // Hide form when using saved address
            const form = document.getElementById('deliveryAddressForm');
            if (form) {
                form.classList.add('hidden');
                // Remove required attributes when using saved address
                const requiredFields = form.querySelectorAll('[data-required]');
                requiredFields.forEach(field => {
                    field.removeAttribute('required');
                });
            }
        }
    @else
        // If no saved address, show form and make fields required
        const form = document.getElementById('deliveryAddressForm');
        if (form) {
            form.classList.remove('hidden');
            // Make form fields required when no saved address
            const requiredFields = form.querySelectorAll('[data-required]');
            requiredFields.forEach(field => {
                field.setAttribute('required', 'required');
            });
        }
    @endif

    // Handle form submission - make fields required if using new address
    const form = document.querySelector('#checkoutForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            const addressOption = document.querySelector('input[name="address_option"]:checked');
            if (addressOption && addressOption.value === 'new') {
                // If using new address, ensure all fields are required
                const deliveryForm = document.getElementById('deliveryAddressForm');
                if (deliveryForm) {
                    const requiredFields = deliveryForm.querySelectorAll('[data-required]');
                    requiredFields.forEach(field => {
                        field.setAttribute('required', 'required');
                    });
                }
            }
        });
    }
});

function selectAddress(id, recipientName, recipientPhone, deliveryAddress, city, postalCode) {
    selectedAddressId = id;

    // Update hidden field
    document.getElementById('selected_address_id').value = id;

    // Update form fields (even if hidden, so data is available on submit)
    document.getElementById('recipient_name').value = recipientName;
    document.getElementById('recipient_phone').value = recipientPhone;
    document.getElementById('delivery_address').value = deliveryAddress;
    document.getElementById('city').value = city;
    document.getElementById('postal_code').value = postalCode || '';

    // Update radio button
    document.querySelector('input[name="address_option"][value="saved"]').checked = true;

    // Update visual selection
    document.querySelectorAll('.address-option').forEach(el => {
        el.classList.remove('border-green-400', 'bg-green-50');
        el.querySelector('.address-check-icon').classList.add('hidden');
    });

    event.currentTarget.classList.add('border-green-400', 'bg-green-50');
    event.currentTarget.querySelector('.address-check-icon').classList.remove('hidden');
}

function toggleAddressForm(option) {
    const form = document.getElementById('deliveryAddressForm');
    if (option === 'new') {
        // Show form for new address
        form.classList.remove('hidden');

        // Clear form for new address
        document.getElementById('recipient_name').value = '';
        document.getElementById('recipient_phone').value = '';
        document.getElementById('delivery_address').value = '';
        document.getElementById('city').value = '';
        document.getElementById('postal_code').value = '';

        // Make form fields required when using new address
        const requiredFields = form.querySelectorAll('[data-required]');
        requiredFields.forEach(field => {
            field.setAttribute('required', 'required');
        });

        // Remove selection from saved addresses
        document.querySelectorAll('.address-option').forEach(el => {
            el.classList.remove('border-green-400', 'bg-green-50');
            el.querySelector('.address-check-icon').classList.add('hidden');
        });
        selectedAddressId = null;
        document.getElementById('selected_address_id').value = '';
    } else {
        // Hide form when using saved address
        form.classList.add('hidden');

        // Make form fields not required when using saved address
        const requiredFields = form.querySelectorAll('[data-required]');
        requiredFields.forEach(field => {
            field.removeAttribute('required');
        });

        // Use currently selected address or default address
        if (selectedAddressId) {
            // Address already selected, values already filled
        } else {
            // If there's a default address, use it
            @if(isset($defaultAddress) && $defaultAddress)
                const defaultAddr = { id: {{ $defaultAddress->id }},
                                       name: '{{ addslashes($defaultAddress->recipient_name) }}',
                                       phone: '{{ $defaultAddress->recipient_phone }}',
                                       address: '{{ addslashes($defaultAddress->delivery_address) }}',
                                       city: '{{ $defaultAddress->city }}',
                                       postal: '{{ $defaultAddress->postal_code ?? '' }}' };
                document.getElementById('selected_address_id').value = defaultAddr.id;
                document.getElementById('recipient_name').value = defaultAddr.name;
                document.getElementById('recipient_phone').value = defaultAddr.phone;
                document.getElementById('delivery_address').value = defaultAddr.address;
                document.getElementById('city').value = defaultAddr.city;
                document.getElementById('postal_code').value = defaultAddr.postal;
                selectedAddressId = defaultAddr.id;
            @endif
        }
    }
}

// ============ CALENDAR FUNCTIONS ============
let currentMonth = new Date().getMonth();
let currentYear = new Date().getFullYear();
const today = new Date();
const minDate = new Date(today);
minDate.setDate(today.getDate() + 1); // Minimum date is tomorrow

// Toggle calendar modal
function toggleCalendar() {
    const modal = document.getElementById('calendarModal');
    const isOpening = modal.classList.contains('hidden');
    
    if (isOpening) {
        // Opening modal
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
        
        // Render calendar
        renderCalendar();
    } else {
        // Closing modal
        // Enable body scroll
        document.body.style.overflow = '';
        
        // Hide modal
        modal.classList.add('hidden');
        modal.style.display = 'none';
    }
}

// Render calendar
function renderCalendar() {
    const calendarDays = document.getElementById('calendarDays');
    const monthYear = document.getElementById('calendarMonthYear');

    // Set month and year display
    const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    monthYear.textContent = `${monthNames[currentMonth]} ${currentYear}`;

    // Clear previous days
    calendarDays.innerHTML = '';

    // Get first day of month and number of days
    const firstDay = new Date(currentYear, currentMonth, 1).getDay();
    const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();

    // Add empty cells for days before first day of month
    for (let i = 0; i < firstDay; i++) {
        const emptyDay = document.createElement('div');
        emptyDay.className = 'text-center py-2 text-gray-400';
        calendarDays.appendChild(emptyDay);
    }

    // Add days of month
    for (let day = 1; day <= daysInMonth; day++) {
        const dayCell = document.createElement('button');
        dayCell.type = 'button';
        dayCell.className = 'text-center py-2 rounded-lg hover:bg-gray-100 transition-colors relative';

        const date = new Date(currentYear, currentMonth, day);
        const selectedDate = document.getElementById('needed_date').value;
        const selectedDateObj = selectedDate ? new Date(selectedDate) : null;

        // Check if this date is in the past or today
        if (date < minDate) {
            dayCell.classList.add('text-gray-300', 'cursor-not-allowed', 'hover:bg-transparent');
            dayCell.disabled = true;
        } else {
            // Check if this is the selected date
            if (selectedDateObj && date.toDateString() === selectedDateObj.toDateString()) {
                dayCell.classList.add('bg-green-600', 'text-white', 'font-bold');
            } else if (date.toDateString() === today.toDateString()) {
                dayCell.classList.add('text-green-600', 'font-bold');
            } else {
                dayCell.classList.add('text-gray-700');
            }

            // Add click handler
            dayCell.onclick = () => selectDate(date);
        }

        dayCell.textContent = day;
        calendarDays.appendChild(dayCell);
    }
}

// Change month
function changeMonth(direction) {
    currentMonth += direction;

    // Handle year overflow
    if (currentMonth < 0) {
        currentMonth = 11;
        currentYear--;
    } else if (currentMonth > 11) {
        currentMonth = 0;
        currentYear++;
    }

    renderCalendar();
}

// Select date
function selectDate(date) {
    const formattedDate = date.toISOString().split('T')[0];
    const displayFormat = date.toLocaleDateString('en-US', { year: 'numeric', month: '2-digit', day: '2-digit' });

    // Update hidden input
    document.getElementById('needed_date').value = formattedDate;

    // Update display input
    document.getElementById('needed_date_display').value = formattedDate;

    // Close calendar
    toggleCalendar();
}

// Select today's date
function selectToday() {
    const tomorrow = new Date(minDate);
    selectDate(tomorrow);
}

// Close calendar when clicking outside
document.addEventListener('click', function(event) {
    const modal = document.getElementById('calendarModal');
    const modalContainer = modal.querySelector('.fixed.inset-0.flex');
    if (modal && !modal.classList.contains('hidden')) {
        // Check if click is on the overlay (not on modal content)
        if (event.target === modal || event.target === modal.querySelector('.fixed.inset-0.bg-transparent')) {
            toggleCalendar();
        }
    }
});

// Handle window resize to keep modal centered
let calendarResizeTimeout;
window.addEventListener('resize', function() {
    const modal = document.getElementById('calendarModal');
    if (modal && !modal.classList.contains('hidden')) {
        clearTimeout(calendarResizeTimeout);
        calendarResizeTimeout = setTimeout(function() {
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

// Close modal on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('calendarModal');
        if (modal && !modal.classList.contains('hidden')) {
            toggleCalendar();
        }
    }
});
</script>
@endpush
@endsection
