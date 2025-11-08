@extends('layouts.app')

@section('title', 'Purchase Report - FSMS')

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
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Purchase Report</h1>
                        <p class="mt-2 text-gray-600">View and analyze your purchase history</p>
                    </div>
                </div>

                <!-- Purchase List with Filter -->
                <div class="bg-white rounded-lg shadow mx-4 sm:mx-6 lg:mx-8">
                    <!-- Filter Section inside table container -->
                    <div class="px-4 sm:px-6 py-3 border-b border-gray-200 print:hidden">
                        <form method="GET" action="{{ route('customer.purchase-report') }}" class="flex flex-wrap items-end gap-3">
                            <!-- Status Filter -->
                            <div class="flex-1 min-w-[120px]">
                                <label for="status" class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                                <select name="status" id="status" class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-1 focus:ring-green-500 focus:border-green-500">
                                    <option value="">All</option>
                                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                    <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                </select>
                            </div>

                            <!-- Date From -->
                            <div class="flex-1 min-w-[130px]">
                                <label for="date_from" class="block text-xs font-medium text-gray-600 mb-1">From</label>
                                <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-1 focus:ring-green-500 focus:border-green-500">
                            </div>

                            <!-- Date To -->
                            <div class="flex-1 min-w-[130px]">
                                <label for="date_to" class="block text-xs font-medium text-gray-600 mb-1">To</label>
                                <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-1 focus:ring-green-500 focus:border-green-500">
                            </div>

                            <!-- Month/Year Filter -->
                            <div class="flex-1 min-w-[180px]">
                                <label for="month" class="block text-xs font-medium text-gray-600 mb-1">Month/Year</label>
                                <div class="grid grid-cols-2 gap-2">
                                    <select name="month" id="month" class="px-2 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-1 focus:ring-green-500 focus:border-green-500">
                                        <option value="">Month</option>
                                        @for($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}" {{ request('month') == $i ? 'selected' : '' }}>{{ date('M', mktime(0, 0, 0, $i, 1)) }}</option>
                                        @endfor
                                    </select>
                                    <select name="year" id="year" class="px-2 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-1 focus:ring-green-500 focus:border-green-500">
                                        <option value="">Year</option>
                                        @for($i = date('Y'); $i >= date('Y') - 5; $i--)
                                            <option value="{{ $i }}" {{ request('year') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-end gap-2">
                                <button type="submit" class="bg-green-600 text-white px-3 py-1.5 rounded-md hover:bg-green-700 transition-colors text-sm font-medium">
                                    <i class="fas fa-filter mr-1"></i>Filter
                                </button>
                                <a href="{{ route('customer.purchase-report') }}" class="px-3 py-1.5 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors text-sm" title="Reset">
                                    <i class="fas fa-redo"></i>
                                </a>
                                <button type="button" onclick="window.print()" class="px-3 py-1.5 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors text-sm" title="Print">
                                    <i class="fas fa-print"></i>
                                </button>
                            </div>
                        </form>
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

                    <!-- Desktop Table View -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order #</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider print:hidden">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($requests as $request)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $request->order_number ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $request->created_at->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900">
                                            {{ $request->title ?? ($request->foodItem->name ?? 'N/A') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $request->foodCategory->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            {{ number_format($request->quantity, 2) }} {{ $request->unit }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">
                                            @if($request->foodItem)
                                                Rp {{ number_format($request->foodItem->price, 0, ',', '.') }}
                                            @elseif($request->price)
                                                Rp {{ number_format($request->price, 0, ',', '.') }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 text-right">
                                            @if($request->foodItem)
                                                Rp {{ number_format($request->foodItem->price * $request->quantity, 0, ',', '.') }}
                                            @elseif($request->price)
                                                Rp {{ number_format($request->price * $request->quantity, 0, ',', '.') }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$request->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                {{ $statusLabels[$request->status] ?? $request->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium print:hidden">
                                            <a href="{{ route('customer.requests.show', $request->id) }}" class="text-green-600 hover:text-green-900">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-sm font-bold text-gray-900 text-right">Grand Total:</td>
                                    <td class="px-6 py-4 text-sm font-bold text-gray-900 text-right">Rp {{ number_format($stats['total_amount'], 0, ',', '.') }}</td>
                                    <td colspan="2"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- Mobile Card View -->
                    <div class="block md:hidden divide-y divide-gray-200">
                        @foreach($requests as $request)
                            <div class="p-4 hover:bg-gray-50 transition-colors">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex-1">
                                        <div class="text-sm font-semibold text-gray-900">{{ $request->order_number ?? 'N/A' }}</div>
                                        <div class="text-xs text-gray-500 mt-1">{{ $request->created_at->format('d M Y') }}</div>
                                    </div>
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$request->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $statusLabels[$request->status] ?? $request->status }}
                                    </span>
                                </div>

                                <div class="mb-2">
                                    <div class="text-sm font-medium text-gray-900">{{ $request->title ?? ($request->foodItem->name ?? 'N/A') }}</div>
                                    <div class="text-xs text-gray-500">{{ $request->foodCategory->name }}</div>
                                </div>

                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-600">{{ number_format($request->quantity, 2) }} {{ $request->unit }}</span>
                                    <span class="font-semibold text-gray-900">
                                        @if($request->foodItem)
                                            Rp {{ number_format($request->foodItem->price * $request->quantity, 0, ',', '.') }}
                                        @elseif($request->price)
                                            Rp {{ number_format($request->price * $request->quantity, 0, ',', '.') }}
                                        @else
                                            N/A
                                        @endif
                                    </span>
                                </div>

                                <div class="mt-3 pt-3 border-t border-gray-200">
                                    <a href="{{ route('customer.requests.show', $request->id) }}" class="text-sm text-green-600 hover:text-green-900 font-medium">
                                        <i class="fas fa-eye mr-1"></i>View Details
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @else
                        <div class="p-12 text-center">
                            <i class="fas fa-inbox text-gray-400 text-5xl mb-4"></i>
                            <p class="text-gray-500 text-lg">No purchases found</p>
                            <p class="text-gray-400 text-sm mt-2">Try adjusting your filters</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
@media print {
    .print\\:hidden {
        display: none !important;
    }
    @page {
        margin: 1cm;
    }
    body {
        background: white;
    }
    .lg\\:ml-64 {
        margin-left: 0 !important;
    }
}
</style>
@endpush

@endsection

