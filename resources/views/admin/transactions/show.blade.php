@extends('layouts.app')

@section('title', 'Transaction Details - Admin')

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
                        <a href="{{ route('admin.transactions.index') }}" class="text-gray-500 hover:text-gray-700 mr-4">
                            <i class="fas fa-arrow-left text-xl"></i>
                        </a>
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">Transaction Details</h1>
                            <p class="mt-2 text-gray-600">View transaction information</p>
                        </div>
                    </div>
                </div>

                <div class="px-4 sm:px-6 lg:px-8">
                    <!-- Invoice Style Layout -->
                    <div class="bg-white border-2 border-gray-200 rounded-lg shadow-sm mb-8 print:shadow-none">
                        <div class="print:hidden border-b border-gray-200 p-4 bg-gray-50 flex justify-between items-center">
                            <h1 class="text-2xl font-bold text-gray-900">Transaction Invoice</h1>
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
                                        <h3 class="text-2xl font-bold text-gray-900 mb-2" style="line-height: 1.2;">Transaction</h3>
                                        <p class="text-gray-700 font-semibold">Order #{{ $transaction->order_number }}</p>
                                        <p class="text-gray-600 text-sm mt-1">Date: {{ $transaction->created_at->format('d M Y') }}</p>
                                        @php
                                            $statusConfigs = [
                                                'pending' => ['color' => 'bg-gray-100 text-gray-800', 'label' => 'Pending'],
                                                'payment_pending' => ['color' => 'bg-yellow-100 text-yellow-800', 'label' => 'Payment Pending'],
                                                'paid' => ['color' => 'bg-green-100 text-green-800', 'label' => 'Paid'],
                                                'delivered' => ['color' => 'bg-indigo-100 text-indigo-800', 'label' => 'Delivered'],
                                                'rejected' => ['color' => 'bg-red-100 text-red-800', 'label' => 'Rejected'],
                                            ];
                                            $config = $statusConfigs[$transaction->status] ?? $statusConfigs['pending'];
                                        @endphp
                                        <p class="text-gray-600 text-sm mt-2">
                                            Status:
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $config['color'] }}">
                                                {{ $config['label'] }}
                                            </span>
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Customer Info (Bill To) -->
                            <div class="mb-8">
                                <h3 class="text-lg font-bold text-gray-900 mb-4">Bill To:</h3>
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                    <p class="font-semibold text-gray-900">{{ $transaction->customer->name }}</p>
                                    <p class="text-gray-600 text-sm">{{ $transaction->customer->email }}</p>
                                    @if($transaction->customer->phone)
                                        <p class="text-gray-600 text-sm mt-1"><i class="fas fa-phone mr-1"></i>{{ $transaction->customer->phone }}</p>
                                    @endif
                                    @if($transaction->recipient_name)
                                        <div class="mt-3 pt-3 border-t border-gray-200">
                                            <p class="text-gray-900 font-medium">{{ $transaction->recipient_name }}</p>
                                            <p class="text-gray-600 text-sm">{{ $transaction->recipient_phone }}</p>
                                            <p class="text-gray-600 text-sm">{{ $transaction->delivery_address }}</p>
                                            <p class="text-gray-600 text-sm">{{ $transaction->city }} {{ $transaction->postal_code ?? '' }}</p>
                                            @if($transaction->needed_date)
                                                <p class="text-gray-600 text-sm mt-2">
                                                    <span class="font-medium">Needed Date:</span> 
                                                    @if($transaction->needed_date instanceof \Carbon\Carbon)
                                                        {{ $transaction->needed_date->format('d M Y') }}
                                                    @else
                                                        {{ \Carbon\Carbon::parse($transaction->needed_date)->format('d M Y') }}
                                                    @endif
                                                </p>
                                            @endif
                                            @if($transaction->delivery_notes && !empty($transaction->delivery_notes))
                                                <div class="mt-2 pt-2 border-t border-gray-200">
                                                    <p class="text-gray-900 font-medium text-sm mb-1">Delivery Notes:</p>
                                                    <p class="text-gray-600 text-sm whitespace-pre-wrap">{{ $transaction->delivery_notes }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Supplier Info -->
                            @if(($transaction->food_item_id && $transaction->foodItem && $transaction->foodItem->supplier) || ($transaction->assigned_supplier_id && $transaction->assignedSupplier))
                                <div class="mb-8">
                                    <h3 class="text-lg font-bold text-gray-900 mb-4">Supplier:</h3>
                                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                        @if($transaction->food_item_id && $transaction->foodItem && $transaction->foodItem->supplier)
                                            <p class="font-semibold text-gray-900">{{ $transaction->foodItem->supplier->name }}</p>
                                            <p class="text-gray-600 text-sm">{{ $transaction->foodItem->supplier->email }}</p>
                                            @if($transaction->foodItem->supplier->phone)
                                                <p class="text-gray-600 text-sm mt-1"><i class="fas fa-phone mr-1"></i>{{ $transaction->foodItem->supplier->phone }}</p>
                                            @endif
                                        @elseif($transaction->assigned_supplier_id && $transaction->assignedSupplier)
                                            <p class="font-semibold text-gray-900">{{ $transaction->assignedSupplier->name }}</p>
                                            <p class="text-gray-600 text-sm">{{ $transaction->assignedSupplier->email }}</p>
                                            @if($transaction->assignedSupplier->phone)
                                                <p class="text-gray-600 text-sm mt-1"><i class="fas fa-phone mr-1"></i>{{ $transaction->assignedSupplier->phone }}</p>
                                            @endif
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
                                        <tr>
                                            <td class="border border-gray-300 px-4 py-3">
                                                <div class="font-semibold text-gray-900">
                                                    @if($transaction->food_item_id && $transaction->foodItem)
                                                        {{ $transaction->foodItem->name }}
                                                    @else
                                                        {{ $transaction->title ?? 'N/A' }}
                                                    @endif
                                                </div>
                                                @if($transaction->foodCategory)
                                                    <div class="text-sm text-gray-600">{{ $transaction->foodCategory->name }}</div>
                                                @endif
                                                @if($transaction->description)
                                                    <div class="text-xs text-gray-500 mt-1">{{ $transaction->description }}</div>
                                                @endif
                                            </td>
                                            <td class="border border-gray-300 px-4 py-3 text-center text-gray-900">
                                                {{ number_format($transaction->quantity, 2) }} {{ $transaction->unit }}
                                            </td>
                                            <td class="border border-gray-300 px-4 py-3 text-right text-gray-900">
                                                @if($transaction->price)
                                                    Rp {{ number_format($transaction->price, 0, ',', '.') }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td class="border border-gray-300 px-4 py-3 text-right font-semibold text-gray-900">
                                                @if($transaction->price)
                                                    Rp {{ number_format($transaction->price * $transaction->quantity, 0, ',', '.') }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3" class="border border-gray-300 px-4 py-3 text-right font-bold text-gray-900">Total:</td>
                                            <td class="border border-gray-300 px-4 py-3 text-right font-bold text-green-600 text-lg">
                                                @if($transaction->price)
                                                    Rp {{ number_format($transaction->price * $transaction->quantity, 0, ',', '.') }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <!-- Payment & Delivery Info -->
                            @if($transaction->payment_proof || $transaction->received_proof)
                                <div class="mb-8">
                                    <h3 class="text-lg font-bold text-gray-900 mb-4">Proofs:</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        @if($transaction->payment_proof)
                                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                                <p class="text-sm font-medium text-gray-700 mb-2">Payment Proof</p>
                                                <a href="{{ asset('storage/' . $transaction->payment_proof) }}" target="_blank" class="inline-block">
                                                    <img src="{{ asset('storage/' . $transaction->payment_proof) }}" alt="Payment Proof" class="max-w-full h-auto rounded-lg border border-gray-300">
                                                </a>
                                                @if($transaction->payment_proof_uploaded_at)
                                                    <p class="text-xs text-gray-500 mt-2">Uploaded: {{ $transaction->payment_proof_uploaded_at->format('d M Y H:i') }}</p>
                                                @endif
                                            </div>
                                        @endif

                                        @if($transaction->received_proof)
                                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                                <p class="text-sm font-medium text-gray-700 mb-2">Received Proof</p>
                                                <a href="{{ asset('storage/' . $transaction->received_proof) }}" target="_blank" class="inline-block">
                                                    <img src="{{ asset('storage/' . $transaction->received_proof) }}" alt="Received Proof" class="max-w-full h-auto rounded-lg border border-gray-300">
                                                </a>
                                                @if($transaction->received_proof_uploaded_at)
                                                    <p class="text-xs text-gray-500 mt-2">Uploaded: {{ $transaction->received_proof_uploaded_at->format('d M Y H:i') }}</p>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <!-- Notes -->
                            @if($transaction->notes || $transaction->admin_notes)
                                <div class="mb-8">
                                    <h3 class="text-lg font-bold text-gray-900 mb-4">Notes:</h3>
                                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                        @if($transaction->notes)
                                            <div class="mb-3">
                                                <p class="text-sm font-medium text-gray-700 mb-1">Customer Notes:</p>
                                                <p class="text-sm text-gray-600 whitespace-pre-wrap">{{ $transaction->notes }}</p>
                                            </div>
                                        @endif
                                        @if($transaction->admin_notes)
                                            <div>
                                                <p class="text-sm font-medium text-gray-700 mb-1">Admin Notes:</p>
                                                <p class="text-sm text-gray-600 whitespace-pre-wrap">{{ $transaction->admin_notes }}</p>
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
    </div>
</div>

<style>
@media print {
    .print\:hidden {
        display: none !important;
    }
    .print\:shadow-none {
        box-shadow: none !important;
    }
}
</style>
@endsection

