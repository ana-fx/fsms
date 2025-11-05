
@extends('layouts.app')

@section('title', 'Requests - FSMS')

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
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">Requests</h1>
                            <p class="mt-2 text-gray-600">Manage all your ingredient requests and track order status</p>
                        </div>
                        <a href="{{ route('customer.requests.custom.create') }}" class="hidden sm:flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold">
                            <i class="fas fa-plus-circle mr-2"></i>
                            Custom Request
                        </a>
                    </div>
                </div>

                <!-- Requests List -->
                <div class="bg-white rounded-lg shadow mx-4 sm:mx-6 lg:mx-8">
                    <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Food Requests</h3>
                    </div>

            @if($requests->count() > 0)
                @php
                    $statusColors = [
                        'pending' => 'bg-gray-100 text-gray-800',
                        'payment_pending' => 'bg-yellow-100 text-yellow-800',
                        'paid' => 'bg-green-100 text-green-800',
                        'shipping' => 'bg-blue-100 text-blue-800',
                        'delivered' => 'bg-indigo-100 text-indigo-800',
                        'completed' => 'bg-purple-100 text-purple-800',
                        'rejected' => 'bg-red-100 text-red-800',
                    ];
                    $statusLabels = [
                        'pending' => 'Pending',
                        'payment_pending' => 'Pending',
                        'paid' => 'Paid',
                        'shipping' => 'Shipping',
                        'delivered' => 'Delivered',
                        'completed' => 'Completed',
                        'rejected' => 'Rejected',
                    ];
                @endphp

                <!-- Mobile Card View -->
                <div class="block md:hidden divide-y divide-gray-200">
                    @foreach($requests as $request)
                        <div class="p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-start space-x-3 flex-1 min-w-0">
                                    <div class="p-2 rounded-lg flex-shrink-0" style="background-color: {{ $request->foodCategory->color }}20">
                                        <i class="{{ $request->foodCategory->icon }} text-sm" style="color: {{ $request->foodCategory->color }}"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="text-sm font-semibold text-gray-900 truncate">{{ $request->order_number ?? 'N/A' }}</div>
                                        <div class="text-xs text-gray-500 truncate">{{ $request->foodCategory->name }}</div>
                                        <div class="text-xs text-gray-600 mt-1">{{ number_format($request->quantity, 2) }} {{ $request->unit }}</div>
                                    </div>
                                </div>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$request->status] }} flex-shrink-0">
                                    {{ $statusLabels[$request->status] }}
                                </span>
                            </div>

                            <div class="mb-3">
                                <div class="text-sm font-medium text-gray-900 truncate">{{ $request->title }}</div>
                                <div class="text-xs text-gray-500 mt-1">
                                    <i class="fas fa-calendar-alt mr-1"></i>{{ $request->needed_date->format('d M Y') }}
                                </div>
                            </div>

                            <!-- Payment Proof Section Mobile -->
                            @php
                                $hasPaymentProof = $request->payment_proof && $request->payment_proof !== '' && $request->payment_proof !== null;
                                $hasReceivedProof = $request->received_proof && $request->received_proof !== '' && $request->received_proof !== null;
                            @endphp
                            @if($hasPaymentProof || $hasReceivedProof)
                                <div class="mb-3">
                                    <button onclick="openProofsModal({{ $hasPaymentProof ? json_encode(asset('storage/' . $request->payment_proof)) : 'null' }}, {{ $hasPaymentProof ? json_encode($request->payment_proof) : 'null' }}, {{ $hasReceivedProof ? json_encode(asset('storage/' . $request->received_proof)) : 'null' }}, {{ $hasReceivedProof ? json_encode($request->received_proof) : 'null' }}, {{ $request->id }})"
                                            class="inline-flex items-center px-3 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors text-sm font-medium w-full justify-center">
                                        <i class="fas fa-eye mr-2"></i>View
                                    </button>
                                </div>
                            @elseif($request->status === 'payment_pending')
                                <div class="mb-3">
                                    <form method="POST" action="{{ route('customer.requests.upload-payment-proof') }}" enctype="multipart/form-data" class="space-y-2" id="uploadForm{{ $request->id }}">
                                        @csrf
                                        <input type="hidden" name="request_ids[]" value="{{ $request->id }}">
                                        <input type="file"
                                               id="payment_proof_{{ $request->id }}"
                                               name="payment_proof"
                                               accept="image/*,.pdf"
                                               class="block w-full text-xs file:mr-2 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-green-600 file:text-white hover:file:bg-green-700 file:cursor-pointer text-transparent">
                                    </form>
                                </div>
                            @endif

                            <!-- Actions Mobile -->
                            <div class="flex items-center justify-between pt-2 border-t border-gray-100">
                                <div class="flex flex-wrap gap-2">
                                    <a href="{{ route('customer.requests.show', $request) }}" class="inline-flex items-center px-3 py-1.5 bg-green-50 text-green-700 rounded-md hover:bg-green-100 text-sm font-medium transition-colors">
                                        <i class="fas fa-eye mr-1.5 text-xs"></i>Detail
                                    </a>
                                    @if(in_array($request->status, ['pending', 'payment_pending']))
                                        <a href="{{ route('customer.requests.edit', $request) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-700 rounded-md hover:bg-blue-100 text-sm font-medium transition-colors">
                                            <i class="fas fa-edit mr-1.5 text-xs"></i>Edit
                                        </a>
                                        <button onclick="confirmDeleteRequest({{ $request->id }}, '{{ $request->title }}')" class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-700 rounded-md hover:bg-red-100 text-sm font-medium transition-colors">
                                            <i class="fas fa-trash mr-1.5 text-xs"></i>Hapus
                                        </button>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1.5 bg-gray-100 text-gray-400 rounded-md cursor-not-allowed text-sm font-medium" title="Edit (Not available for {{ $statusLabels[$request->status] }})">
                                            <i class="fas fa-edit mr-1.5 text-xs"></i>Edit
                                        </span>
                                        <span class="inline-flex items-center px-3 py-1.5 bg-gray-100 text-gray-400 rounded-md cursor-not-allowed text-sm font-medium" title="Delete (Not available for {{ $statusLabels[$request->status] }})">
                                            <i class="fas fa-trash mr-1.5 text-xs"></i>Hapus
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Desktop Table View -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Order</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Item</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Quantity</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Needed Date</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Payment / Received</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($requests as $request)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <div class="flex items-center space-x-3">
                                            <div class="flex-shrink-0 p-2 rounded-lg" style="background-color: {{ $request->foodCategory->color }}20">
                                                <i class="{{ $request->foodCategory->icon }} text-sm" style="color: {{ $request->foodCategory->color }}"></i>
                                            </div>
                                            <div>
                                                <div class="text-sm font-semibold text-gray-900">{{ $request->order_number ?? 'N/A' }}</div>
                                                <div class="text-xs text-gray-500 mt-0.5">{{ $request->foodCategory->name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="text-sm font-medium text-gray-900 max-w-xs truncate" title="{{ $request->title }}">{{ $request->title }}</div>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ number_format($request->quantity, 2) }} {{ $request->unit }}</div>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full {{ $statusColors[$request->status] }}">
                                            {{ $statusLabels[$request->status] }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-600">{{ $request->needed_date->format('d M Y') }}</div>
                                    </td>
                                    <td class="px-4 py-4">
                                        @php
                                            $hasPaymentProof = $request->payment_proof && $request->payment_proof !== '' && $request->payment_proof !== null;
                                            $hasReceivedProof = $request->received_proof && $request->received_proof !== '' && $request->received_proof !== null;
                                        @endphp
                                        @if($hasPaymentProof || $hasReceivedProof)
                                            <button onclick="openProofsModal({{ $hasPaymentProof ? json_encode(asset('storage/' . $request->payment_proof)) : 'null' }}, {{ $hasPaymentProof ? json_encode($request->payment_proof) : 'null' }}, {{ $hasReceivedProof ? json_encode(asset('storage/' . $request->received_proof)) : 'null' }}, {{ $hasReceivedProof ? json_encode($request->received_proof) : 'null' }}, {{ $request->id }})"
                                                    class="inline-flex items-center px-3 py-1.5 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors text-xs font-medium">
                                                <i class="fas fa-eye mr-1.5"></i>View
                                            </button>
                                        @elseif($request->status === 'payment_pending')
                                            <form method="POST" action="{{ route('customer.requests.upload-payment-proof') }}" enctype="multipart/form-data" class="space-y-2" id="uploadForm{{ $request->id }}">
                                                @csrf
                                                <input type="hidden" name="request_ids[]" value="{{ $request->id }}">
                                                <input type="file"
                                                       id="payment_proof_{{ $request->id }}"
                                                       name="payment_proof"
                                                       accept="image/*,.pdf"
                                                       class="block w-full text-xs file:mr-2 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-green-600 file:text-white hover:file:bg-green-700 file:cursor-pointer text-transparent">
                                            </form>
                                        @else
                                            <span class="text-xs text-gray-400 italic">N/A</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <div class="flex items-center justify-center space-x-1.5">
                                            <a href="{{ route('customer.requests.show', $request) }}" class="inline-flex items-center justify-center w-9 h-9 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition-colors" title="View Details">
                                                <i class="fas fa-eye text-sm"></i>
                                            </a>
                                            @if(in_array($request->status, ['pending', 'payment_pending']))
                                                @if($request->food_item_id !== null)
                                                    <a href="{{ route('customer.requests.edit', $request) }}" class="inline-flex items-center justify-center w-9 h-9 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-colors" title="Edit">
                                                        <i class="fas fa-edit text-sm"></i>
                                                    </a>
                                                @else
                                                    <span class="inline-flex items-center justify-center w-9 h-9 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed" title="Edit (Not available for custom requests)">
                                                        <i class="fas fa-edit text-sm"></i>
                                                    </span>
                                                @endif
                                                <button onclick="confirmDeleteRequest({{ $request->id }}, '{{ $request->title }}')" class="inline-flex items-center justify-center w-9 h-9 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 transition-colors" title="Delete">
                                                    <i class="fas fa-trash text-sm"></i>
                                                </button>
                                            @else
                                                <span class="inline-flex items-center justify-center w-9 h-9 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed" title="Edit (Not available for {{ $statusLabels[$request->status] }})">
                                                    <i class="fas fa-edit text-sm"></i>
                                                </span>
                                                <span class="inline-flex items-center justify-center w-9 h-9 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed" title="Delete (Not available for {{ $statusLabels[$request->status] }})">
                                                    <i class="fas fa-trash text-sm"></i>
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                    <!-- Pagination -->
                    <div class="px-4 sm:px-6 py-4 border-t border-gray-200">
                        {{ $requests->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-inbox text-4xl text-gray-400 mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No requests yet</h3>
                        <p class="text-gray-500 mb-6">Start by adding items to your cart and proceed to checkout, or create a custom request</p>
                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <a href="{{ route('customer.ingredients') }}" class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors font-semibold">
                                <i class="fas fa-shopping-cart mr-2"></i>
                                Browse Ingredients
                            </a>
                            <a href="{{ route('customer.requests.custom.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                                <i class="fas fa-plus-circle mr-2"></i>
                                Create Custom Request
                            </a>
                        </div>
                    </div>
                @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div id="confirmModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 hidden items-center justify-center" style="display: none;">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
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

<!-- Payment Proof Modal -->
<div id="paymentProofModal" class="fixed inset-0 z-[100] hidden" style="display: none;">
    <!-- Background overlay - transparent -->
    <div class="fixed inset-0 bg-transparent transition-opacity" onclick="closePaymentProofModal()"></div>

    <!-- Modal panel - centered in content area (accounting for sidebar) -->
    <div class="fixed inset-0 flex items-center justify-center p-2 sm:p-4 pointer-events-none" style="left: 0; right: 0; top: 0; bottom: 0;">
        <div class="relative w-full max-w-4xl max-h-[90vh] transform overflow-hidden rounded-lg bg-white shadow-2xl transition-all pointer-events-auto" onclick="event.stopPropagation()" style="margin: 0 auto;">
            <!-- Header -->
            <div class="flex items-center justify-between border-b border-gray-200 px-4 py-3 sm:px-6 sm:py-4">
                <h3 class="text-base sm:text-lg font-semibold text-gray-900">Payment Proof</h3>
                <button onclick="closePaymentProofModal()" class="text-gray-400 hover:text-gray-500 transition-colors p-1" type="button">
                    <i class="fas fa-times text-lg sm:text-xl"></i>
                </button>
            </div>

            <!-- Content - scrollable -->
            <div class="px-4 py-3 sm:px-6 sm:py-4 overflow-y-auto" id="paymentProofContent" style="max-height: calc(90vh - 140px);">
                <div class="flex items-center justify-center py-8">
                    <div class="text-gray-500">
                        <i class="fas fa-spinner fa-spin text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="flex justify-center border-t border-gray-200 px-4 py-3 sm:px-6 sm:py-4" id="paymentProofFooter">
                <a href="#" id="viewInvoiceLink" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors text-sm font-medium inline-flex items-center">
                    <i class="fas fa-file-invoice mr-2"></i>View Invoice
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openProofsModal(paymentUrl, paymentFileName, receivedUrl, receivedFileName, requestId) {
    // Disable body scroll
    document.body.style.overflow = 'hidden';

    const modal = document.getElementById('paymentProofModal');
    const content = document.getElementById('paymentProofContent');
    const viewInvoiceLink = document.getElementById('viewInvoiceLink');

    // Set invoice link
    if (viewInvoiceLink && requestId) {
        viewInvoiceLink.href = `/customer/requests/${requestId}`;
    }

    // Calculate center position considering sidebar on desktop
    const isDesktop = window.innerWidth >= 1024; // lg breakpoint
    const sidebarWidth = isDesktop ? 256 : 0; // 64 * 4 = 256px (lg:ml-64)

    // Get viewport dimensions
    const viewportWidth = window.innerWidth;
    const viewportHeight = window.innerHeight;

    // Calculate available width (viewport minus sidebar)
    const availableWidth = viewportWidth - sidebarWidth;

    // Center modal in available content area
    const modalContainer = modal.querySelector('.fixed.inset-0.flex');
    if (modalContainer) {
        modalContainer.style.left = sidebarWidth + 'px';
        modalContainer.style.width = availableWidth + 'px';
    }

    // Build content HTML for both proofs
    let contentHTML = '<div class="space-y-6">';

    // Payment Proof Section
    if (paymentUrl && paymentFileName) {
        const paymentIsPDF = paymentFileName.toLowerCase().endsWith('.pdf');
        const paymentImageUrl = paymentUrl.replace(/ /g, '%20');

        contentHTML += `
            <div class="border-b border-gray-200 pb-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                    <i class="fas fa-money-bill-wave text-green-600 mr-2"></i>Payment Proof
                </h3>
        `;

        if (paymentIsPDF) {
            contentHTML += `
                <div class="w-full" style="height: 60vh; min-height: 300px;">
                    <iframe src="${paymentImageUrl}" class="w-full h-full border border-gray-300 rounded-md" frameborder="0"></iframe>
                </div>
                <div class="mt-3 text-center">
                    <a href="${paymentImageUrl}" download class="inline-flex items-center px-3 py-1.5 sm:px-4 sm:py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors text-xs sm:text-sm font-medium">
                        <i class="fas fa-download mr-2"></i>Download Payment PDF
                    </a>
                </div>
            `;
        } else {
            contentHTML += `
                <div class="flex flex-col items-center justify-center min-h-[200px]">
                    <div class="mb-3 sm:mb-4 w-full flex justify-center">
                        <img src="${paymentImageUrl}"
                             alt="Payment Proof"
                             class="max-w-full h-auto rounded-md shadow-lg border border-gray-200 w-full object-contain"
                             style="max-height: 50vh;"
                             onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\\'text-red-600 text-center py-4\\'><i class=\\'fas fa-exclamation-triangle text-xl mb-2\\'></i><p class=\\'text-sm\\'>Gambar tidak dapat dimuat</p></div>'">
                    </div>
                    <div class="mt-2 sm:mt-3">
                        <a href="${paymentImageUrl}" download class="inline-flex items-center px-3 py-1.5 sm:px-4 sm:py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors text-xs sm:text-sm font-medium">
                            <i class="fas fa-download mr-2"></i>Download Payment Image
                        </a>
                    </div>
                </div>
            `;
        }

        contentHTML += '</div>';
    }

    // Received Proof Section
    if (receivedUrl && receivedFileName) {
        const receivedIsPDF = receivedFileName.toLowerCase().endsWith('.pdf');
        const receivedImageUrl = receivedUrl.replace(/ /g, '%20');

        contentHTML += `
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                    <i class="fas fa-box-check text-green-600 mr-2"></i>Received Items Proof
                </h3>
        `;

        if (receivedIsPDF) {
            contentHTML += `
                <div class="w-full" style="height: 60vh; min-height: 300px;">
                    <iframe src="${receivedImageUrl}" class="w-full h-full border border-gray-300 rounded-md" frameborder="0"></iframe>
                </div>
                <div class="mt-3 text-center">
                    <a href="${receivedImageUrl}" download class="inline-flex items-center px-3 py-1.5 sm:px-4 sm:py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors text-xs sm:text-sm font-medium">
                        <i class="fas fa-download mr-2"></i>Download Received PDF
                    </a>
                </div>
            `;
        } else {
            contentHTML += `
                <div class="flex flex-col items-center justify-center min-h-[200px]">
                    <div class="mb-3 sm:mb-4 w-full flex justify-center">
                        <img src="${receivedImageUrl}"
                             alt="Received Items Proof"
                             class="max-w-full h-auto rounded-md shadow-lg border border-gray-200 w-full object-contain"
                             style="max-height: 50vh;"
                             onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\\'text-red-600 text-center py-4\\'><i class=\\'fas fa-exclamation-triangle text-xl mb-2\\'></i><p class=\\'text-sm\\'>Gambar tidak dapat dimuat</p></div>'">
                    </div>
                    <div class="mt-2 sm:mt-3">
                        <a href="${receivedImageUrl}" download class="inline-flex items-center px-3 py-1.5 sm:px-4 sm:py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors text-xs sm:text-sm font-medium">
                            <i class="fas fa-download mr-2"></i>Download Received Image
                        </a>
                    </div>
                </div>
            `;
        }

        contentHTML += '</div>';
    }

    // If no proofs available
    if (!paymentUrl && !receivedUrl) {
        contentHTML = `
            <div class="text-center py-8">
                <i class="fas fa-exclamation-circle text-gray-400 text-4xl mb-4"></i>
                <p class="text-gray-600">No proofs available</p>
            </div>
        `;
    }

    contentHTML += '</div>';

    content.innerHTML = contentHTML;

    // Show modal
    modal.classList.remove('hidden');
    modal.style.display = 'block';
}

// Keep old function for backward compatibility
function openPaymentProofModal(url, fileName) {
    openProofsModal(url, fileName, null, null, null);
}

function closePaymentProofModal() {
    // Enable body scroll
    document.body.style.overflow = '';

    const modal = document.getElementById('paymentProofModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';

    // Clear content when closing
    document.getElementById('paymentProofContent').innerHTML = '<div class="flex items-center justify-center py-8"><div class="text-gray-500"><i class="fas fa-spinner fa-spin text-2xl"></i></div></div>';
}

function confirmDeleteRequest(id, title) {
    const modal = document.getElementById('confirmModal');
    document.getElementById('confirmMessage').textContent = `Are you sure you want to delete "${title}"? This action cannot be undone.`;
    document.getElementById('deleteForm').action = `/customer/requests/${id}`;
    modal.classList.remove('hidden');
    modal.style.display = 'flex';
}

function closeConfirmModal() {
    const modal = document.getElementById('confirmModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
    document.getElementById('deleteForm').action = '';
}

// Initialize file upload handlers after page load
document.addEventListener('DOMContentLoaded', function() {
    // Find all file inputs for payment proof
    const fileInputs = document.querySelectorAll('input[id^="payment_proof_"]');

    fileInputs.forEach(function(input) {
        input.addEventListener('change', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const inputId = this.id;
            const requestId = inputId.replace('payment_proof_', '');
            const form = document.getElementById('uploadForm' + requestId);
            const file = this.files[0];

            if (!file || !form) {
                console.error('File or form not found', {file: file, form: form});
                return;
            }

            console.log('File selected:', {
                requestId: requestId,
                fileName: file.name,
                fileSize: file.size,
                fileType: file.type
            });

            // Validate file size (5MB max)
            const maxSize = 5 * 1024 * 1024; // 5MB in bytes
            if (file.size > maxSize) {
                alert('File size exceeds 5MB limit. Please choose a smaller file.');
                this.value = '';
                return;
            }

            // Validate file type
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
            if (!allowedTypes.includes(file.type)) {
                alert('Invalid file type. Please upload JPG, PNG, or PDF files only.');
                this.value = '';
                return;
            }

            // Show loading indicator
            this.disabled = true;
            this.style.opacity = '0.5';
            this.style.cursor = 'not-allowed';

            // Create or update loading message
            let loadingDiv = document.getElementById('uploadLoading' + requestId);
            if (!loadingDiv) {
                loadingDiv = document.createElement('div');
                loadingDiv.id = 'uploadLoading' + requestId;
                loadingDiv.className = 'text-xs text-green-600 mt-1 flex items-center';
                form.appendChild(loadingDiv);
            }
            loadingDiv.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Uploading...';

            // Get CSRF token from form
            const csrfToken = form.querySelector('input[name="_token"]')?.value;
            if (!csrfToken) {
                console.error('CSRF token not found');
                alert('Error: Security token not found. Please refresh the page and try again.');
                this.disabled = false;
                this.style.opacity = '1';
                this.style.cursor = 'pointer';
                if (loadingDiv) loadingDiv.remove();
                return;
            }

            // Get request IDs from form
            const requestIdsInput = form.querySelector('input[name="request_ids[]"]');
            if (!requestIdsInput) {
                console.error('Request ID not found in form');
                alert('Error: Request ID not found. Please refresh the page and try again.');
                this.disabled = false;
                this.style.opacity = '1';
                this.style.cursor = 'pointer';
                if (loadingDiv) loadingDiv.remove();
                return;
            }

            // Build FormData explicitly
            const formData = new FormData();
            formData.append('_token', csrfToken);
            formData.append('request_ids[]', requestIdsInput.value);
            formData.append('payment_proof', file);

            console.log('FormData created:', {
                hasToken: formData.has('_token'),
                hasRequestIds: formData.has('request_ids[]'),
                hasFile: formData.has('payment_proof'),
                fileSize: file.size
            });

            // Submit using fetch API
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                }
            })
            .then(response => {
                console.log('Response received:', response.status, response.statusText);
                if (response.redirected) {
                    window.location.href = response.url;
                    return;
                }
                return response.json().catch(() => response.text());
            })
            .then(data => {
                console.log('Response data:', data);
                if (data && data.message) {
                    window.location.href = form.action.replace('/upload-payment-proof', '/requests');
                } else {
                    window.location.reload();
                }
            })
            .catch(error => {
                console.error('Upload error:', error);
                alert('Error uploading file: ' + error.message);
                this.disabled = false;
                this.style.opacity = '1';
                this.style.cursor = 'pointer';
                if (loadingDiv) loadingDiv.remove();
            });
        });
    });
});

// Close modals when clicking outside
document.getElementById('confirmModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeConfirmModal();
    }
});

document.getElementById('paymentProofModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closePaymentProofModal();
    }
});

// Close modal on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closePaymentProofModal();
        closeConfirmModal();
    }
});

// Handle window resize to keep modal centered
let resizeTimeout;
window.addEventListener('resize', function() {
    const modal = document.getElementById('paymentProofModal');
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

@if(session('status'))
    document.addEventListener('DOMContentLoaded', function() {
        const status = @json(session('status'));
        if (status && status.message) {
            const alertType = status.type === 'error' ? 'error' : 'success';
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
@endpush
@endsection
