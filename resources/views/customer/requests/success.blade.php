@extends('layouts.app')

@section('title', isset($request) ? 'Invoice ' . $request->order_number : 'Order Invoices')

@section('content')
<div class="flex bg-gray-100 min-h-screen w-full overflow-x-hidden">
    @include('customer.partials.sidebar')

    <!-- Mobile Menu Button -->
    <button id="openSidebar" class="lg:hidden fixed top-4 left-4 z-50 p-2 bg-white rounded-lg shadow-lg hover:bg-gray-100 transition-colors print:hidden">
        <i class="fas fa-bars text-gray-700 text-xl"></i>
    </button>

    <!-- Main Content -->
    <div class="w-full lg:ml-64 transition-all duration-300">
        <div class="flex-1 bg-white min-h-screen">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

                @if(isset($request))
                    <!-- Single Invoice View (from show method) -->
                    <div class="bg-white border-2 border-gray-200 rounded-lg shadow-sm mb-8 print:shadow-none">
                        <div class="print:hidden border-b border-gray-200 p-4 bg-gray-50 flex justify-between items-center">
                            <h1 class="text-2xl font-bold text-gray-900">Invoice</h1>
                            <button onclick="window.print()" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition-colors font-semibold">
                                <i class="fas fa-print mr-2"></i>Print Invoice
                            </button>
                        </div>
                        <div class="p-8">
                            <!-- Company & Invoice Info -->
                            <table class="w-full mb-8 pb-8 border-b-2 border-gray-300" style="border-collapse: collapse;">
                                <tr>
                                    <td class="align-top" style="width: 50%;">
                                        <h2 class="text-2xl font-bold text-gray-900 mb-2" style="line-height: 1.2;">FSMS</h2>
                                        <p class="text-gray-600 text-sm" style="line-height: 1.4;">FoodSupply Management System</p>
                                        <p class="text-gray-600 text-sm mt-1" style="line-height: 1.4;">Jakarta, Indonesia</p>
                                    </td>
                                    <td class="align-top text-right" style="width: 50%;">
                                        <h3 class="text-2xl font-bold text-gray-900 mb-2" style="line-height: 1.2;">Invoice</h3>
                                        <p class="text-gray-700 font-semibold" style="line-height: 1.4;">Order #{{ $request->order_number }}</p>
                                        <p class="text-gray-600 text-sm mt-1" style="line-height: 1.4;">Date: {{ $request->created_at->format('d M Y') }}</p>
                                        <p class="text-gray-600 text-sm mt-1" style="line-height: 1.4;">
                                            Status:
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
                                                $config = $statusConfigs[$request->status] ?? $statusConfigs['pending'];
                                            @endphp
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $config['color'] }}">
                                                {{ $config['label'] }}
                                            </span>
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Customer Info -->
                            <div class="mb-8">
                                <h3 class="text-lg font-bold text-gray-900 mb-4">Bill To:</h3>
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                    <p class="font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                                    <p class="text-gray-600 text-sm">{{ auth()->user()->email }}</p>
                                    @if(isset($orderData['delivery']['delivery_address']))
                                        <div class="mt-3 pt-3 border-t border-gray-200">
                                            <p class="text-gray-900 font-medium">{{ $orderData['delivery']['recipient_name'] }}</p>
                                            <p class="text-gray-600 text-sm">{{ $orderData['delivery']['recipient_phone'] }}</p>
                                            <p class="text-gray-600 text-sm">{{ $orderData['delivery']['delivery_address'] }}</p>
                                            <p class="text-gray-600 text-sm">{{ $orderData['delivery']['city'] }} {{ $orderData['delivery']['postal_code'] ?? '' }}</p>
                                            @if(isset($orderData['delivery']['needed_date']) && $orderData['delivery']['needed_date'])
                                                <p class="text-gray-600 text-sm mt-2">
                                                    <span class="font-medium">Needed Date:</span>
                                                    @if($orderData['delivery']['needed_date'] instanceof \Carbon\Carbon)
                                                        {{ $orderData['delivery']['needed_date']->format('d M Y') }}
                                                    @else
                                                        {{ \Carbon\Carbon::parse($orderData['delivery']['needed_date'])->format('d M Y') }}
                                                    @endif
                                                </p>
                                            @endif
                                            @if(isset($orderData['delivery']['delivery_notes']) && !empty($orderData['delivery']['delivery_notes']))
                                                <div class="mt-2 pt-2 border-t border-gray-200">
                                                    <p class="text-gray-900 font-medium text-sm mb-1">Delivery Notes:</p>
                                                    <p class="text-gray-600 text-sm whitespace-pre-wrap">{{ $orderData['delivery']['delivery_notes'] }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Payment To Supplier Info -->
                            @if(isset($orderData['supplier']) && $orderData['supplier'])
                                <div class="mb-8">
                                    <h3 class="text-lg font-bold text-gray-900 mb-4">Payment To:</h3>
                                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                        <p class="font-semibold text-gray-900">{{ $orderData['supplier']->name }}</p>
                                        <p class="text-gray-600 text-sm">{{ $orderData['supplier']->email }}</p>
                                        @if($orderData['supplier']->phone)
                                            <p class="text-gray-600 text-sm mt-1"><i class="fas fa-phone mr-1"></i>{{ $orderData['supplier']->phone }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endif

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
                                        @foreach($orderData['items'] as $item)
                                            <tr>
                                                <td class="border border-gray-300 px-4 py-3">
                                                    <div class="font-semibold text-gray-900">{{ $item['product']->name }}</div>
                                                    @if($item['product']->description)
                                                        <div class="text-sm text-gray-600">{{ Str::limit($item['product']->description, 60) }}</div>
                                                    @endif
                                                </td>
                                                <td class="border border-gray-300 px-4 py-3 text-center text-gray-700">
                                                    {{ number_format($item['quantity'], 2) }} {{ $item['product']->unit }}
                                                </td>
                                                <td class="border border-gray-300 px-4 py-3 text-right text-gray-700">
                                                    @php
                                                        $itemPrice = $item['final_price'] ?? ($item['product']->price ?? $item['product']->getFinalPrice());
                                                    @endphp
                                                    Rp {{ number_format($itemPrice, 0, ',', '.') }}
                                                </td>
                                                <td class="border border-gray-300 px-4 py-3 text-right font-semibold text-gray-900">
                                                    Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3" class="border border-gray-300 px-4 py-3 text-right font-bold text-gray-900">Grand Total:</td>
                                            <td class="border border-gray-300 px-4 py-3 text-right font-bold text-xl text-gray-900">
                                                Rp {{ number_format($orderData['total'], 0, ',', '.') }}
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

                    <!-- Payment Proof Section (Single Invoice) -->
                    @if($request->payment_proof)
                        <div class="bg-white border-2 border-gray-200 rounded-lg shadow-sm mb-8 print:hidden">
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-4">Payment Proof</h3>
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 mb-4">
                                    <div class="flex items-center justify-between mb-3">
                                        <a href="{{ asset('storage/' . $request->payment_proof) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold">
                                            <i class="fas fa-eye mr-2"></i>View Payment Proof
                                        </a>
                                        @if($request->customer_id === auth()->id())
                                            <button onclick="togglePaymentProofEdit({{ $request->id }})" class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors font-semibold">
                                                <i class="fas fa-edit mr-2"></i>Change
                                            </button>
                                        @endif
                                    </div>
                                    @if($request->payment_proof_uploaded_at)
                                        <p class="text-xs text-gray-500 mt-2">Uploaded: {{ $request->payment_proof_uploaded_at->format('d M Y H:i') }}</p>
                                    @endif
                                    @php
                                        // Extract payment notes from notes field
                                        $paymentNotes = null;
                                        if ($request->notes) {
                                            // Match "Payment Notes: " followed by content until "Delivery Notes:" or end of string
                                            if (preg_match('/Payment Notes:\s*(.+?)(?:\n\nDelivery Notes:|$)/s', $request->notes, $matches)) {
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

                                <!-- Edit Payment Proof Form (Hidden by default) -->
                                <div id="editPaymentProofForm{{ $request->id }}" class="hidden">
                                    <form method="POST" action="{{ route('customer.requests.upload-payment-proof') }}" enctype="multipart/form-data" class="space-y-4">
                                        @csrf
                                        <input type="hidden" name="request_ids[]" value="{{ $request->id }}">

                                        <div>
                                            <label for="payment_proof_edit_{{ $request->id }}" class="block text-sm font-medium text-gray-700 mb-2">
                                                Upload New Payment Proof <span class="text-red-500">*</span>
                                            </label>
                                            <input type="file" id="payment_proof_edit_{{ $request->id }}" name="payment_proof" accept="image/*,.pdf"
                                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-600 file:text-white hover:file:bg-green-700" required>
                                        </div>

                                        <div>
                                            <label for="payment_notes_edit_{{ $request->id }}" class="block text-sm font-medium text-gray-700 mb-2">
                                                Payment Notes (Optional)
                                            </label>
                                            <textarea name="payment_notes" id="payment_notes_edit_{{ $request->id }}" rows="2"
                                                      class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                                      placeholder="Add any additional notes about your payment..."></textarea>
                                        </div>

                                        <div class="flex gap-3">
                                            <button type="submit" class="flex-1 bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors font-semibold">
                                                <i class="fas fa-upload mr-2"></i>Update Payment Proof
                                            </button>
                                            <button type="button" onclick="togglePaymentProofEdit({{ $request->id }})" class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors font-semibold">
                                                Cancel
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @elseif($request->status === 'payment_pending')
                        <div class="bg-yellow-50 border-2 border-yellow-300 rounded-lg shadow-sm mb-8 print:hidden">
                            <div class="p-6">
                                <h3 class="text-lg font-bold text-gray-900 mb-3 flex items-center">
                                    <i class="fas fa-exclamation-triangle text-yellow-600 mr-2"></i>
                                    Payment Required
                                </h3>
                                <p class="text-gray-700 mb-4">Please upload payment proof for this invoice:</p>

                                <form method="POST" action="{{ route('customer.requests.upload-payment-proof') }}" enctype="multipart/form-data" class="space-y-4">
                                    @csrf
                                    <input type="hidden" name="request_ids[]" value="{{ $request->id }}">

                                    <div>
                                        <label for="payment_proof" class="block text-sm font-medium text-gray-700 mb-2">
                                            Payment Proof File <span class="text-red-500">*</span>
                                        </label>
                                        <input type="file" id="payment_proof" name="payment_proof" accept="image/*,.pdf"
                                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-600 file:text-white hover:file:bg-green-700" required>
                                    </div>

                                    <div>
                                        <label for="payment_notes" class="block text-sm font-medium text-gray-700 mb-2">
                                            Payment Notes (Optional)
                                        </label>
                                        <textarea name="payment_notes" id="payment_notes" rows="2"
                                                  class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                                  placeholder="Add any additional notes about your payment..."></textarea>
                                    </div>

                                    <button type="submit"
                                            class="w-full bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors font-semibold">
                                        <i class="fas fa-upload mr-2"></i>Upload Payment Proof
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif

                    <!-- Received Items Information Section (Single Invoice) -->
                    <div class="bg-white border-2 border-gray-200 rounded-lg shadow-sm mb-8 print:hidden">
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-4">Received Items Information</h3>
                            @if($request->received_proof)
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 mb-4">
                                    <div class="flex items-center justify-between mb-3">
                                        <a href="{{ asset('storage/' . $request->received_proof) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold">
                                            <i class="fas fa-eye mr-2"></i>View Received Items Proof
                                        </a>
                                        @if($request->customer_id === auth()->id())
                                            <button onclick="toggleReceivedProofEdit({{ $request->id }})" class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors font-semibold">
                                                <i class="fas fa-edit mr-2"></i>Change
                                            </button>
                                        @endif
                                    </div>
                                    @if($request->received_proof_uploaded_at)
                                        <p class="text-xs text-gray-500 mt-2">Uploaded: {{ $request->received_proof_uploaded_at->format('d M Y H:i') }}</p>
                                    @endif
                                    @php
                                        // Extract delivery notes from notes field
                                        $deliveryNotes = null;
                                        if ($request->notes) {
                                            // Match "Delivery Notes: " followed by content until end of string
                                            if (preg_match('/Delivery Notes:\s*(.+?)$/s', $request->notes, $matches)) {
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

                            @if($request->status === 'paid' && $request->customer_id === auth()->id())
                                <div id="uploadReceivedProofForm{{ $request->id }}" class="{{ $request->received_proof ? 'hidden' : '' }}">
                                    <form method="POST" action="{{ route('customer.requests.upload-delivery-proof', $request->id) }}" enctype="multipart/form-data" class="space-y-4">
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

                                <!-- Edit Received Proof Form (Always hidden by default, shown when Change button clicked) -->
                                <div id="editReceivedProofForm{{ $request->id }}" class="hidden">
                                    <form method="POST" action="{{ route('customer.requests.upload-delivery-proof', $request->id) }}" enctype="multipart/form-data" class="space-y-4">
                                        @csrf
                                        <div>
                                            <label for="received_proof_edit_{{ $request->id }}" class="block text-sm font-medium text-gray-700 mb-2">
                                                Upload New Received Items Proof <span class="text-red-500">*</span>
                                            </label>
                                            <input type="file"
                                                   id="received_proof_edit_{{ $request->id }}"
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
                                            <label for="delivery_notes_edit_{{ $request->id }}" class="block text-sm font-medium text-gray-700 mb-2">
                                                Notes (Optional)
                                            </label>
                                            <textarea name="delivery_notes"
                                                      id="delivery_notes_edit_{{ $request->id }}"
                                                      rows="3"
                                                      class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                                      placeholder="Add any additional notes about receiving the items..."></textarea>
                                            @error('delivery_notes')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="flex gap-3">
                                            <button type="submit" class="flex-1 bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors font-semibold">
                                                <i class="fas fa-upload mr-2"></i>Update Received Items Proof
                                            </button>
                                            <button type="button" onclick="toggleReceivedProofEdit({{ $request->id }})" class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors font-semibold">
                                                Cancel
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            @else
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                        <p class="text-sm text-gray-600">
                                            @if($request->status === 'payment_pending')
                                                Received items information can be uploaded after payment is confirmed.
                                            @elseif($request->status === 'completed')
                                                Order has been completed.
                                            @else
                                                Received items information upload is not available for this order status.
                                            @endif
                                        </p>
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <!-- Multiple Invoices View (from success page) -->
                    @if(isset($orderData['suppliers']) && count($orderData['suppliers']) > 0)
                        @foreach($orderData['suppliers'] as $supplierId => $supplierData)
                            <div class="invoice-section bg-white border-2 border-gray-200 rounded-lg shadow-sm mb-8 print:shadow-none print:break-after-page" id="invoice-{{ $supplierId }}">
                                <!-- Supplier Invoice Header -->
                                <div class="print:hidden border-b border-gray-200 p-4 bg-gray-50 flex justify-between items-center">
                                    <h1 class="text-2xl font-bold text-gray-900">Invoice - {{ $supplierData['supplier']->name }}</h1>
                                    <button onclick="printInvoice('invoice-{{ $supplierId }}')" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition-colors font-semibold">
                                        <i class="fas fa-print mr-2"></i>Print Invoice
                                    </button>
                                </div>
                                <div class="p-8">
                                    <!-- Company & Supplier Info -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8 pb-8 border-b-2 border-gray-300">
                                        <div>
                                            <h2 class="text-xl font-bold text-gray-900 mb-1">{{ $supplierData['supplier']->name }}</h2>
                                            <p class="text-gray-600 text-sm">{{ $supplierData['supplier']->email }}</p>
                                        </div>
                                        <div class="text-right">
                                            <h3 class="text-lg font-bold text-gray-900 mb-2">Invoice</h3>
                                            <p class="text-gray-600 text-sm mt-1">Date: {{ now()->format('d M Y') }}</p>
                                        </div>
                                    </div>

                                    <!-- Customer Info -->
                                    <div class="mb-8">
                                        <h3 class="text-lg font-bold text-gray-900 mb-4">Bill To:</h3>
                                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                            <p class="font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                                            <p class="text-gray-600 text-sm">{{ auth()->user()->email }}</p>
                                            @if(isset($orderData['delivery']['delivery_address']))
                                                <div class="mt-3 pt-3 border-t border-gray-200">
                                                    <p class="text-gray-900 font-medium">{{ $orderData['delivery']['recipient_name'] }}</p>
                                                    <p class="text-gray-600 text-sm">{{ $orderData['delivery']['recipient_phone'] }}</p>
                                                    <p class="text-gray-600 text-sm">{{ $orderData['delivery']['delivery_address'] }}</p>
                                                    <p class="text-gray-600 text-sm">{{ $orderData['delivery']['city'] }} {{ $orderData['delivery']['postal_code'] ?? '' }}</p>
                                                    @if(isset($orderData['delivery']['needed_date']) && $orderData['delivery']['needed_date'])
                                                        <p class="text-gray-600 text-sm mt-2">
                                                            <span class="font-medium">Needed Date:</span> {{ \Carbon\Carbon::parse($orderData['delivery']['needed_date'])->format('d M Y') }}
                                                        </p>
                                                    @endif
                                                    @if(isset($orderData['delivery']['delivery_notes']) && $orderData['delivery']['delivery_notes'])
                                                        <div class="mt-2 pt-2 border-t border-gray-200">
                                                            <p class="text-gray-900 font-medium text-sm mb-1">Delivery Notes:</p>
                                                            <p class="text-gray-600 text-sm whitespace-pre-wrap">{{ $orderData['delivery']['delivery_notes'] }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Payment To Supplier Info -->
                                    <div class="mb-8">
                                        <h3 class="text-lg font-bold text-gray-900 mb-4">Payment To:</h3>
                                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                            <p class="font-semibold text-gray-900">{{ $supplierData['supplier']->name }}</p>
                                            <p class="text-gray-600 text-sm">{{ $supplierData['supplier']->email }}</p>
                                            @if($supplierData['supplier']->phone)
                                                <p class="text-gray-600 text-sm mt-1"><i class="fas fa-phone mr-1"></i>{{ $supplierData['supplier']->phone }}</p>
                                            @endif
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
                                                @foreach($supplierData['items'] as $item)
                                                    <tr>
                                                        <td class="border border-gray-300 px-4 py-3">
                                                            <div class="font-semibold text-gray-900">{{ $item['product']->name }}</div>
                                                            @if($item['product']->description)
                                                                <div class="text-sm text-gray-600">{{ Str::limit($item['product']->description, 60) }}</div>
                                                            @endif
                                                        </td>
                                                        <td class="border border-gray-300 px-4 py-3 text-center text-gray-700">
                                                            {{ number_format($item['quantity'], 2) }} {{ $item['product']->unit }}
                                                        </td>
                                                        <td class="border border-gray-300 px-4 py-3 text-right text-gray-700">
                                                            @php
                                                                $itemPrice = $item['final_price'] ?? ($item['product']->price ?? $item['product']->getFinalPrice());
                                                            @endphp
                                                            Rp {{ number_format($itemPrice, 0, ',', '.') }}
                                                        </td>
                                                        <td class="border border-gray-300 px-4 py-3 text-right font-semibold text-gray-900">
                                                            Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="3" class="border border-gray-300 px-4 py-3 text-right font-bold text-gray-900">Subtotal:</td>
                                                    <td class="border border-gray-300 px-4 py-3 text-right font-bold text-gray-900">
                                                        Rp {{ number_format($supplierData['total'], 0, ',', '.') }}
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

                            <!-- Payment Upload for this supplier -->
                            @php
                                $supplierPaymentUploaded = true;
                                foreach($supplierData['request_ids'] as $reqId) {
                                    $req = $requests->firstWhere('id', $reqId);
                                    if (!$req || !$req->payment_proof) {
                                        $supplierPaymentUploaded = false;
                                        break;
                                    }
                                }
                            @endphp

                            @if(!$supplierPaymentUploaded)
                            <div class="invoice-upload-section bg-yellow-50 border-2 border-yellow-300 rounded-lg p-6 mb-8 print:hidden">
                                <h3 class="text-lg font-bold text-gray-900 mb-3 flex items-center">
                                    <i class="fas fa-exclamation-triangle text-yellow-600 mr-2"></i>
                                    Payment Required for {{ $supplierData['supplier']->name }}
                                </h3>
                                <p class="text-gray-700 mb-4">Please upload payment proof for this invoice:</p>

                                <form method="POST" action="{{ route('customer.requests.upload-payment-proof') }}" enctype="multipart/form-data" class="space-y-4">
                                    @csrf

                                    @foreach($supplierData['request_ids'] as $requestId)
                                        <input type="hidden" name="request_ids[]" value="{{ $requestId }}">
                                    @endforeach

                                    <div>
                                        <label for="payment_proof_{{ $supplierId }}" class="block text-sm font-medium text-gray-700 mb-2">
                                            Payment Proof File <span class="text-red-500">*</span>
                                        </label>
                                        <input type="file" id="payment_proof_{{ $supplierId }}" name="payment_proof" accept="image/*,.pdf"
                                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-600 file:text-white hover:file:bg-green-700" required>
                                    </div>

                                    <div>
                                        <label for="payment_notes_{{ $supplierId }}" class="block text-sm font-medium text-gray-700 mb-2">
                                            Payment Notes (Optional)
                                        </label>
                                        <textarea name="payment_notes" id="payment_notes_{{ $supplierId }}" rows="2"
                                                  class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                                  placeholder="Add any additional notes about your payment..."></textarea>
                                    </div>

                                    <button type="submit"
                                            class="w-full bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors font-semibold">
                                        <i class="fas fa-upload mr-2"></i>Upload Payment Proof for {{ $supplierData['supplier']->name }}
                                    </button>
                                </form>
                            </div>
                            @else
                            <div class="invoice-upload-section bg-green-50 border-2 border-green-300 rounded-lg p-6 mb-8 print:hidden">
                                <div class="flex items-center justify-between mb-3">
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900 mb-2 flex items-center">
                                            <i class="fas fa-check-circle text-green-600 mr-2"></i>
                                            Payment Proof Uploaded for {{ $supplierData['supplier']->name }}
                                        </h3>
                                        <p class="text-gray-700">Your payment has been received. Order is being processed.</p>
                                    </div>
                                    <button onclick="togglePaymentProofEditMulti({{ $supplierId }})" class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors font-semibold">
                                        <i class="fas fa-edit mr-2"></i>Change
                                    </button>
                                </div>

                                <!-- View Payment Proofs -->
                                <div class="mt-4 space-y-2">
                                    @foreach($supplierRequests->whereNotNull('payment_proof') as $req)
                                        <div class="flex items-center justify-between bg-white rounded-lg p-3 border border-gray-200">
                                            <span class="text-sm text-gray-600">Order #{{ $req->order_number }}</span>
                                            <a href="{{ asset('storage/' . $req->payment_proof) }}" target="_blank" class="inline-flex items-center px-3 py-1.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-xs font-semibold">
                                                <i class="fas fa-eye mr-1"></i>View
                                            </a>
                                        </div>
                                    @endforeach
                                </div>

                                @php
                                    // Extract payment notes from first request that has notes
                                    $paymentNotesMulti = null;
                                    foreach($supplierRequests->whereNotNull('payment_proof') as $req) {
                                        if ($req->notes) {
                                            if (preg_match('/Payment Notes:\s*(.+?)(?:\n\nDelivery Notes:|$)/s', $req->notes, $matches)) {
                                                $paymentNotesMulti = trim($matches[1]);
                                                break; // Take first found payment notes
                                            }
                                        }
                                    }
                                @endphp
                                @if($paymentNotesMulti)
                                    <div class="mt-4 pt-4 border-t border-gray-300">
                                        <p class="text-sm font-medium text-gray-700 mb-1">Payment Notes:</p>
                                        <p class="text-sm text-gray-600 whitespace-pre-wrap">{{ $paymentNotesMulti }}</p>
                                    </div>
                                @endif

                                <!-- Edit Payment Proof Form (Hidden by default) -->
                                <div id="editPaymentProofFormMulti{{ $supplierId }}" class="hidden mt-4">
                                    <form method="POST" action="{{ route('customer.requests.upload-payment-proof') }}" enctype="multipart/form-data" class="space-y-4 bg-white rounded-lg p-4 border border-gray-200">
                                        @csrf
                                        @foreach($supplierData['request_ids'] as $requestId)
                                            <input type="hidden" name="request_ids[]" value="{{ $requestId }}">
                                        @endforeach

                                        <div>
                                            <label for="payment_proof_edit_multi_{{ $supplierId }}" class="block text-sm font-medium text-gray-700 mb-2">
                                                Upload New Payment Proof <span class="text-red-500">*</span>
                                            </label>
                                            <input type="file" id="payment_proof_edit_multi_{{ $supplierId }}" name="payment_proof" accept="image/*,.pdf"
                                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-600 file:text-white hover:file:bg-green-700" required>
                                        </div>

                                        <div>
                                            <label for="payment_notes_edit_multi_{{ $supplierId }}" class="block text-sm font-medium text-gray-700 mb-2">
                                                Payment Notes (Optional)
                                            </label>
                                            <textarea name="payment_notes" id="payment_notes_edit_multi_{{ $supplierId }}" rows="2"
                                                      class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                                      placeholder="Add any additional notes about your payment..."></textarea>
                                        </div>

                                        <div class="flex gap-3">
                                            <button type="submit" class="flex-1 bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors font-semibold">
                                                <i class="fas fa-upload mr-2"></i>Update Payment Proof
                                            </button>
                                            <button type="button" onclick="togglePaymentProofEditMulti({{ $supplierId }})" class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors font-semibold">
                                                Cancel
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            @endif

                            <!-- Received Items Information Section for this supplier -->
                            @php
                                $supplierRequests = $requests->whereIn('id', $supplierData['request_ids']);
                                $hasDeliveryProof = $supplierRequests->whereNotNull('received_proof')->count() > 0;
                                $canUploadDeliveryProof = $supplierRequests->where('status', 'paid')->count() > 0;
                            @endphp

                            <div class="invoice-upload-section bg-white border-2 border-gray-200 rounded-lg p-6 mb-8 print:hidden">
                                <h3 class="text-xl font-bold text-gray-900 mb-4">Received Items Information</h3>

                                @if($hasDeliveryProof)
                                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 mb-4">
                                        <div class="flex items-center justify-between mb-3">
                                            <p class="text-sm text-gray-700 font-semibold">Received items proof uploaded for orders:</p>
                                            <button onclick="toggleReceivedProofEditMulti({{ $supplierId }})" class="inline-flex items-center px-3 py-1.5 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors text-xs font-semibold">
                                                <i class="fas fa-edit mr-1"></i>Change
                                            </button>
                                        </div>
                                        <div class="space-y-2">
                                            @foreach($supplierRequests->whereNotNull('received_proof') as $req)
                                                <div class="flex items-center justify-between bg-white rounded-lg p-2 border border-gray-200">
                                                    <span class="text-sm text-gray-600">Order #{{ $req->order_number }}</span>
                                                    <a href="{{ asset('storage/' . $req->received_proof) }}" target="_blank" class="inline-flex items-center px-3 py-1.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-xs font-semibold">
                                                        <i class="fas fa-eye mr-1"></i>View
                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>
                                        @php
                                            // Extract delivery notes from first request that has notes
                                            $deliveryNotesMulti = null;
                                            foreach($supplierRequests->whereNotNull('received_proof') as $req) {
                                                if ($req->notes) {
                                                    if (preg_match('/Delivery Notes:\s*(.+?)$/s', $req->notes, $matches)) {
                                                        $deliveryNotesMulti = trim($matches[1]);
                                                        break; // Take first found delivery notes
                                                    }
                                                }
                                            }
                                        @endphp
                                        @if($deliveryNotesMulti)
                                            <div class="mt-4 pt-4 border-t border-gray-300">
                                                <p class="text-sm font-medium text-gray-700 mb-1">Received Items Notes:</p>
                                                <p class="text-sm text-gray-600 whitespace-pre-wrap">{{ $deliveryNotesMulti }}</p>
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                @if($canUploadDeliveryProof)
                                    @php
                                        // Get first paid request that belongs to current customer
                                        $firstPaidRequest = $supplierRequests->where('status', 'paid')
                                            ->where('customer_id', auth()->id())
                                            ->first();
                                    @endphp
                                    @if($firstPaidRequest)
                                    <!-- Upload Form (Hidden if proof exists) -->
                                    <div id="uploadReceivedProofFormMulti{{ $supplierId }}" class="{{ $hasDeliveryProof ? 'hidden' : '' }}">
                                        <form method="POST" action="{{ route('customer.requests.upload-delivery-proof', $firstPaidRequest->id) }}" enctype="multipart/form-data" class="space-y-4">
                                            @csrf
                                            <div>
                                                <label for="received_proof_{{ $supplierId }}" class="block text-sm font-medium text-gray-700 mb-2">
                                                    Upload Received Items Proof <span class="text-red-500">*</span>
                                                </label>
                                                <input type="file"
                                                       id="received_proof_{{ $supplierId }}"
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
                                                <label for="delivery_notes_{{ $supplierId }}" class="block text-sm font-medium text-gray-700 mb-2">
                                                    Notes (Optional)
                                                </label>
                                                <textarea name="delivery_notes"
                                                          id="delivery_notes_{{ $supplierId }}"
                                                          rows="3"
                                                          class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                                          placeholder="Add any additional notes about receiving the items..."></textarea>
                                                @error('delivery_notes')
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <button type="submit" class="w-full bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors font-semibold">
                                                <i class="fas fa-upload mr-2"></i>Upload Received Items Proof for {{ $supplierData['supplier']->name }}
                                            </button>
                                        </form>
                                    </div>

                                    <!-- Edit Form (Always hidden by default, shown when Change button clicked) -->
                                    <div id="editReceivedProofFormMulti{{ $supplierId }}" class="hidden">
                                        <form method="POST" action="{{ route('customer.requests.upload-delivery-proof', $firstPaidRequest->id) }}" enctype="multipart/form-data" class="space-y-4">
                                            @csrf
                                            <div>
                                                <label for="received_proof_edit_multi_{{ $supplierId }}" class="block text-sm font-medium text-gray-700 mb-2">
                                                    Upload New Received Items Proof <span class="text-red-500">*</span>
                                                </label>
                                                <input type="file"
                                                       id="received_proof_edit_multi_{{ $supplierId }}"
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
                                                <label for="delivery_notes_edit_multi_{{ $supplierId }}" class="block text-sm font-medium text-gray-700 mb-2">
                                                    Notes (Optional)
                                                </label>
                                                <textarea name="delivery_notes"
                                                          id="delivery_notes_edit_multi_{{ $supplierId }}"
                                                          rows="3"
                                                          class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                                          placeholder="Add any additional notes about receiving the items..."></textarea>
                                                @error('delivery_notes')
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="flex gap-3">
                                                <button type="submit" class="flex-1 bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors font-semibold">
                                                    <i class="fas fa-upload mr-2"></i>Update Received Items Proof
                                                </button>
                                                <button type="button" onclick="toggleReceivedProofEditMulti({{ $supplierId }})" class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors font-semibold">
                                                    Cancel
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                    @endif
                                @else
                                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                        <p class="text-sm text-gray-600">
                                            @php
                                                $firstRequest = $supplierRequests->first();
                                                if ($firstRequest) {
                                                    if ($firstRequest->status === 'payment_pending') {
                                                        echo 'Received items information can be uploaded after payment is confirmed.';
                                                    } elseif ($firstRequest->status === 'completed') {
                                                        echo 'Order has been completed.';
                                                    } else {
                                                        echo 'Received items information upload is not available for this order status.';
                                                    }
                                                }
                                            @endphp
                                        </p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @endif
                @endif

                <!-- Action Buttons -->
                <div id="actionButtons" class="print:hidden flex gap-4 justify-center">
                    <a href="{{ route('customer.requests.index') }}"
                       class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition-colors font-semibold">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Orders
                    </a>
                    <a href="{{ route('customer.ingredients') }}"
                       class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition-colors font-semibold">
                        <i class="fas fa-shopping-bag mr-2"></i>Continue Shopping
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
    .invoice-section {
        page-break-after: auto !important;
    }
    /* Ensure header alignment for print - table layout */
    table.w-full {
        width: 100% !important;
        border-collapse: collapse !important;
    }
    table.w-full td {
        vertical-align: top !important;
        padding: 0 !important;
    }
    table.w-full td:first-child {
        text-align: left !important;
    }
    table.w-full td:last-child {
        text-align: right !important;
    }
    table.w-full h2,
    table.w-full h3 {
        line-height: 1.2 !important;
        margin-bottom: 0.5rem !important;
        font-size: 1.5rem !important;
    }
    table.w-full p {
        line-height: 1.4 !important;
        margin-top: 0.25rem !important;
    }
}

/* Hide all invoice sections when printing specific invoice */
body.printing-invoice .invoice-section:not(.invoice-to-print) {
    display: none !important;
}

body.printing-invoice .invoice-upload-section {
    display: none !important;
}

body.printing-invoice #actionButtons {
    display: none !important;
}
</style>
@endpush

@push('scripts')
<script>
function printInvoice(invoiceId) {
    // Add printing class to body
    document.body.classList.add('printing-invoice');

    // Add class to the invoice to print
    const invoiceElement = document.getElementById(invoiceId);
    if (invoiceElement) {
        invoiceElement.classList.add('invoice-to-print');
    }

    // Trigger print
    window.print();

    // Clean up after print dialog closes
    setTimeout(() => {
        document.body.classList.remove('printing-invoice');
        if (invoiceElement) {
            invoiceElement.classList.remove('invoice-to-print');
        }
    }, 100);
}

function togglePaymentProofEdit(requestId) {
    const form = document.getElementById('editPaymentProofForm' + requestId);
    if (form) {
        form.classList.toggle('hidden');
    }
}

function toggleReceivedProofEdit(requestId) {
    const uploadForm = document.getElementById('uploadReceivedProofForm' + requestId);
    const editForm = document.getElementById('editReceivedProofForm' + requestId);

    if (editForm) {
        editForm.classList.toggle('hidden');
    }

    // Hide upload form when showing edit form
    if (uploadForm) {
        if (!editForm.classList.contains('hidden')) {
            uploadForm.classList.add('hidden');
        } else {
            uploadForm.classList.remove('hidden');
        }
    }
}

function togglePaymentProofEditMulti(supplierId) {
    const form = document.getElementById('editPaymentProofFormMulti' + supplierId);
    if (form) {
        form.classList.toggle('hidden');
    }
}

function toggleReceivedProofEditMulti(supplierId) {
    const uploadForm = document.getElementById('uploadReceivedProofFormMulti' + supplierId);
    const editForm = document.getElementById('editReceivedProofFormMulti' + supplierId);

    if (editForm) {
        editForm.classList.toggle('hidden');
    }

    // Hide upload form when showing edit form
    if (uploadForm) {
        if (!editForm.classList.contains('hidden')) {
            uploadForm.classList.add('hidden');
        } else {
            uploadForm.classList.remove('hidden');
        }
    }
}
</script>
@endpush

@if(session('status'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    const alert = @json(session('status'));
    const type = alert.type || 'success';
    alert(alert.message);
});
</script>
@endif
@endsection
