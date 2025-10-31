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
        <div class="flex-1 bg-gray-100">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <!-- Header -->
                <div class="mb-8">
                    <div class="flex items-center">
                        <a href="{{ route('customer.dashboard') }}" class="text-gray-500 hover:text-gray-700 mr-4">
                            <i class="fas fa-arrow-left text-xl"></i>
                        </a>
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">Delivery Addresses</h1>
                            <p class="mt-2 text-gray-600">Manage your delivery addresses. You can add multiple addresses and select one during checkout.</p>
                        </div>
                    </div>
                </div>

                <!-- Success Message -->
                @if(session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Delivery Addresses Section -->
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h2 class="text-xl font-bold text-gray-900">My Delivery Addresses</h2>
                                <p class="text-sm text-gray-600 mt-1">Add and manage multiple delivery addresses for easy checkout</p>
                            </div>
                            <button onclick="openAddAddressModal()"
                                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold text-sm">
                                <i class="fas fa-plus mr-2"></i>Add Address
                            </button>
                        </div>

                        <!-- Addresses List -->
                        @if(isset($addresses) && $addresses->count() > 0)
                            <div class="space-y-4">
                                @foreach($addresses as $address)
                                    <div class="border border-gray-200 rounded-lg p-4 hover:border-green-300 transition-colors {{ $address->is_default ? 'bg-green-50 border-green-300' : '' }}">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <div class="flex items-center mb-2">
                                                    @if($address->label)
                                                        <span class="font-semibold text-gray-900 mr-2">{{ $address->label }}</span>
                                                    @endif
                                                    @if($address->is_default)
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                            <i class="fas fa-star mr-1"></i>Default
                                                        </span>
                                                    @endif
                                                </div>
                                                <p class="text-sm text-gray-700 font-medium mb-1">{{ $address->recipient_name }}</p>
                                                <p class="text-sm text-gray-600 mb-1">{{ $address->recipient_phone }}</p>
                                                <p class="text-sm text-gray-600 mb-1">{{ $address->delivery_address }}</p>
                                                <p class="text-sm text-gray-600">{{ $address->city }}{{ $address->postal_code ? ', ' . $address->postal_code : '' }}</p>
                                            </div>
                                            <div class="flex items-center space-x-2 ml-4">
                                                <button onclick="openEditAddressModal({{ $address->id }}, '{{ $address->label ?? '' }}', '{{ $address->recipient_name }}', '{{ $address->recipient_phone }}', '{{ addslashes($address->delivery_address) }}', '{{ $address->city }}', '{{ $address->postal_code ?? '' }}', {{ $address->is_default ? 'true' : 'false' }})"
                                                        class="px-3 py-1 text-sm text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded transition-colors">
                                                    <i class="fas fa-edit mr-1"></i>Edit
                                                </button>
                                                <form method="POST" action="{{ route('customer.settings.delivery-addresses.delete', $address->id) }}" 
                                                      onsubmit="return confirm('Are you sure you want to delete this address?');" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="px-3 py-1 text-sm text-red-600 hover:text-red-800 hover:bg-red-50 rounded transition-colors">
                                                        <i class="fas fa-trash mr-1"></i>Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <i class="fas fa-map-marker-alt text-4xl text-gray-400 mb-4"></i>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No delivery addresses yet</h3>
                                <p class="text-gray-500 mb-6">Add your first delivery address to get started</p>
                                <button onclick="openAddAddressModal()"
                                        class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold">
                                    <i class="fas fa-plus mr-2"></i>Add Address
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Address Modal -->
<div id="addAddressModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-900">Add New Address</h3>
                <button onclick="closeAddAddressModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('customer.settings.delivery-addresses.store') }}">
                @csrf
                <div class="mb-4">
                    <label for="add_label" class="block text-sm font-medium text-gray-700 mb-2">
                        Label (e.g., Home, Office)
                    </label>
                    <input type="text" name="label" id="add_label"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                           placeholder="Enter label (optional)">
                </div>

                <div class="mb-4">
                    <label for="add_recipient_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Recipient Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="recipient_name" id="add_recipient_name" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                           placeholder="Enter recipient name">
                </div>

                <div class="mb-4">
                    <label for="add_recipient_phone" class="block text-sm font-medium text-gray-700 mb-2">
                        Phone Number <span class="text-red-500">*</span>
                    </label>
                    <input type="tel" name="recipient_phone" id="add_recipient_phone" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                           placeholder="Enter phone number">
                </div>

                <div class="mb-4">
                    <label for="add_delivery_address" class="block text-sm font-medium text-gray-700 mb-2">
                        Delivery Address <span class="text-red-500">*</span>
                    </label>
                    <textarea name="delivery_address" id="add_delivery_address" rows="3" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                              placeholder="Enter complete delivery address"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="add_city" class="block text-sm font-medium text-gray-700 mb-2">
                            City <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="city" id="add_city" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                               placeholder="Enter city">
                    </div>

                    <div>
                        <label for="add_postal_code" class="block text-sm font-medium text-gray-700 mb-2">
                            Postal Code
                        </label>
                        <input type="text" name="postal_code" id="add_postal_code"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                               placeholder="Enter postal code">
                    </div>
                </div>

                <div class="mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_default" value="1" class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                        <span class="ml-2 text-sm text-gray-700">Set as default address</span>
                    </label>
                </div>

                <div class="flex justify-end space-x-4">
                    <button type="button" onclick="closeAddAddressModal()"
                            class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-semibold">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold">
                        <i class="fas fa-save mr-2"></i>Save Address
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Address Modal -->
<div id="editAddressModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-900">Edit Address</h3>
                <button onclick="closeEditAddressModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
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
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                           placeholder="Enter label (optional)">
                </div>

                <div class="mb-4">
                    <label for="edit_recipient_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Recipient Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="recipient_name" id="edit_recipient_name" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                           placeholder="Enter recipient name">
                </div>

                <div class="mb-4">
                    <label for="edit_recipient_phone" class="block text-sm font-medium text-gray-700 mb-2">
                        Phone Number <span class="text-red-500">*</span>
                    </label>
                    <input type="tel" name="recipient_phone" id="edit_recipient_phone" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                           placeholder="Enter phone number">
                </div>

                <div class="mb-4">
                    <label for="edit_delivery_address" class="block text-sm font-medium text-gray-700 mb-2">
                        Delivery Address <span class="text-red-500">*</span>
                    </label>
                    <textarea name="delivery_address" id="edit_delivery_address" rows="3" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                              placeholder="Enter complete delivery address"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="edit_city" class="block text-sm font-medium text-gray-700 mb-2">
                            City <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="city" id="edit_city" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                               placeholder="Enter city">
                    </div>

                    <div>
                        <label for="edit_postal_code" class="block text-sm font-medium text-gray-700 mb-2">
                            Postal Code
                        </label>
                        <input type="text" name="postal_code" id="edit_postal_code"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                               placeholder="Enter postal code">
                    </div>
                </div>

                <div class="mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_default" id="edit_is_default" value="1"
                               class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                        <span class="ml-2 text-sm text-gray-700">Set as default address</span>
                    </label>
                </div>

                <div class="flex justify-end space-x-4">
                    <button type="button" onclick="closeEditAddressModal()"
                            class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-semibold">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold">
                        <i class="fas fa-save mr-2"></i>Update Address
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openAddAddressModal() {
    document.getElementById('addAddressModal').classList.remove('hidden');
}

function closeAddAddressModal() {
    document.getElementById('addAddressModal').classList.add('hidden');
}

function openEditAddressModal(id, label, recipientName, recipientPhone, deliveryAddress, city, postalCode, isDefault) {
    document.getElementById('editAddressForm').action = '{{ url("/customer/settings/delivery-addresses") }}/' + id;
    document.getElementById('edit_label').value = label || '';
    document.getElementById('edit_recipient_name').value = recipientName;
    document.getElementById('edit_recipient_phone').value = recipientPhone;
    document.getElementById('edit_delivery_address').value = deliveryAddress;
    document.getElementById('edit_city').value = city;
    document.getElementById('edit_postal_code').value = postalCode || '';
    document.getElementById('edit_is_default').checked = isDefault;
    document.getElementById('editAddressModal').classList.remove('hidden');
}

function closeEditAddressModal() {
    document.getElementById('editAddressModal').classList.add('hidden');
}
</script>
@endpush
@endsection

