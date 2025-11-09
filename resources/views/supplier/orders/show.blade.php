@extends('layouts.app')

@section('title', 'Invoice - ' . $order->order_number)

@section('content')
<div class="flex bg-gray-100 min-h-screen w-full overflow-x-hidden">
    @include('supplier.partials.sidebar')

    <!-- Mobile Menu Button -->
    <button id="openSidebar" class="lg:hidden fixed top-4 left-4 z-50 p-2 bg-white rounded-lg shadow-lg hover:bg-gray-100 transition-colors print:hidden">
        <i class="fas fa-bars text-gray-700 text-xl"></i>
    </button>

    <!-- Main Content -->
    <div class="w-full lg:ml-64 transition-all duration-300">
        <div class="flex-1 bg-white min-h-screen">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <!-- Invoice Card -->
                <div class="bg-white border-2 border-gray-200 rounded-lg shadow-sm mb-8 print:shadow-none">
                    <!-- Header with Print Button -->
                    <div class="print:hidden border-b border-gray-200 p-4 bg-gray-50 flex justify-between items-center">
                        <div class="flex items-center">
                            <a href="{{ route('supplier.orders.index') }}" class="text-gray-500 hover:text-gray-700 mr-4">
                                <i class="fas fa-arrow-left text-xl"></i>
                            </a>
                            <h1 class="text-2xl font-bold text-gray-900">Invoice</h1>
                        </div>
                        <button onclick="window.print()" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition-colors font-semibold">
                            <i class="fas fa-print mr-2"></i>Print Invoice
                        </button>
                    </div>

                    <div class="p-8">
                        <!-- Supplier & Invoice Info -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8 pb-8 border-b-2 border-gray-300">
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ auth()->user()->name }}</h2>
                                <p class="text-gray-600 text-sm">{{ auth()->user()->email }}</p>
                                @if(auth()->user()->phone)
                                    <p class="text-gray-600 text-sm mt-1"><i class="fas fa-phone mr-1"></i>{{ auth()->user()->phone }}</p>
                                @endif
                            </div>
                            <div class="text-right">
                                <h3 class="text-xl font-bold text-gray-900 mb-2">Invoice</h3>
                                <p class="text-gray-700 font-semibold">Order #{{ $order->order_number }}</p>
                                <p class="text-gray-600 text-sm mt-1">Date: {{ $order->created_at->format('d M Y') }}</p>
                                @php
                                    $statusConfigs = [
                                        'pending' => ['color' => 'bg-gray-100 text-gray-800', 'label' => 'Pending'],
                                        'payment_pending' => ['color' => 'bg-yellow-100 text-yellow-800', 'label' => 'Payment Pending'],
                                        'paid' => ['color' => 'bg-green-100 text-green-800', 'label' => 'Paid'],
                                        'shipping' => ['color' => 'bg-blue-100 text-blue-800', 'label' => 'Shipping'],
                                        'delivered' => ['color' => 'bg-indigo-100 text-indigo-800', 'label' => 'Delivered'],
                                        'completed' => ['color' => 'bg-purple-100 text-purple-800', 'label' => 'Completed'],
                                        'rejected' => ['color' => 'bg-red-100 text-red-800', 'label' => 'Rejected'],
                                    ];
                                    $config = $statusConfigs[$order->status] ?? $statusConfigs['pending'];
                                @endphp
                                <p class="text-gray-600 text-sm mt-2">
                                    Status:
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $config['color'] }}">
                                        {{ $config['label'] }}
                                    </span>
                                </p>
                            </div>
                        </div>

                        <!-- Customer Info (Bill To) -->
                        <div class="mb-8">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Bill To:</h3>
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <p class="font-semibold text-gray-900">{{ $order->customer->name }}</p>
                                <p class="text-gray-600 text-sm">{{ $order->customer->email }}</p>
                                @if($order->customer->phone)
                                    <p class="text-gray-600 text-sm mt-1"><i class="fas fa-phone mr-1"></i>{{ $order->customer->phone }}</p>
                                @endif
                                <div class="mt-3 pt-3 border-t border-gray-200">
                                    <p class="text-gray-900 font-medium">{{ $order->recipient_name }}</p>
                                    <p class="text-gray-600 text-sm">{{ $order->recipient_phone }}</p>
                                    <p class="text-gray-600 text-sm">{{ $order->delivery_address }}</p>
                                    <p class="text-gray-600 text-sm">{{ $order->city }} {{ $order->postal_code ?? '' }}</p>
                                    @if($order->needed_date)
                                        <p class="text-gray-600 text-sm mt-2">
                                            <span class="font-medium">Needed Date:</span>
                                            @if($order->needed_date instanceof \Carbon\Carbon)
                                                {{ $order->needed_date->format('d M Y') }}
                                            @else
                                                {{ \Carbon\Carbon::parse($order->needed_date)->format('d M Y') }}
                                            @endif
                                        </p>
                                    @endif
                                    @if($order->delivery_notes && !empty($order->delivery_notes))
                                        <div class="mt-2 pt-2 border-t border-gray-200">
                                            <p class="text-gray-900 font-medium text-sm mb-1">Delivery Notes:</p>
                                            <p class="text-gray-600 text-sm whitespace-pre-wrap">{{ $order->delivery_notes }}</p>
                                        </div>
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
                                        <th class="border border-gray-300 px-4 py-3 text-right text-sm font-semibold text-gray-900">Price</th>
                                        <th class="border border-gray-300 px-4 py-3 text-right text-sm font-semibold text-gray-900">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($order->foodItem)
                                        <tr>
                                            <td class="border border-gray-300 px-4 py-3">
                                                <div class="font-semibold text-gray-900">{{ $order->foodItem->name }}</div>
                                                @if($order->foodItem->description)
                                                    <div class="text-sm text-gray-600">{{ Str::limit($order->foodItem->description, 60) }}</div>
                                                @endif
                                                <div class="text-xs text-gray-500 mt-1">{{ $order->foodItem->foodCategory->name }}</div>
                                            </td>
                                            <td class="border border-gray-300 px-4 py-3 text-center text-gray-700">
                                                {{ number_format($order->quantity, 2) }} {{ $order->unit }}
                                            </td>
                                            <td class="border border-gray-300 px-4 py-3 text-right text-gray-700">
                                                Rp {{ number_format($order->foodItem->price, 0, ',', '.') }}
                                            </td>
                                            <td class="border border-gray-300 px-4 py-3 text-right font-semibold text-gray-900">
                                                Rp {{ number_format($order->foodItem->price * $order->quantity, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td class="border border-gray-300 px-4 py-3">
                                                <div class="font-semibold text-gray-900">{{ $order->title }}</div>
                                                @if($order->description)
                                                    <div class="text-sm text-gray-600">{{ Str::limit($order->description, 60) }}</div>
                                                @endif
                                                @if($order->foodCategory)
                                                    <div class="text-xs text-gray-500 mt-1">{{ $order->foodCategory->name }}</div>
                                                @endif
                                            </td>
                                            <td class="border border-gray-300 px-4 py-3 text-center text-gray-700">
                                                {{ number_format($order->quantity, 2) }} {{ $order->unit }}
                                            </td>
                                            <td class="border border-gray-300 px-4 py-3 text-right text-gray-700">
                                                @if($order->price)
                                                    Rp {{ number_format($order->price, 0, ',', '.') }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td class="border border-gray-300 px-4 py-3 text-right font-semibold text-gray-900">
                                                @if($order->price)
                                                    Rp {{ number_format($order->price * $order->quantity, 0, ',', '.') }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="border border-gray-300 px-4 py-3 text-right font-bold text-gray-900">Grand Total:</td>
                                        <td class="border border-gray-300 px-4 py-3 text-right font-bold text-xl text-gray-900">
                                            @if($order->foodItem)
                                                Rp {{ number_format($order->foodItem->price * $order->quantity, 0, ',', '.') }}
                                            @elseif($order->price)
                                                Rp {{ number_format($order->price * $order->quantity, 0, ',', '.') }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <!-- Footer -->
                        <div class="text-center text-sm text-gray-600 pt-8 border-t border-gray-200 print:hidden">
                            <p>Thank you for your business!</p>
                        </div>
                    </div>
                </div>

                <!-- Payment Proof -->
                @if($order->payment_proof)
                    <div class="bg-white border-2 border-gray-200 rounded-lg shadow-sm mb-8 print:hidden">
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-4">Payment Proof</h3>
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold">
                                    <i class="fas fa-eye mr-2"></i>View Payment Proof
                                </a>
                                @if($order->payment_proof_uploaded_at)
                                    <p class="text-xs text-gray-500 mt-2">Uploaded: {{ $order->payment_proof_uploaded_at->format('d M Y H:i') }}</p>
                                @endif
                                @php
                                    // Extract payment notes from notes field
                                    $paymentNotes = null;
                                    if ($order->notes) {
                                        // Match "Payment Notes: " followed by content until "Delivery Notes:" or end of string
                                        if (preg_match('/Payment Notes:\s*(.+?)(?:\n\nDelivery Notes:|$)/s', $order->notes, $matches)) {
                                            $paymentNotes = trim($matches[1]);
                                        }
                                    }
                                @endphp
                                @if($paymentNotes)
                                    <div class="mt-3 pt-3 border-t border-gray-300">
                                        <p class="text-sm font-medium text-gray-700 mb-1">Payment Notes:</p>
                                        <p class="text-sm text-gray-600 whitespace-pre-wrap">{{ $paymentNotes }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Received Items Information -->
                <div class="bg-white border-2 border-gray-200 rounded-lg shadow-sm mb-8 print:hidden">
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Received Items Information</h3>
                        @if($order->received_proof)
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 mb-4">
                                <a href="{{ asset('storage/' . $order->received_proof) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold">
                                    <i class="fas fa-eye mr-2"></i>View Received Items Proof
                                </a>
                                @if($order->received_proof_uploaded_at)
                                    <p class="text-xs text-gray-500 mt-2">Uploaded: {{ $order->received_proof_uploaded_at->format('d M Y H:i') }}</p>
                                @endif
                                @php
                                    // Extract delivery notes from notes field
                                    $deliveryNotes = null;
                                    if ($order->notes) {
                                        // Match "Delivery Notes: " followed by content until end of string
                                        if (preg_match('/Delivery Notes:\s*(.+?)$/s', $order->notes, $matches)) {
                                            $deliveryNotes = trim($matches[1]);
                                        }
                                    }
                                @endphp
                                @if($deliveryNotes)
                                    <div class="mt-3 pt-3 border-t border-gray-300">
                                        <p class="text-sm font-medium text-gray-700 mb-1">Received Items Notes:</p>
                                        <p class="text-sm text-gray-600 whitespace-pre-wrap">{{ $deliveryNotes }}</p>
                                    </div>
                                @endif
                            </div>
                        @endif

                        @if($order->status === 'paid')
                            <!-- Upload Form (Only shown if no received_proof exists) -->
                            <div id="uploadReceivedProofForm{{ $order->id }}" class="{{ $order->received_proof ? 'hidden' : '' }}">
                                <form method="POST" action="{{ route('supplier.orders.upload-delivery-proof', $order) }}" enctype="multipart/form-data" class="space-y-4">
                                    @csrf
                                    <div>
                                        <label for="received_proof" class="block text-sm font-medium text-gray-700 mb-2">
                                            Upload Received Items Proof <span class="text-red-500">*</span>
                                        </label>
                                        <input type="file"
                                               id="received_proof"
                                               name="received_proof"
                                               accept="image/*,.pdf"
                                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-600 file:text-white hover:file:bg-green-700 file:cursor-pointer"
                                               required>
                                        <p class="text-xs text-gray-500 mt-1">Accepted formats: JPG, PNG, PDF (Max 5MB)</p>
                                        @error('received_proof')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="delivery_notes" class="block text-sm font-medium text-gray-700 mb-2">
                                            Notes (Optional)
                                        </label>
                                        <textarea name="delivery_notes"
                                                  id="delivery_notes"
                                                  rows="3"
                                                  class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                                  placeholder="Add any additional notes about receiving the items..."></textarea>
                                        @error('delivery_notes')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <button type="submit" class="w-full bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors font-semibold">
                                        <i class="fas fa-upload mr-2"></i>Upload Received Items Proof
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                    <p class="text-sm text-gray-600">
                                        @if($order->status === 'payment_pending')
                                            Received items information can be uploaded after payment is confirmed.
                                        @elseif($order->status === 'completed')
                                            Order has been completed.
                                        @else
                                            Received items information upload is not available for this order status.
                                        @endif
                                    </p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Back Button -->
                <div class="print:hidden flex justify-center">
                    <a href="{{ route('supplier.orders.index') }}" class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition-colors font-semibold">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Orders
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
@media print {
    @page {
        margin: 0.5cm;
    }
    .sidebar, .print\\:hidden, button, nav {
        display: none !important;
    }
    body {
        margin: 0;
        padding: 0;
    }
    .lg\\:ml-64 {
        margin-left: 0 !important;
    }
}
</style>
@endpush

@push('scripts')
@if(session('status'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    const alert = @json(session('status'));
    const type = alert.type || 'success';
    const message = alert.message || '';

    if (message) {
        // Create alert element
        const alertDiv = document.createElement('div');
        alertDiv.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg ${
            type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
        }`;
        alertDiv.innerHTML = `
            <div class="flex items-center">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
                <span>${message}</span>
            </div>
        `;

        document.body.appendChild(alertDiv);

        // Remove after 5 seconds
        setTimeout(() => {
            alertDiv.style.transition = 'opacity 0.5s';
            alertDiv.style.opacity = '0';
            setTimeout(() => alertDiv.remove(), 500);
        }, 5000);
    }
});
</script>
@endif
@endpush

@endsection
