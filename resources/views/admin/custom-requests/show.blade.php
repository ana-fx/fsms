@extends('layouts.app')

@section('title', 'Custom Request Details - Admin')

@section('content')
<div class="flex bg-gray-100 min-h-screen w-full overflow-x-hidden">
    @include('admin.partials.sidebar')

    <!-- Mobile Menu Button -->
    <button id="openSidebar" class="lg:hidden fixed top-4 left-4 z-50 p-2 bg-white rounded-lg shadow-lg hover:bg-gray-100 transition-colors print:hidden">
        <i class="fas fa-bars text-gray-700 text-xl"></i>
    </button>

    <!-- Main Content -->
    <div class="w-full lg:ml-64 transition-all duration-300 overflow-x-hidden">
        <div class="flex-1 bg-gray-100 min-h-screen">
            <div class="w-full py-8">
                <!-- Header -->
                <div class="mb-8 px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center">
                        <a href="{{ route('admin.custom-requests.index') }}" class="text-gray-500 hover:text-gray-700 mr-4">
                            <i class="fas fa-arrow-left text-xl"></i>
                        </a>
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">Custom Request Details</h1>
                            <p class="mt-2 text-gray-600">Review and manage custom request</p>
                        </div>
                    </div>
                </div>

                <div class="px-4 sm:px-6 lg:px-8">
                    <!-- Invoice Style Layout -->
                    <div class="bg-white border-2 border-gray-200 rounded-lg shadow-sm mb-8 print:shadow-none">
                        <div class="print:hidden border-b border-gray-200 p-4 bg-gray-50 flex justify-between items-center">
                            <h1 class="text-2xl font-bold text-gray-900">Custom Request Invoice</h1>
                            <button onclick="window.print()" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition-colors font-semibold">
                                <i class="fas fa-print mr-2"></i>Print Invoice
                            </button>
                        </div>
                        <div class="p-8">
                            <!-- Company & Invoice Info -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8 pb-8 border-b-2 border-gray-300">
                                <div>
                                    <h2 class="text-2xl font-bold text-gray-900 mb-2">FSMS</h2>
                                    <p class="text-gray-600 text-sm">FoodSupply Management System</p>
                                    <p class="text-gray-600 text-sm mt-1">Jakarta, Indonesia</p>
                                </div>
                                <div class="text-right">
                                    <h3 class="text-xl font-bold text-gray-900 mb-2">Custom Request</h3>
                                    <p class="text-gray-700 font-semibold">Order #{{ $customRequest->order_number }}</p>
                                    <p class="text-gray-600 text-sm mt-1">Date: {{ $customRequest->created_at->format('d M Y') }}</p>
                                    <p class="text-gray-600 text-sm mt-1">
                                        Status:
                                        @php
                                            $statusConfigs = [
                                                'pending' => ['color' => 'bg-yellow-100 text-yellow-800', 'label' => 'Pending'],
                                                'payment_pending' => ['color' => 'bg-blue-100 text-blue-800', 'label' => 'Payment Pending'],
                                                'paid' => ['color' => 'bg-green-100 text-green-800', 'label' => 'Paid'],
                                                'rejected' => ['color' => 'bg-red-100 text-red-800', 'label' => 'Rejected'],
                                            ];
                                            $config = $statusConfigs[$customRequest->status] ?? $statusConfigs['pending'];
                                        @endphp
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $config['color'] }}">
                                            {{ $config['label'] }}
                                        </span>
                                    </p>
                                </div>
                            </div>

                            <!-- Customer Info -->
                            <div class="mb-8">
                                <h3 class="text-lg font-bold text-gray-900 mb-4">Bill To:</h3>
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                    <p class="font-semibold text-gray-900">{{ $customRequest->customer->name }}</p>
                                    <p class="text-gray-600 text-sm">{{ $customRequest->customer->email }}</p>
                                    @if($customRequest->customer->phone)
                                        <p class="text-gray-600 text-sm">{{ $customRequest->customer->phone }}</p>
                                    @endif
                                    <div class="mt-3 pt-3 border-t border-gray-200">
                                        <p class="text-gray-900 font-medium">{{ $customRequest->recipient_name }}</p>
                                        <p class="text-gray-600 text-sm">{{ $customRequest->recipient_phone }}</p>
                                        <p class="text-gray-600 text-sm">{{ $customRequest->delivery_address }}</p>
                                        <p class="text-gray-600 text-sm">{{ $customRequest->city }} {{ $customRequest->postal_code }}</p>
                                        @if($customRequest->delivery_notes)
                                            <p class="text-gray-600 text-sm mt-2 italic">Note: {{ $customRequest->delivery_notes }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Items Table -->
                            <div class="mb-8">
                                <table class="w-full border-collapse">
                                    <thead>
                                        <tr class="bg-gray-100">
                                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-semibold text-gray-900">Item</th>
                                            <th class="border border-gray-300 px-4 py-3 text-center text-sm font-semibold text-gray-900">Qty</th>
                                            @if($customRequest->price)
                                                <th class="border border-gray-300 px-4 py-3 text-right text-sm font-semibold text-gray-900">Price</th>
                                                <th class="border border-gray-300 px-4 py-3 text-right text-sm font-semibold text-gray-900">Total</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="border border-gray-300 px-4 py-3">
                                                <div class="font-semibold text-gray-900">{{ $customRequest->title }}</div>
                                                @if($customRequest->description)
                                                    <div class="text-sm text-gray-600 mt-1">{{ $customRequest->description }}</div>
                                                @endif
                                                <div class="flex items-center mt-2">
                                                    <div class="p-1.5 rounded mr-2" style="background-color: {{ $customRequest->foodCategory->color }}20">
                                                        <i class="{{ $customRequest->foodCategory->icon }} text-sm" style="color: {{ $customRequest->foodCategory->color }}"></i>
                                                    </div>
                                                    <span class="text-xs text-gray-500">{{ $customRequest->foodCategory->name }}</span>
                                                </div>
                                                @if($customRequest->notes)
                                                    <div class="text-xs text-gray-500 mt-2 italic">Note: {{ $customRequest->notes }}</div>
                                                @endif
                                                <div class="text-xs text-gray-500 mt-1">Needed: {{ $customRequest->needed_date->format('d M Y') }}</div>
                                            </td>
                                            <td class="border border-gray-300 px-4 py-3 text-center text-gray-700">
                                                {{ number_format($customRequest->quantity, 2) }} {{ $customRequest->unit }}
                                            </td>
                                            @if($customRequest->price)
                                                <td class="border border-gray-300 px-4 py-3 text-right text-gray-700">
                                                    Rp {{ number_format($customRequest->price, 0, ',', '.') }}
                                                </td>
                                                <td class="border border-gray-300 px-4 py-3 text-right font-semibold text-gray-900">
                                                    Rp {{ number_format($customRequest->price * $customRequest->quantity, 0, ',', '.') }}
                                                </td>
                                            @endif
                                        </tr>
                                    </tbody>
                                    @if($customRequest->price)
                                        <tfoot>
                                            <tr>
                                                <td colspan="3" class="border border-gray-300 px-4 py-3 text-right font-bold text-gray-900">Grand Total:</td>
                                                <td class="border border-gray-300 px-4 py-3 text-right font-bold text-xl text-gray-900">
                                                    Rp {{ number_format($customRequest->price * $customRequest->quantity, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        </tfoot>
                                    @endif
                                </table>
                            </div>

                            <!-- Footer -->
                            <div class="text-center text-sm text-gray-600 pt-8 border-t border-gray-200 print:hidden">
                                <p>Thank you for your business!</p>
                            </div>
                        </div>
                    </div>

                    <!-- Approve Confirmation Modal -->
                    <div id="approveConfirmModal" class="fixed inset-0 z-[100] hidden" style="display: none;">
                        <div class="fixed inset-0 transition-opacity" style="background-color: rgba(0, 0, 0, 0.5);" onclick="closeApproveConfirmModal()"></div>
                        <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none" style="left: 0; right: 0; top: 0; bottom: 0;">
                            <div class="bg-white rounded-lg shadow-xl max-w-md w-full pointer-events-auto" onclick="event.stopPropagation()">
                                <div class="p-6">
                                    <div class="flex items-center mb-4">
                                        <div class="flex items-center justify-center w-12 h-12 rounded-full bg-green-100 mr-4">
                                            <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                                        </div>
                                        <h3 class="text-xl font-semibold text-gray-900">Confirm Approval</h3>
                                    </div>
                                    <p class="text-gray-700 mb-6">Are you sure you want to approve this request and assign it to the selected supplier?</p>
                                    <div class="flex justify-end gap-3">
                                        <button onclick="closeApproveConfirmModal()" class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors font-medium">
                                            Cancel
                                        </button>
                                        <button onclick="confirmApprove()" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium">
                                            <i class="fas fa-check mr-2"></i>Confirm & Approve
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Reject Confirmation Modal -->
                    <div id="rejectConfirmModal" class="fixed inset-0 z-[100] hidden" style="display: none;">
                        <div class="fixed inset-0 transition-opacity" style="background-color: rgba(0, 0, 0, 0.5);" onclick="closeRejectConfirmModal()"></div>
                        <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none" style="left: 0; right: 0; top: 0; bottom: 0;">
                            <div class="bg-white rounded-lg shadow-xl max-w-md w-full pointer-events-auto" onclick="event.stopPropagation()">
                                <div class="p-6">
                                    <div class="flex items-center mb-4">
                                        <div class="flex items-center justify-center w-12 h-12 rounded-full bg-red-100 mr-4">
                                            <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                                        </div>
                                        <h3 class="text-xl font-semibold text-gray-900">Confirm Rejection</h3>
                                    </div>
                                    <p class="text-gray-700 mb-6">Are you sure you want to reject this request?</p>
                                    <div class="flex justify-end gap-3">
                                        <button onclick="closeRejectConfirmModal()" class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors font-medium">
                                            Cancel
                                        </button>
                                        <button onclick="confirmReject()" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium">
                                            <i class="fas fa-times mr-2"></i>Confirm & Reject
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Admin Actions -->
                    @if($customRequest->status === 'pending')
                        <div class="bg-white rounded-lg shadow">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h2 class="text-xl font-semibold text-gray-900">Admin Actions</h2>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Approve Form -->
                                    <div class="border border-green-200 rounded-lg p-4 bg-green-50">
                                        <h3 class="text-lg font-semibold text-green-900 mb-4">
                                            <i class="fas fa-check-circle mr-2"></i>Approve Request
                                        </h3>
                                        <form method="POST" action="{{ route('admin.custom-requests.approve', $customRequest) }}" id="approveForm">
                                            @csrf
                                            <div class="mb-4">
                                                <label for="assigned_supplier_id" class="block text-sm font-medium text-gray-700 mb-2">
                                                    Assign Supplier <span class="text-red-500">*</span>
                                                </label>
                                                <select name="assigned_supplier_id" id="assigned_supplier_id" required
                                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                                    <option value="">Select Supplier</option>
                                                    @foreach($suppliers as $supplier)
                                                        <option value="{{ $supplier->id }}" {{ old('assigned_supplier_id') == $supplier->id ? 'selected' : '' }}>
                                                            {{ $supplier->name }} ({{ $supplier->email }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('assigned_supplier_id')
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="mb-4">
                                                <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                                                    Price per {{ $customRequest->unit }} <span class="text-red-500">*</span>
                                                </label>
                                                <div class="relative">
                                                    <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                                                    <input type="number" name="price" id="price" required step="0.01" min="0"
                                                           value="{{ old('price') }}"
                                                           placeholder="0.00"
                                                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                                </div>
                                                @error('price')
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                                <p class="mt-1 text-xs text-gray-500">
                                                    Total: <span id="totalPrice" class="font-semibold">Rp 0</span>
                                                    ({{ number_format($customRequest->quantity, 2) }} {{ $customRequest->unit }} × Rp <span id="pricePerUnit">0</span>)
                                                </p>
                                            </div>
                                            <div class="mb-4">
                                                <label for="approve_admin_notes" class="block text-sm font-medium text-gray-700 mb-2">
                                                    Admin Notes (Optional)
                                                </label>
                                                <textarea name="admin_notes" id="approve_admin_notes" rows="3"
                                                          placeholder="Add any notes for this approval..."
                                                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">{{ old('admin_notes') }}</textarea>
                                            </div>
                                            <button type="button"
                                                    onclick="showApproveConfirmModal()"
                                                    class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold">
                                                <i class="fas fa-check mr-2"></i>Approve & Assign Supplier
                                            </button>
                                        </form>
                                    </div>

                                    <!-- Reject Form -->
                                    <div class="border border-red-200 rounded-lg p-4 bg-red-50">
                                        <h3 class="text-lg font-semibold text-red-900 mb-4">
                                            <i class="fas fa-times-circle mr-2"></i>Reject Request
                                        </h3>
                                        <form method="POST" action="{{ route('admin.custom-requests.reject', $customRequest) }}" id="rejectForm">
                                            @csrf
                                            <div class="mb-4">
                                                <label for="reject_admin_notes" class="block text-sm font-medium text-gray-700 mb-2">
                                                    Rejection Reason <span class="text-red-500">*</span>
                                                </label>
                                                <textarea name="admin_notes" id="reject_admin_notes" rows="3" required
                                                          placeholder="Please provide a reason for rejection..."
                                                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">{{ old('admin_notes') }}</textarea>
                                                @error('admin_notes')
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <button type="button"
                                                    onclick="showRejectConfirmModal()"
                                                    class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-semibold">
                                                <i class="fas fa-times mr-2"></i>Reject Request
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Show Current Status -->
                        <div class="bg-white rounded-lg shadow">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h2 class="text-xl font-semibold text-gray-900">Request Status</h2>
                            </div>
                            <div class="p-6">
                                @if($customRequest->assignedSupplier)
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Assigned Supplier</label>
                                        <p class="text-gray-900 font-semibold">{{ $customRequest->assignedSupplier->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $customRequest->assignedSupplier->email }}</p>
                                    </div>
                                @endif
                                @if($customRequest->admin_notes)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Admin Notes</label>
                                        <p class="text-gray-900">{{ $customRequest->admin_notes }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if(session('status'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const status = @json(session('status'));
            if (status && status.message) {
                const alertType = status.type === 'error' ? 'error' : 'success';
                const alertColor = alertType === 'error' ? 'red' : 'green';

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
    </script>
@endif

@if($errors->any())
    <script>
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
    </script>
@endif

@push('scripts')
<script>
// Show approve confirmation modal
function showApproveConfirmModal() {
    // Validate form first
    const form = document.getElementById('approveForm');
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    const modal = document.getElementById('approveConfirmModal');
    if (!modal) return;

    // Disable body scroll
    document.body.style.overflow = 'hidden';

    // Calculate center position considering sidebar on desktop
    const isDesktop = window.innerWidth >= 1024;
    const sidebarWidth = isDesktop ? 256 : 0;
    const viewportWidth = window.innerWidth;
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

// Close approve confirmation modal
function closeApproveConfirmModal() {
    const modal = document.getElementById('approveConfirmModal');
    if (modal) {
        document.body.style.overflow = '';
        modal.classList.add('hidden');
        modal.style.display = 'none';
    }
}

// Confirm approve and submit form
function confirmApprove() {
    const form = document.getElementById('approveForm');
    if (form) {
        closeApproveConfirmModal();
        form.submit();
    }
}

// Show reject confirmation modal
function showRejectConfirmModal() {
    // Validate form first
    const form = document.getElementById('rejectForm');
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    const modal = document.getElementById('rejectConfirmModal');
    if (!modal) return;

    // Disable body scroll
    document.body.style.overflow = 'hidden';

    // Calculate center position considering sidebar on desktop
    const isDesktop = window.innerWidth >= 1024;
    const sidebarWidth = isDesktop ? 256 : 0;
    const viewportWidth = window.innerWidth;
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

// Close reject confirmation modal
function closeRejectConfirmModal() {
    const modal = document.getElementById('rejectConfirmModal');
    if (modal) {
        document.body.style.overflow = '';
        modal.classList.add('hidden');
        modal.style.display = 'none';
    }
}

// Confirm reject and submit form
function confirmReject() {
    const form = document.getElementById('rejectForm');
    if (form) {
        closeRejectConfirmModal();
        form.submit();
    }
}

// Close modals on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeApproveConfirmModal();
        closeRejectConfirmModal();
    }
});

// Handle window resize to recalculate modal position
let resizeTimeout;
window.addEventListener('resize', function() {
    const approveModal = document.getElementById('approveConfirmModal');
    const rejectModal = document.getElementById('rejectConfirmModal');

    [approveModal, rejectModal].forEach(modal => {
        if (modal && !modal.classList.contains('hidden')) {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(function() {
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
});

// Calculate total price when price input changes
document.addEventListener('DOMContentLoaded', function() {
    const priceInput = document.getElementById('price');
    const pricePerUnitSpan = document.getElementById('pricePerUnit');
    const totalPriceSpan = document.getElementById('totalPrice');
    const quantity = {{ $customRequest->quantity }};

    if (priceInput && pricePerUnitSpan && totalPriceSpan) {
        priceInput.addEventListener('input', function() {
            const price = parseFloat(this.value) || 0;
            const total = price * quantity;

            pricePerUnitSpan.textContent = price.toLocaleString('id-ID', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            totalPriceSpan.textContent = 'Rp ' + total.toLocaleString('id-ID', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        });

        // Trigger on page load if there's an old value
        if (priceInput.value) {
            priceInput.dispatchEvent(new Event('input'));
        }
    }
});
</script>
@endpush
@endsection

