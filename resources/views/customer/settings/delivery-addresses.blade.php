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
        <div class="flex-1 bg-gray-50 min-h-screen">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
                <!-- Header -->
                <div class="mb-6 sm:mb-8">
                    <div class="flex items-center gap-3 sm:gap-4 mb-3 sm:mb-4">
                        <a href="{{ route('customer.dashboard') }}" class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-600 hover:text-gray-900 hover:bg-white transition-colors">
                            <i class="fas fa-arrow-left text-sm"></i>
                        </a>
                        <div>
                            <h1 class="text-xl sm:text-2xl font-semibold text-gray-900">Delivery Addresses</h1>
                            <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Manage your delivery locations</p>
                        </div>
                    </div>
                </div>

                <!-- Status Messages -->
                @if(session('status'))
                    @php $alert = session('status'); $type = $alert['type'] ?? 'success'; @endphp
                    <div class="mb-4 sm:mb-6">
                        @if($type === 'danger')
                            <div class="rounded-lg bg-red-50 p-3 sm:p-4 border border-red-200">
                                <div class="flex items-start gap-3">
                                    <i class="fas fa-exclamation-circle text-red-600 mt-0.5 flex-shrink-0"></i>
                                    <p class="text-sm font-medium text-red-800">{{ $alert['message'] ?? $alert }}</p>
                                </div>
                            </div>
                        @else
                            <div class="rounded-lg bg-green-50 p-3 sm:p-4 border border-green-200">
                                <div class="flex items-start gap-3">
                                    <i class="fas fa-check-circle text-green-600 mt-0.5 flex-shrink-0"></i>
                                    <p class="text-sm font-medium text-green-800">{{ $alert['message'] ?? $alert }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Action Header -->
                <div class="mb-4 sm:mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
                    <div>
                        <h2 class="text-base sm:text-lg font-medium text-gray-900">My Addresses</h2>
                        <p class="text-xs sm:text-sm text-gray-500 mt-0.5">{{ isset($addresses) && $addresses->count() > 0 ? $addresses->count() . ' saved address' . ($addresses->count() > 1 ? 'es' : '') : 'No addresses saved' }}</p>
                    </div>
                    <button onclick="openAddAddressModal()"
                            class="inline-flex items-center justify-center px-4 sm:px-5 py-2 sm:py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium text-sm">
                        <i class="fas fa-plus mr-1.5 text-xs"></i>Add New Address
                    </button>
                </div>

                <!-- Addresses List -->
                @if(isset($addresses) && $addresses->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
                        @foreach($addresses as $address)
                            <div class="group relative bg-white rounded-lg border {{ $address->is_default ? 'border-green-500 border-2' : 'border-gray-200' }} hover:border-gray-300 transition-colors overflow-hidden">
                                @if($address->is_default)
                                    <div class="absolute top-3 right-3">
                                        <span class="inline-flex items-center px-2 py-1 rounded-md bg-green-50 text-green-700 text-xs font-medium">
                                            <i class="fas fa-star mr-1 text-xs"></i>Default
                                        </span>
                                    </div>
                                @endif
                                
                                <div class="p-4 sm:p-5">
                                    <!-- Header -->
                                    <div class="mb-4 {{ $address->is_default ? 'pr-16' : '' }}">
                                        @if($address->label)
                                            <h3 class="font-semibold text-gray-900 text-base mb-1">{{ $address->label }}</h3>
                                            <p class="text-sm text-gray-600">{{ $address->recipient_name }}</p>
                                        @else
                                            <h3 class="font-semibold text-gray-900 text-base">{{ $address->recipient_name }}</h3>
                                        @endif
                                    </div>

                                    <!-- Address Details -->
                                    <div class="space-y-2.5 mb-5 text-sm text-gray-600">
                                        <div class="flex items-start gap-2.5">
                                            <i class="fas fa-phone text-gray-400 text-xs mt-0.5 flex-shrink-0"></i>
                                            <span class="break-words">{{ $address->recipient_phone }}</span>
                                        </div>
                                        <div class="flex items-start gap-2.5">
                                            <i class="fas fa-map-marker-alt text-gray-400 text-xs mt-0.5 flex-shrink-0"></i>
                                            <span class="break-words leading-relaxed">{{ $address->delivery_address }}</span>
                                        </div>
                                        <div class="flex items-start gap-2.5">
                                            <i class="fas fa-city text-gray-400 text-xs mt-0.5 flex-shrink-0"></i>
                                            <span>{{ $address->city }}{{ $address->postal_code ? ' ' . $address->postal_code : '' }}</span>
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="flex items-center gap-2 pt-4 border-t border-gray-100">
                                        <button onclick="openEditAddressModal({{ $address->id }}, '{{ $address->label ?? '' }}', '{{ $address->recipient_name }}', '{{ $address->recipient_phone }}', '{{ addslashes($address->delivery_address) }}', '{{ $address->city }}', '{{ $address->postal_code ?? '' }}', {{ $address->is_default ? 'true' : 'false' }})"
                                                class="flex-1 inline-flex items-center justify-center px-3 py-2 text-gray-700 rounded-md hover:bg-gray-50 transition-colors font-medium text-xs sm:text-sm border border-gray-200">
                                            <i class="fas fa-edit mr-1.5 text-xs"></i>Edit
                                        </button>
                                        <button onclick="confirmDeleteAddress({{ $address->id }}, '{{ $address->label ?? $address->recipient_name }}')"
                                                class="flex-1 inline-flex items-center justify-center px-3 py-2 text-red-600 rounded-md hover:bg-red-50 transition-colors font-medium text-xs sm:text-sm border border-red-200">
                                            <i class="fas fa-trash mr-1.5 text-xs"></i>Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="bg-white rounded-lg border border-gray-200 p-8 sm:p-12 text-center">
                        <div class="w-16 h-16 sm:w-20 sm:h-20 mx-auto mb-4 sm:mb-6 rounded-full bg-gray-50 flex items-center justify-center">
                            <i class="fas fa-map-marker-alt text-2xl sm:text-3xl text-gray-400"></i>
                        </div>
                        <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2">No delivery addresses yet</h3>
                        <p class="text-sm sm:text-base text-gray-500 mb-6 sm:mb-8 max-w-md mx-auto">Get started by adding your first delivery address. You can add multiple addresses and switch between them during checkout.</p>
                        <button onclick="openAddAddressModal()"
                                class="inline-flex items-center justify-center px-5 sm:px-6 py-2.5 sm:py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium text-sm">
                            <i class="fas fa-plus mr-1.5 text-xs"></i>Add Your First Address
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Add Address Modal -->
<div id="addAddressModal" class="hidden fixed inset-0 z-[9999]" style="display: none; background-color: rgba(0, 0, 0, 0.5);">
    <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none" id="addAddressModalContainer">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[calc(100vh-4rem)] overflow-y-auto pointer-events-auto border border-gray-200">
            <div class="p-5 sm:p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg sm:text-xl font-semibold text-gray-900">Add New Address</h3>
                <button onclick="closeAddAddressModal()" class="w-8 h-8 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-50 transition-colors flex items-center justify-center">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('customer.settings.delivery-addresses.store') }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="add_label" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Label (e.g., Home, Office)
                        </label>
                        <input type="text" name="label" id="add_label"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all text-sm"
                               placeholder="Enter label (optional)">
                    </div>

                    <div>
                        <label for="add_recipient_name" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Recipient Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="recipient_name" id="add_recipient_name" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all text-sm"
                               placeholder="Enter recipient name">
                    </div>

                    <div>
                        <label for="add_recipient_phone" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Phone Number <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" name="recipient_phone" id="add_recipient_phone" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all text-sm"
                               placeholder="Enter phone number">
                    </div>

                    <div>
                        <label for="add_delivery_address" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Delivery Address <span class="text-red-500">*</span>
                        </label>
                        <textarea name="delivery_address" id="add_delivery_address" rows="3" required
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all text-sm resize-none"
                                  placeholder="Enter complete delivery address"></textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="add_city" class="block text-sm font-medium text-gray-700 mb-1.5">
                                City <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="city" id="add_city" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all text-sm"
                                   placeholder="Enter city">
                        </div>

                        <div>
                            <label for="add_postal_code" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Postal Code
                            </label>
                            <input type="text" name="postal_code" id="add_postal_code"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all text-sm"
                                   placeholder="Enter postal code">
                        </div>
                    </div>

                    <div>
                        <label for="add_is_default" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Set as default address
                        </label>
                        <select name="is_default" id="add_is_default" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all text-sm">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                        <p class="mt-1 text-xs text-gray-500">Only one address can be set as default</p>
                    </div>
                </div>

                <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 mt-6 pt-5 border-t border-gray-200">
                    <button type="button" onclick="closeAddAddressModal()"
                            class="w-full sm:w-auto px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium text-sm">
                        Cancel
                    </button>
                    <button type="submit"
                            class="w-full sm:w-auto px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium text-sm">
                        <i class="fas fa-save mr-1.5 text-xs"></i>Save Address
                    </button>
                </div>
            </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Address Modal -->
<div id="editAddressModal" class="hidden fixed inset-0 z-[9999]" style="display: none; background-color: rgba(0, 0, 0, 0.5);">
    <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none" id="editAddressModalContainer">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[calc(100vh-4rem)] overflow-y-auto pointer-events-auto border border-gray-200">
            <div class="p-5 sm:p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg sm:text-xl font-semibold text-gray-900">Edit Address</h3>
                <button onclick="closeEditAddressModal()" class="w-8 h-8 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-50 transition-colors flex items-center justify-center">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>

            <form id="editAddressForm" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label for="edit_label" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Label (e.g., Home, Office)
                        </label>
                        <input type="text" name="label" id="edit_label"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all text-sm"
                               placeholder="Enter label (optional)">
                    </div>

                    <div>
                        <label for="edit_recipient_name" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Recipient Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="recipient_name" id="edit_recipient_name" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all text-sm"
                               placeholder="Enter recipient name">
                    </div>

                    <div>
                        <label for="edit_recipient_phone" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Phone Number <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" name="recipient_phone" id="edit_recipient_phone" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all text-sm"
                               placeholder="Enter phone number">
                    </div>

                    <div>
                        <label for="edit_delivery_address" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Delivery Address <span class="text-red-500">*</span>
                        </label>
                        <textarea name="delivery_address" id="edit_delivery_address" rows="3" required
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all text-sm resize-none"
                                  placeholder="Enter complete delivery address"></textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="edit_city" class="block text-sm font-medium text-gray-700 mb-1.5">
                                City <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="city" id="edit_city" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all text-sm"
                                   placeholder="Enter city">
                        </div>

                        <div>
                            <label for="edit_postal_code" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Postal Code
                            </label>
                            <input type="text" name="postal_code" id="edit_postal_code"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all text-sm"
                                   placeholder="Enter postal code">
                        </div>
                    </div>

                    <div>
                        <label for="edit_is_default" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Set as default address
                        </label>
                        <select name="is_default" id="edit_is_default" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all text-sm">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                        <p class="mt-1 text-xs text-gray-500">Only one address can be set as default</p>
                    </div>
                </div>

                <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 mt-6 pt-5 border-t border-gray-200">
                    <button type="button" onclick="closeEditAddressModal()"
                            class="w-full sm:w-auto px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium text-sm">
                        Cancel
                    </button>
                    <button type="submit"
                            class="w-full sm:w-auto px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium text-sm">
                        <i class="fas fa-save mr-1.5 text-xs"></i>Update Address
                    </button>
                </div>
            </form>
            </div>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div id="confirmDeleteModal" class="hidden fixed inset-0 z-[9999]" style="display: none; background-color: rgba(0, 0, 0, 0.5);">
    <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none" id="confirmDeleteModalContainer">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full pointer-events-auto border border-gray-200">
            <div class="p-5 sm:p-6">
                <div class="flex items-start gap-4 mb-4">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-red-50 flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-red-600 text-lg"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Confirm Delete</h3>
                        <p id="confirmDeleteMessage" class="text-sm text-gray-600 leading-relaxed"></p>
                    </div>
                </div>
                <form id="deleteAddressForm" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 pt-4 border-t border-gray-200">
                        <button type="button" onclick="closeDeleteModal()" class="w-full sm:w-auto px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium text-sm">
                            Cancel
                        </button>
                        <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium text-sm">
                            <i class="fas fa-trash mr-1.5 text-xs"></i>Delete Address
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
    const container = document.getElementById('confirmDeleteModalContainer');
    const addressLabel = label || 'this address';
    document.getElementById('confirmDeleteMessage').textContent = `Are you sure you want to delete "${addressLabel}"? This action cannot be undone.`;
    document.getElementById('deleteAddressForm').action = '{{ url("/customer/settings/delivery-addresses") }}/' + id;
    
    // Calculate center position considering sidebar on desktop
    const isDesktop = window.innerWidth >= 1024; // lg breakpoint
    const sidebarWidth = isDesktop ? 256 : 0; // 64 * 4 = 256px (lg:ml-64)
    const viewportWidth = window.innerWidth;
    const availableWidth = viewportWidth - sidebarWidth;
    
    // Set container position to center in available content area
    if (container) {
        container.style.left = sidebarWidth + 'px';
        container.style.width = availableWidth + 'px';
    }
    
    modal.classList.remove('hidden');
    modal.style.display = 'block';
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
    const container = document.getElementById('addAddressModalContainer');
    
    // Calculate center position considering sidebar on desktop
    const isDesktop = window.innerWidth >= 1024; // lg breakpoint
    const sidebarWidth = isDesktop ? 256 : 0; // 64 * 4 = 256px (lg:ml-64)
    const viewportWidth = window.innerWidth;
    const availableWidth = viewportWidth - sidebarWidth;
    
    // Set container position to center in available content area
    if (container) {
        container.style.left = sidebarWidth + 'px';
        container.style.width = availableWidth + 'px';
    }
    
    modal.style.display = 'block';
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
    const container = document.getElementById('editAddressModalContainer');
    
    // Calculate center position considering sidebar on desktop
    const isDesktop = window.innerWidth >= 1024; // lg breakpoint
    const sidebarWidth = isDesktop ? 256 : 0; // 64 * 4 = 256px (lg:ml-64)
    const viewportWidth = window.innerWidth;
    const availableWidth = viewportWidth - sidebarWidth;
    
    // Set container position to center in available content area
    if (container) {
        container.style.left = sidebarWidth + 'px';
        container.style.width = availableWidth + 'px';
    }
    
    modal.style.display = 'block';
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

// Handle window resize to keep modals centered
let modalResizeTimeout;
window.addEventListener('resize', function() {
    clearTimeout(modalResizeTimeout);
    modalResizeTimeout = setTimeout(function() {
        const isDesktop = window.innerWidth >= 1024;
        const sidebarWidth = isDesktop ? 256 : 0;
        const viewportWidth = window.innerWidth;
        const availableWidth = viewportWidth - sidebarWidth;
        
        // Update Add Address Modal
        const addModal = document.getElementById('addAddressModal');
        const addContainer = document.getElementById('addAddressModalContainer');
        if (addModal && !addModal.classList.contains('hidden') && addContainer) {
            addContainer.style.left = sidebarWidth + 'px';
            addContainer.style.width = availableWidth + 'px';
        }
        
        // Update Edit Address Modal
        const editModal = document.getElementById('editAddressModal');
        const editContainer = document.getElementById('editAddressModalContainer');
        if (editModal && !editModal.classList.contains('hidden') && editContainer) {
            editContainer.style.left = sidebarWidth + 'px';
            editContainer.style.width = availableWidth + 'px';
        }
        
        // Update Delete Modal
        const deleteModal = document.getElementById('confirmDeleteModal');
        const deleteContainer = document.getElementById('confirmDeleteModalContainer');
        if (deleteModal && !deleteModal.classList.contains('hidden') && deleteContainer) {
            deleteContainer.style.left = sidebarWidth + 'px';
            deleteContainer.style.width = availableWidth + 'px';
        }
    }, 100);
});

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
    
    // Close modals on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (!addModal.classList.contains('hidden')) {
                closeAddAddressModal();
            } else if (!editModal.classList.contains('hidden')) {
                closeEditAddressModal();
            } else if (!deleteModal.classList.contains('hidden')) {
                closeDeleteModal();
            }
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

