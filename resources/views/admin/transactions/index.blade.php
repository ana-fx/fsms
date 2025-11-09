@extends('layouts.app')

@section('title', 'Transaction Report - Admin')

@section('content')
<div class="flex bg-gray-100 min-h-screen w-full overflow-x-hidden">
    @include('admin.partials.sidebar')

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
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Transaction Report</h1>
                        <p class="mt-2 text-gray-600">View all transaction history and reports</p>
                    </div>
                </div>

                <!-- Transactions List -->
                <div class="bg-white rounded-lg shadow mx-4 sm:mx-6 lg:mx-8">
                    <!-- Filter Section -->
                    <div class="px-4 sm:px-6 py-3 border-b border-gray-200">
                        <form method="GET" action="{{ route('admin.transactions.index') }}" class="flex flex-wrap items-end gap-3">
                            <!-- Search -->
                            <div class="flex-1 min-w-[200px]">
                                <label for="search" class="block text-xs font-medium text-gray-600 mb-1">Order Number</label>
                                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search order number..." class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-1 focus:ring-green-500 focus:border-green-500">
                            </div>

                            <!-- Status Filter -->
                            <div class="flex-1 min-w-[120px]">
                                <label for="status" class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                                <select name="status" id="status" class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-1 focus:ring-green-500 focus:border-green-500">
                                    <option value="">All</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="payment_pending" {{ request('status') == 'payment_pending' ? 'selected' : '' }}>Payment Pending</option>
                                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                    <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </div>

                            <!-- Customer Filter -->
                            <div class="flex-1 min-w-[150px]">
                                <label for="customer" class="block text-xs font-medium text-gray-600 mb-1">Customer</label>
                                <select name="customer" id="customer" class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-1 focus:ring-green-500 focus:border-green-500">
                                    <option value="">All Customers</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ request('customer') == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Supplier Filter -->
                            <div class="flex-1 min-w-[150px]">
                                <label for="supplier" class="block text-xs font-medium text-gray-600 mb-1">Supplier</label>
                                <select name="supplier" id="supplier" class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-1 focus:ring-green-500 focus:border-green-500">
                                    <option value="">All Suppliers</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" {{ request('supplier') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Date Filter -->
                            <div class="flex-1 min-w-[140px]">
                                <label for="date" class="block text-xs font-medium text-gray-600 mb-1">Date</label>
                                <input type="date" name="date" id="date" value="{{ request('date') }}" class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-1 focus:ring-green-500 focus:border-green-500">
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-end gap-2">
                                <button type="submit" class="bg-green-600 text-white px-3 py-1.5 rounded-md hover:bg-green-700 transition-colors text-sm font-medium">
                                    <i class="fas fa-filter mr-1"></i>Filter
                                </button>
                                <a href="{{ route('admin.transactions.index') }}" class="px-3 py-1.5 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors text-sm" title="Reset">
                                    <i class="fas fa-redo"></i>
                                </a>
                            </div>
                        </form>

                        <!-- Filtered Stats -->
                        @if(request()->anyFilled(['status', 'customer', 'supplier', 'search', 'date']))
                            <div class="mt-3 pt-3 border-t border-gray-200">
                                <p class="text-sm text-gray-600">
                                    Showing <span class="font-semibold">{{ $filteredStats['count'] }}</span> transactions
                                    @if($filteredStats['revenue'] > 0)
                                        | Revenue: <span class="font-semibold text-green-600">Rp {{ number_format($filteredStats['revenue'], 0, ',', '.') }}</span>
                                    @endif
                                </p>
                            </div>
                        @endif
                    </div>

                    @if($transactions->count() > 0)
                        @php
                            $statusColors = [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'payment_pending' => 'bg-yellow-100 text-yellow-800',
                                'paid' => 'bg-green-100 text-green-800',
                                'delivered' => 'bg-indigo-100 text-indigo-800',
                                'rejected' => 'bg-red-100 text-red-800',
                            ];
                            $statusLabels = [
                                'pending' => 'Pending',
                                'payment_pending' => 'Pending',
                                'paid' => 'Paid',
                                'delivered' => 'Delivered',
                                'rejected' => 'Rejected',
                            ];
                        @endphp

                        <!-- Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order Number</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supplier</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total Price</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($transactions as $transaction)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $transaction->order_number }}</div>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $transaction->customer->name }}</div>
                                                <div class="text-xs text-gray-500">{{ $transaction->customer->email }}</div>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                @if($transaction->food_item_id && $transaction->foodItem && $transaction->foodItem->supplier)
                                                    <div class="text-sm text-gray-900">{{ $transaction->foodItem->supplier->name }}</div>
                                                @elseif($transaction->assigned_supplier_id && $transaction->assignedSupplier)
                                                    <div class="text-sm text-gray-900">{{ $transaction->assignedSupplier->name }}</div>
                                                @else
                                                    <div class="text-sm text-gray-400">-</div>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                @if($transaction->food_item_id)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        Regular
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                        Custom
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="text-sm text-gray-900">
                                                    @if($transaction->food_item_id && $transaction->foodItem)
                                                        {{ $transaction->foodItem->name }}
                                                    @else
                                                        {{ $transaction->title ?? 'N/A' }}
                                                    @endif
                                                </div>
                                                @if($transaction->foodCategory)
                                                    <div class="text-xs text-gray-500">{{ $transaction->foodCategory->name }}</div>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-right">
                                                <div class="text-sm text-gray-900">{{ number_format($transaction->quantity, 2) }} {{ $transaction->unit }}</div>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-right">
                                                @if($transaction->price)
                                                    <div class="text-sm font-semibold text-gray-900">Rp {{ number_format($transaction->price * $transaction->quantity, 0, ',', '.') }}</div>
                                                @else
                                                    <div class="text-sm text-gray-400">-</div>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $statusColors[$transaction->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                    {{ $statusLabels[$transaction->status] ?? ucfirst($transaction->status) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $transaction->created_at->format('d M Y') }}</div>
                                                <div class="text-xs text-gray-500">{{ $transaction->created_at->format('H:i') }}</div>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('admin.transactions.show', $transaction->order_number) }}" class="text-green-600 hover:text-green-900">
                                                    <i class="fas fa-eye mr-1"></i>View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="px-4 sm:px-6 py-4 border-t border-gray-200">
                            {{ $transactions->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="px-4 sm:px-6 py-12 text-center">
                            <i class="fas fa-inbox text-4xl text-gray-400 mb-4"></i>
                            <p class="text-gray-500 text-lg">No transactions found</p>
                            <p class="text-gray-400 text-sm mt-1">Try adjusting your filters</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Simple date input styling */
input[type="date"] {
    position: relative;
    color-scheme: light;
    background-color: white;
    cursor: pointer;
}

input[type="date"]::-webkit-calendar-picker-indicator {
    cursor: pointer;
    opacity: 0.7;
    padding: 4px;
    margin-left: 4px;
    background-size: 16px 16px;
}

input[type="date"]::-webkit-calendar-picker-indicator:hover {
    opacity: 1;
}

input[type="date"]::-webkit-inner-spin-button,
input[type="date"]::-webkit-clear-button {
    display: none;
}

input[type="date"]:focus {
    outline: none;
    border-color: #10b981;
    box-shadow: 0 0 0 1px rgba(16, 185, 129, 0.1);
}

/* Firefox date input styling */
input[type="date"]::-moz-calendar-picker-indicator {
    cursor: pointer;
    opacity: 0.7;
}

input[type="date"]::-moz-calendar-picker-indicator:hover {
    opacity: 1;
}
</style>
@endsection

