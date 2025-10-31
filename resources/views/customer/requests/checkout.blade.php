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
                    <!-- Order Summary -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-lg shadow p-6 sticky top-4">
                            <h2 class="text-xl font-bold text-gray-900 mb-6">Order Summary</h2>
                            
                            <div class="space-y-4 mb-6">
                                @foreach($cartItems as $cartItem)
                                    <div class="pb-4 border-b border-gray-200 last:border-0">
                                        <div class="flex items-start space-x-4">
                                            <!-- Product Image or Icon -->
                                            @if($cartItem['product']->image)
                                                <img src="{{ asset('storage/' . $cartItem['product']->image) }}" 
                                                     alt="{{ $cartItem['product']->name }}"
                                                     class="w-16 h-16 object-cover rounded-lg">
                                            @else
                                                <div class="flex items-center justify-center w-16 h-16 rounded-lg flex-shrink-0"
                                                     style="background: linear-gradient(135deg, {{ $cartItem['product']->foodCategory->color }}20 0%, {{ $cartItem['product']->foodCategory->color }}40 100%);">
                                                    <i class="{{ $cartItem['product']->foodCategory->icon }} text-2xl" style="color: {{ $cartItem['product']->foodCategory->color }}"></i>
                                                </div>
                                            @endif
                                            
                                            <!-- Product Details -->
                                            <div class="flex-1 min-w-0">
                                                <h4 class="font-semibold text-gray-900 mb-1">{{ $cartItem['product']->name }}</h4>
                                                <p class="text-xs text-gray-500 mb-2">
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium"
                                                          style="background-color: {{ $cartItem['product']->foodCategory->color }}20; color: {{ $cartItem['product']->foodCategory->color }}">
                                                        <i class="{{ $cartItem['product']->foodCategory->icon }} mr-1"></i>
                                                        {{ $cartItem['product']->foodCategory->name }}
                                                    </span>
                                                </p>
                                                @if($cartItem['product']->description)
                                                    <p class="text-xs text-gray-600 mb-2 line-clamp-2">{{ Str::limit($cartItem['product']->description, 80) }}</p>
                                                @endif
                                                
                                                <!-- Price and Quantity Info -->
                                                <div class="space-y-1 mt-2">
                                                    <div class="flex justify-between items-center">
                                                        <span class="text-xs text-gray-500">Price per {{ $cartItem['product']->unit }}:</span>
                                                        <span class="text-sm font-medium text-gray-700">Rp {{ number_format($cartItem['product']->price, 0, ',', '.') }}</span>
                                                    </div>
                                                    <div class="flex justify-between items-center">
                                                        <span class="text-xs text-gray-500">Quantity:</span>
                                                        <span class="text-sm font-medium text-gray-700">{{ $cartItem['quantity'] }} {{ $cartItem['product']->unit }}</span>
                                                    </div>
                                                    @if($cartItem['product']->min_purchase > 0)
                                                        <div class="flex justify-between items-center">
                                                            <span class="text-xs text-gray-500">Min. Purchase:</span>
                                                            <span class="text-xs text-gray-600">{{ $cartItem['product']->min_purchase }} {{ $cartItem['product']->unit }}</span>
                                                        </div>
                                                    @endif
                                                    <div class="flex justify-between items-center pt-1 border-t border-gray-100">
                                                        <span class="text-sm font-semibold text-gray-900">Subtotal:</span>
                                                        <span class="text-sm font-bold text-green-600">Rp {{ number_format($cartItem['subtotal'], 0, ',', '.') }}</span>
                                                    </div>
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

                    <!-- Delivery Address Form -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-lg shadow">
                            <form method="POST" action="{{ route('customer.requests.store') }}" class="p-6">
                                @csrf
                                
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
                                <div id="deliveryAddressForm">
                                    <!-- Recipient Name -->
                                    <div class="mb-6">
                                        <label for="recipient_name" class="block text-sm font-medium text-gray-700 mb-2">
                                            Recipient Name <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" name="recipient_name" id="recipient_name" 
                                               value="{{ old('recipient_name', $defaultAddress->recipient_name ?? $user->name ?? '') }}"
                                               class="w-full px-3 py-2 border {{ $errors->has('recipient_name') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                               placeholder="Enter recipient name" required>
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
                                               value="{{ old('recipient_phone', $defaultAddress->recipient_phone ?? '') }}"
                                               class="w-full px-3 py-2 border {{ $errors->has('recipient_phone') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                               placeholder="Enter phone number" required>
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
                                                  placeholder="Enter complete delivery address" required>{{ old('delivery_address', $defaultAddress->delivery_address ?? '') }}</textarea>
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
                                                   value="{{ old('city', $defaultAddress->city ?? '') }}"
                                                   class="w-full px-3 py-2 border {{ $errors->has('city') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                                   placeholder="Enter city" required>
                                            @error('city')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-2">
                                                Postal Code
                                            </label>
                                            <input type="text" name="postal_code" id="postal_code" 
                                                   value="{{ old('postal_code', $defaultAddress->postal_code ?? '') }}"
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
                                    <label for="needed_date" class="block text-sm font-medium text-gray-700 mb-2">
                                        Needed Date <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" name="needed_date" id="needed_date" value="{{ old('needed_date') }}"
                                           min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                           class="w-full px-3 py-2 border {{ $errors->has('needed_date') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" required>
                                    @error('needed_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Delivery Notes -->
                                <div class="mb-6">
                                    <label for="delivery_notes" class="block text-sm font-medium text-gray-700 mb-2">
                                        Delivery Notes
                                    </label>
                                    <textarea name="delivery_notes" id="delivery_notes" rows="3"
                                              class="w-full px-3 py-2 border {{ $errors->has('delivery_notes') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                              placeholder="Additional delivery instructions (optional)">{{ old('delivery_notes') }}</textarea>
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
                                        Place Order
                                    </button>
                                </div>
                            </form>
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

function selectAddress(id, recipientName, recipientPhone, deliveryAddress, city, postalCode) {
    selectedAddressId = id;
    
    // Update form fields
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
        // Clear form for new address
        document.getElementById('recipient_name').value = '';
        document.getElementById('recipient_phone').value = '';
        document.getElementById('delivery_address').value = '';
        document.getElementById('city').value = '';
        document.getElementById('postal_code').value = '';
        
        // Remove selection from saved addresses
        document.querySelectorAll('.address-option').forEach(el => {
            el.classList.remove('border-green-400', 'bg-green-50');
            el.querySelector('.address-check-icon').classList.add('hidden');
        });
        selectedAddressId = null;
    } else {
        // If there's a default address, select it
        @if(isset($defaultAddress) && $defaultAddress)
            selectAddress({{ $defaultAddress->id }}, '{{ $defaultAddress->recipient_name }}', '{{ $defaultAddress->recipient_phone }}', '{{ addslashes($defaultAddress->delivery_address) }}', '{{ $defaultAddress->city }}', '{{ $defaultAddress->postal_code }}');
        @endif
    }
}

// Initialize with default address if available
@if(isset($defaultAddress) && $defaultAddress)
    document.addEventListener('DOMContentLoaded', function() {
        const defaultOption = document.querySelector('.address-option');
        if (defaultOption) {
            defaultOption.classList.add('border-green-400', 'bg-green-50');
            defaultOption.querySelector('.address-check-icon').classList.remove('hidden');
        }
    });
@endif
</script>
@endpush
@endsection

