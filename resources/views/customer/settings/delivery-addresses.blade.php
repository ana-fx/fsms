@extends('layouts.app')

@section('title', 'Delivery Addresses')

@section('content')
<div class="flex bg-gray-100 min-h-screen w-full overflow-x-hidden">
    @include('customer.partials.sidebar')

    <!-- Mobile Menu Button -->
    <button id="openSidebar" class="lg:hidden fixed top-4 left-4 z-50 p-2 bg-white rounded-lg shadow-lg hover:bg-gray-100 transition-colors">
        <i class="fas fa-bars text-gray-700 text-xl"></i>
    </button>

    <!-- Main Content -->
    <div class="w-full lg:ml-64 transition-all duration-300">
        <div class="flex-1 bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <!-- Header -->
                <div class="mb-8">
                    <div class="flex items-center gap-4 mb-4">
                        <a href="{{ route('customer.dashboard') }}" class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white text-gray-600 hover:text-gray-900 hover:bg-gray-50 transition-all duration-200 shadow-sm">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center shadow-lg">
                                <i class="fas fa-map-marker-alt text-white text-lg"></i>
                            </div>
                            <div>
                                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Delivery Addresses</h1>
                                <p class="text-sm text-gray-500 mt-0.5">Manage your delivery locations</p>
                            </div>
                        </div>
                    </div>
                    <p class="ml-16 text-sm text-gray-600">Add and manage multiple delivery addresses for faster checkout experience</p>
                </div>

                <!-- Status Messages -->
                @if(session('status'))
                    @php $alert = session('status'); $type = $alert['type'] ?? 'success'; @endphp
                    <div class="mb-6">
                        @if($type === 'danger')
                            <div class="rounded-md bg-red-50 p-4 border border-red-200">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-exclamation-circle text-red-600"></i>
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

                <!-- Action Header -->
                <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">My Addresses</h2>
                        <p class="text-sm text-gray-500 mt-1">{{ isset($addresses) && $addresses->count() > 0 ? $addresses->count() . ' saved address' . ($addresses->count() > 1 ? 'es' : '') : 'No addresses saved' }}</p>
                    </div>
                    <button onclick="openAddAddressModal()"
                            class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-xl hover:from-green-700 hover:to-green-800 transition-all duration-200 font-semibold text-sm shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="fas fa-plus mr-2"></i>Add New Address
                    </button>
                </div>

                <!-- Addresses List -->
                @if(isset($addresses) && $addresses->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($addresses as $address)
                            <div class="group relative bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden {{ $address->is_default ? 'ring-2 ring-green-500 ring-offset-2' : '' }}">
                                @if($address->is_default)
                                    <div class="absolute top-0 right-0 bg-gradient-to-br from-green-500 to-green-600 text-white px-4 py-1 rounded-bl-lg shadow-lg">
                                        <span class="text-xs font-semibold flex items-center">
                                            <i class="fas fa-star mr-1.5 text-yellow-300"></i>Default
                                        </span>
                                    </div>
                                @endif
                                
                                <div class="p-6 pt-8">
                                    <!-- Header -->
                                    <div class="flex items-start justify-between mb-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-md">
                                                <i class="fas fa-map-marker-alt text-white"></i>
                                            </div>
                                            <div>
                                                @if($address->label)
                                                    <h3 class="font-bold text-gray-900 text-lg">{{ $address->label }}</h3>
                                                @else
                                                    <h3 class="font-bold text-gray-900 text-lg">{{ $address->recipient_name }}</h3>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Address Details -->
                                    <div class="space-y-2 mb-6">
                                        @if($address->label)
                                            <p class="text-sm font-medium text-gray-700">{{ $address->recipient_name }}</p>
                                        @endif
                                        <div class="flex items-start gap-2">
                                            <i class="fas fa-phone text-gray-400 text-xs mt-1"></i>
                                            <p class="text-sm text-gray-600">{{ $address->recipient_phone }}</p>
                                        </div>
                                        <div class="flex items-start gap-2">
                                            <i class="fas fa-map-pin text-gray-400 text-xs mt-1"></i>
                                            <p class="text-sm text-gray-600 leading-relaxed">{{ $address->delivery_address }}</p>
                                        </div>
                                        <div class="flex items-start gap-2">
                                            <i class="fas fa-city text-gray-400 text-xs mt-1"></i>
                                            <p class="text-sm text-gray-600">{{ $address->city }}{{ $address->postal_code ? ' ' . $address->postal_code : '' }}</p>
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="flex items-center gap-2 pt-4 border-t border-gray-100">
                                        <button onclick="openEditAddressModal({{ $address->id }}, '{{ $address->label ?? '' }}', '{{ $address->recipient_name }}', '{{ $address->recipient_phone }}', '{{ addslashes($address->delivery_address) }}', '{{ $address->city }}', '{{ $address->postal_code ?? '' }}', {{ $address->is_default ? 'true' : 'false' }})"
                                                class="flex-1 inline-flex items-center justify-center px-4 py-2.5 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-colors font-medium text-sm">
                                            <i class="fas fa-edit mr-2"></i>Edit
                                        </button>
                                        <button onclick="confirmDeleteAddress({{ $address->id }}, '{{ $address->label ?? $address->recipient_name }}')"
                                                class="flex-1 inline-flex items-center justify-center px-4 py-2.5 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 transition-colors font-medium text-sm">
                                            <i class="fas fa-trash mr-2"></i>Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                        <div class="w-24 h-24 mx-auto mb-6 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                            <i class="fas fa-map-marker-alt text-4xl text-gray-400"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">No delivery addresses yet</h3>
                        <p class="text-gray-500 mb-8 max-w-md mx-auto">Get started by adding your first delivery address. You can add multiple addresses and switch between them during checkout.</p>
                        <button onclick="openAddAddressModal()"
                                class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-xl hover:from-green-700 hover:to-green-800 transition-all duration-200 font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <i class="fas fa-plus mr-2"></i>Add Your First Address
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Add Address Modal -->
<div id="addAddressModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm z-[9999] flex items-center justify-center p-4 overflow-y-auto" style="display: none;">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full my-8 max-h-[calc(100vh-4rem)] overflow-y-auto">
        <div class="p-6 sm:p-8">
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center">
                        <i class="fas fa-plus text-white text-sm"></i>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-bold text-gray-900">Add New Address</h3>
                </div>
                <button onclick="closeAddAddressModal()" class="w-8 h-8 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors flex items-center justify-center">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('customer.settings.delivery-addresses.store') }}">
                @csrf
                <div class="mb-4">
                    <label for="add_label" class="block text-sm font-medium text-gray-700 mb-2">
                        Label (e.g., Home, Office)
                    </label>
                    <input type="text" name="label" id="add_label"
                           class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all"
                           placeholder="Enter label (optional)">
                </div>

                <div class="mb-4">
                    <label for="add_recipient_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Recipient Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="recipient_name" id="add_recipient_name" required
                           class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all"
                           placeholder="Enter recipient name">
                </div>

                <div class="mb-4">
                    <label for="add_recipient_phone" class="block text-sm font-medium text-gray-700 mb-2">
                        Phone Number <span class="text-red-500">*</span>
                    </label>
                    <input type="tel" name="recipient_phone" id="add_recipient_phone" required
                           class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all"
                           placeholder="Enter phone number">
                </div>

                <div class="mb-4">
                    <label for="add_delivery_address" class="block text-sm font-medium text-gray-700 mb-2">
                        Delivery Address <span class="text-red-500">*</span>
                    </label>
                    <textarea name="delivery_address" id="add_delivery_address" rows="3" required
                              class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all"
                              placeholder="Enter complete delivery address"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="add_city" class="block text-sm font-medium text-gray-700 mb-2">
                            City <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="city" id="add_city" required
                               class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all"
                               placeholder="Enter city">
                    </div>

                    <div>
                        <label for="add_postal_code" class="block text-sm font-medium text-gray-700 mb-2">
                            Postal Code
                        </label>
                        <input type="text" name="postal_code" id="add_postal_code"
                               class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all"
                               placeholder="Enter postal code">
                    </div>
                </div>

                <div class="mb-6">
                    <label for="add_is_default" class="block text-sm font-medium text-gray-700 mb-2">
                        Set as default address
                    </label>
                    <select name="is_default" id="add_is_default" required
                            class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all">
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                    </select>
                    <p class="mt-1 text-xs text-gray-500">Only one address can be set as default</p>
                </div>

                <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 mt-8 pt-6 border-t border-gray-200">
                    <button type="button" onclick="closeAddAddressModal()"
                            class="w-full sm:w-auto px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 hover:border-gray-400 transition-all font-semibold">
                        Cancel
                    </button>
                    <button type="submit"
                            class="w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-xl hover:from-green-700 hover:to-green-800 transition-all font-semibold shadow-lg hover:shadow-xl">
                        <i class="fas fa-save mr-2"></i>Save Address
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Address Modal -->
<div id="editAddressModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm z-[9999] flex items-center justify-center p-4 overflow-y-auto" style="display: none;">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full my-8 max-h-[calc(100vh-4rem)] overflow-y-auto">
        <div class="p-6 sm:p-8">
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                        <i class="fas fa-edit text-white text-sm"></i>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-bold text-gray-900">Edit Address</h3>
                </div>
                <button onclick="closeEditAddressModal()" class="w-8 h-8 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors flex items-center justify-center">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="editAddressForm" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="edit_label" class="block text-sm font-medium text-gray-700 mb-2">
                        Label (e.g., Home, Office)
                    </label>
                    <input type="text" name="label" id="edit_label"
                           class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all"
                           placeholder="Enter label (optional)">
                </div>

                <div class="mb-4">
                    <label for="edit_recipient_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Recipient Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="recipient_name" id="edit_recipient_name" required
                           class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all"
                           placeholder="Enter recipient name">
                </div>

                <div class="mb-4">
                    <label for="edit_recipient_phone" class="block text-sm font-medium text-gray-700 mb-2">
                        Phone Number <span class="text-red-500">*</span>
                    </label>
                    <input type="tel" name="recipient_phone" id="edit_recipient_phone" required
                           class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all"
                           placeholder="Enter phone number">
                </div>

                <div class="mb-4">
                    <label for="edit_delivery_address" class="block text-sm font-medium text-gray-700 mb-2">
                        Delivery Address <span class="text-red-500">*</span>
                    </label>
                    <textarea name="delivery_address" id="edit_delivery_address" rows="3" required
                              class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all"
                              placeholder="Enter complete delivery address"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="edit_city" class="block text-sm font-medium text-gray-700 mb-2">
                            City <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="city" id="edit_city" required
                               class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all"
                               placeholder="Enter city">
                    </div>

                    <div>
                        <label for="edit_postal_code" class="block text-sm font-medium text-gray-700 mb-2">
                            Postal Code
                        </label>
                        <input type="text" name="postal_code" id="edit_postal_code"
                               class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all"
                               placeholder="Enter postal code">
                    </div>
                </div>

                <div class="mb-6">
                    <label for="edit_is_default" class="block text-sm font-medium text-gray-700 mb-2">
                        Set as default address
                    </label>
                    <select name="is_default" id="edit_is_default" required
                            class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all">
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                    </select>
                    <p class="mt-1 text-xs text-gray-500">Only one address can be set as default</p>
                </div>

                <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 mt-8 pt-6 border-t border-gray-200">
                    <button type="button" onclick="closeEditAddressModal()"
                            class="w-full sm:w-auto px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 hover:border-gray-400 transition-all font-semibold">
                        Cancel
                    </button>
                    <button type="submit"
                            class="w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-xl hover:from-green-700 hover:to-green-800 transition-all font-semibold shadow-lg hover:shadow-xl">
                        <i class="fas fa-save mr-2"></i>Update Address
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div id="confirmDeleteModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm z-[9999] hidden items-center justify-center" style="display: none;">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4">
        <div class="p-8">
            <div class="flex items-center mb-6">
                <div class="flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-yellow-400 to-yellow-500 mr-4 shadow-lg">
                    <i class="fas fa-exclamation-triangle text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900">Confirm Delete</h3>
            </div>
            <p id="confirmDeleteMessage" class="text-gray-600 mb-8 text-sm leading-relaxed"></p>
            <form id="deleteAddressForm" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeDeleteModal()" class="w-full sm:w-auto px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 hover:border-gray-400 transition-all font-semibold">
                        Cancel
                    </button>
                    <button type="submit" class="w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl hover:from-red-700 hover:to-red-800 transition-all font-semibold shadow-lg hover:shadow-xl">
                        <i class="fas fa-trash mr-2"></i>Delete Address
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
    notification.className = `fixed top-4 right-4 ${color.bg} ${color.border} border rounded-lg shadow-lg z-[10000] flex items-center space-x-3 p-4 animate-slide-in`;
    notification.style.minWidth = '300px';
    notification.style.maxWidth = '400px';
    notification.innerHTML = `
        <div class="flex-shrink-0">
            <i class="fas ${color.icon} ${color.iconColor} text-xl"></i>
        </div>
        <div class="flex-1">
            <p class="${color.text} font-medium text-sm">${message}</p>
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

function confirmDeleteAddress(id, label) {
    const modal = document.getElementById('confirmDeleteModal');
    const addressLabel = label || 'this address';
    document.getElementById('confirmDeleteMessage').textContent = `Are you sure you want to delete "${addressLabel}"? This action cannot be undone.`;
    document.getElementById('deleteAddressForm').action = '{{ url("/customer/settings/delivery-addresses") }}/' + id;
    modal.classList.remove('hidden');
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeDeleteModal() {
    const modal = document.getElementById('confirmDeleteModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
    document.getElementById('deleteAddressForm').action = '';
    document.body.style.overflow = '';
}
function openAddAddressModal() {
    const modal = document.getElementById('addAddressModal');
    modal.style.display = 'flex';
    modal.classList.remove('hidden');
    // Prevent body scroll when modal is open
    document.body.style.overflow = 'hidden';
}

function closeAddAddressModal() {
    const modal = document.getElementById('addAddressModal');
    modal.style.display = 'none';
    modal.classList.add('hidden');
    // Restore body scroll
    document.body.style.overflow = '';
}

function openEditAddressModal(id, label, recipientName, recipientPhone, deliveryAddress, city, postalCode, isDefault) {
    document.getElementById('editAddressForm').action = '{{ url("/customer/settings/delivery-addresses") }}/' + id;
    document.getElementById('edit_label').value = label || '';
    document.getElementById('edit_recipient_name').value = recipientName;
    document.getElementById('edit_recipient_phone').value = recipientPhone;
    document.getElementById('edit_delivery_address').value = deliveryAddress;
    document.getElementById('edit_city').value = city;
    document.getElementById('edit_postal_code').value = postalCode || '';
    // Set select for default address
    document.getElementById('edit_is_default').value = isDefault ? '1' : '0';
    const modal = document.getElementById('editAddressModal');
    modal.style.display = 'flex';
    modal.classList.remove('hidden');
    // Prevent body scroll when modal is open
    document.body.style.overflow = 'hidden';
}

function closeEditAddressModal() {
    const modal = document.getElementById('editAddressModal');
    modal.style.display = 'none';
    modal.classList.add('hidden');
    // Restore body scroll
    document.body.style.overflow = '';
}

// Close modal when clicking outside
document.addEventListener('DOMContentLoaded', function() {
    const addModal = document.getElementById('addAddressModal');
    const editModal = document.getElementById('editAddressModal');
    const deleteModal = document.getElementById('confirmDeleteModal');
    
    addModal.addEventListener('click', function(e) {
        if (e.target === addModal) {
            closeAddAddressModal();
        }
    });
    
    editModal.addEventListener('click', function(e) {
        if (e.target === editModal) {
            closeEditAddressModal();
        }
    });
    
    deleteModal.addEventListener('click', function(e) {
        if (e.target === deleteModal) {
            closeDeleteModal();
        }
    });

    // Handle delete form submit
    const deleteForm = document.getElementById('deleteAddressForm');
    if (deleteForm) {
        deleteForm.addEventListener('submit', function(e) {
            closeDeleteModal();
        });
    }

    // Show notification if there's a success message from session
    @if(session('status'))
        @php $alert = session('status'); $type = $alert['type'] ?? 'success'; @endphp
        showNotification('{{ addslashes($alert['message'] ?? $alert) }}', '{{ $type }}');
    @endif
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

