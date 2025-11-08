@extends('layouts.app')

@section('title', 'Purchase Report - FSMS')

@section('content')
<div class="flex bg-gray-100 min-h-screen w-full overflow-x-hidden">
    @include('customer.partials.sidebar')

    <!-- Mobile Menu Button -->
    <button id="openSidebar" class="lg:hidden fixed top-4 left-4 z-50 p-2 bg-white rounded-lg shadow-lg hover:bg-gray-100 transition-colors">
        <i class="fas fa-bars text-gray-700 text-xl"></i>
    </button>

    <div class="w-full lg:ml-64 transition-all duration-300">
        <div class="flex-1 bg-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Purchase Report</h1>
                        <p class="mt-1 text-gray-600">Track and analyse all of your ingredient purchases</p>
                    </div>
                    <a href="{{ route('customer.requests.index') }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 transition-colors">
                        <i class="fas fa-list mr-2"></i> View Requests
                    </a>
                </div>

                <!-- Filters -->
                @php
                    $isFilterActive = filled($filters['search']) || ($filters['status'] ?? 'all') !== 'all' || filled($filters['date_from']) || filled($filters['date_to']);
                @endphp

                <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                    <div class="flex items-center justify-between gap-4 mb-4 lg:mb-6">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">Filters</h2>
                            <p class="text-sm text-gray-500">Refine the report by status, date range, or keyword search.</p>
                        </div>
                        <button type="button"
                                id="toggleFilters"
                                class="inline-flex items-center px-3 py-2 border border-gray-200 text-sm font-medium rounded-lg text-gray-600 hover:text-gray-900 hover:border-gray-300 bg-gray-50 lg:hidden transition-colors">
                            <i class="fas fa-sliders-h mr-2"></i>
                            <span>{{ $isFilterActive ? 'Hide Filters' : 'Show Filters' }}</span>
                        </button>
                    </div>

                    <form method="GET"
                          action="{{ route('customer.reports.purchases') }}"
                          id="filtersForm"
                          class="grid grid-cols-1 gap-4 lg:grid-cols-5 {{ $isFilterActive ? '' : 'hidden lg:grid' }}">
                        <div class="flex flex-col">
                            <label class="block text-sm font-medium text-gray-600 mb-1">
                                <i class="fas fa-search mr-1 text-gray-400"></i> Search
                            </label>
                            <input type="text" name="search" value="{{ $filters['search'] }}"
                                   placeholder="Order number, product, category"
                                   class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>
                        <div class="flex flex-col">
                            <label class="block text-sm font-medium text-gray-600 mb-1">
                                <i class="fas fa-filter mr-1 text-gray-400"></i> Status
                            </label>
                            <select name="status" class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                @php
                                    $statuses = [
                                        'all' => 'All Statuses',
                                        'pending' => 'Pending',
                                        'payment_pending' => 'Payment Pending',
                                        'paid' => 'Paid',
                                        'shipping' => 'Shipping',
                                        'delivered' => 'Delivered',
                                        'completed' => 'Completed',
                                        'rejected' => 'Rejected',
                                    ];
                                @endphp
                                @foreach($statuses as $value => $label)
                                    <option value="{{ $value }}" @selected($filters['status'] === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex flex-col">
                            <label class="block text-sm font-medium text-gray-600 mb-1">
                                <i class="fas fa-calendar-alt mr-1 text-gray-400"></i> From
                            </label>
                            <input type="date" name="date_from" value="{{ $filters['date_from'] }}"
                                   class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>
                        <div class="flex flex-col">
                            <label class="block text-sm font-medium text-gray-600 mb-1">
                                <i class="fas fa-calendar-check mr-1 text-gray-400"></i> To
                            </label>
                            <input type="date" name="date_to" value="{{ $filters['date_to'] }}"
                                   class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>
                        <div class="flex items-end space-x-2">
                            <button type="submit" class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 transition-colors">
                                <i class="fas fa-chart-line mr-2"></i> Apply
                            </button>
                            <a href="{{ route('customer.reports.purchases') }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg border border-gray-200 hover:bg-gray-200 transition-colors">
                                <i class="fas fa-undo-alt"></i>
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Summary Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 lg:gap-6">
                    <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Orders</p>
                                <p class="mt-2 text-3xl font-bold text-gray-900">{{ number_format($summary['total_orders']) }}</p>
                            </div>
                            <div class="p-3 bg-green-100 rounded-xl">
                                <i class="fas fa-receipt text-green-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Amount</p>
                                <p class="mt-2 text-3xl font-bold text-gray-900">Rp {{ number_format($summary['total_amount'], 0, ',', '.') }}</p>
                            </div>
                            <div class="p-3 bg-blue-100 rounded-xl">
                                <i class="fas fa-wallet text-blue-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Paid Amount</p>
                                <p class="mt-2 text-3xl font-bold text-gray-900">Rp {{ number_format($summary['paid_amount'], 0, ',', '.') }}</p>
                            </div>
                            <div class="p-3 bg-purple-100 rounded-xl">
                                <i class="fas fa-check-circle text-purple-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Average Order</p>
                                <p class="mt-2 text-3xl font-bold text-gray-900">Rp {{ number_format($summary['average_order_value'], 0, ',', '.') }}</p>
                            </div>
                            <div class="p-3 bg-orange-100 rounded-xl">
                                <i class="fas fa-chart-pie text-orange-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status Breakdown & Monthly Totals -->
                <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                    <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Status Breakdown</h3>
                                <p class="text-sm text-gray-500">Overview of orders per status</p>
                            </div>
                            <div class="p-2 bg-green-100 rounded-xl">
                                <i class="fas fa-traffic-light text-green-600"></i>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($summary['status_breakdown'] as $status => $count)
                                @php
                                    $statusLabels = [
                                        'pending' => ['label' => 'Pending', 'color' => 'bg-gray-200 text-gray-800'],
                                        'payment_pending' => ['label' => 'Payment Pending', 'color' => 'bg-yellow-200 text-yellow-800'],
                                        'paid' => ['label' => 'Paid', 'color' => 'bg-green-200 text-green-800'],
                                        'shipping' => ['label' => 'Shipping', 'color' => 'bg-blue-200 text-blue-800'],
                                        'delivered' => ['label' => 'Delivered', 'color' => 'bg-indigo-200 text-indigo-800'],
                                        'completed' => ['label' => 'Completed', 'color' => 'bg-purple-200 text-purple-800'],
                                        'rejected' => ['label' => 'Rejected', 'color' => 'bg-red-200 text-red-800'],
                                    ];
                                    $percentage = $summary['total_orders'] > 0 ? round(($count / $summary['total_orders']) * 100) : 0;
                                @endphp
                                <div class="space-y-2 border border-gray-100 rounded-xl p-4 hover:border-green-200 transition-colors shadow-sm">
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $statusLabels[$status]['color'] }}">
                                            {{ $statusLabels[$status]['label'] }}
                                        </span>
                                        <span class="text-gray-600 font-medium">{{ $count }} orders <span class="text-gray-400">({{ $percentage }}%)</span></span>
                                    </div>
                                    <div class="w-full bg-gray-100 rounded-full h-2">
                                        <div class="h-2 rounded-full bg-gradient-to-r from-green-400 to-green-600" style="width: {{ $percentage }}%;"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Monthly Totals</h3>
                                <p class="text-sm text-gray-500">Recent purchase trends</p>
                            </div>
                            <div class="p-2 bg-blue-100 rounded-xl">
                                <i class="fas fa-calendar-week text-blue-600"></i>
                            </div>
                        </div>
                        @if($summary['monthly_totals']->isEmpty())
                            <p class="text-sm text-gray-500">No data available for the selected filters.</p>
                        @else
                            <div class="space-y-4 max-h-80 overflow-y-auto pr-2">
                                @foreach($summary['monthly_totals'] as $month)
                                    <div class="border border-gray-100 rounded-xl p-4 hover:border-green-200 transition-colors bg-white shadow-sm">
                                        <div class="flex items-center justify-between gap-4">
                                            <div>
                                                <p class="text-sm font-semibold text-gray-900">{{ $month['label'] }}</p>
                                                <p class="text-xs text-gray-500 mt-1">{{ $month['orders'] }} orders</p>
                                            </div>
                                            <p class="text-base font-bold text-green-600">Rp {{ number_format($month['amount'], 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Data Table -->
                <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Purchase Details</h3>
                            <p class="text-sm text-gray-500">Showing {{ $requests->firstItem() ?? 0 }}-{{ $requests->lastItem() ?? 0 }} of {{ $requests->total() }} orders</p>
                        </div>
                        @if($requests->count() > 0)
                            <a href="#"
                               class="inline-flex items-center px-3 py-2 text-sm font-medium text-green-600 border border-green-200 rounded-lg hover:bg-green-50 transition-colors cursor-not-allowed"
                               title="Export feature coming soon">
                                <i class="fas fa-file-export mr-2"></i> Export (soon)
                            </a>
                        @endif
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-100 hidden md:table">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @forelse($requests as $request)
                                    @php
                                        $unitPrice = $request->price ?? optional($request->foodItem)->price;
                                        $totalAmount = $unitPrice ? (float) $unitPrice * (float) $request->quantity : null;
                                    @endphp
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex flex-col">
                                                <span class="text-sm font-semibold text-gray-900">{{ $request->order_number }}</span>
                                                <a href="{{ route('customer.requests.show', $request) }}" class="text-xs text-green-600 hover:text-green-700">View invoice</a>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap max-w-xs">
                                            <div class="text-sm text-gray-900 font-medium">
                                                {{ $request->foodItem->name ?? $request->title }}
                                            </div>
                                            <div class="text-xs text-gray-500 mt-1 truncate">
                                                {{ $request->foodCategory->name ?? 'No category' }}
                                                @if($request->assignedSupplier)
                                                    • Supplier: {{ $request->assignedSupplier->name }}
                                                @elseif($request->foodItem && $request->foodItem->supplier)
                                                    • Supplier: {{ $request->foodItem->supplier->name }}
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $statusStyles = [
                                                    'pending' => 'bg-gray-100 text-gray-700',
                                                    'payment_pending' => 'bg-yellow-100 text-yellow-800',
                                                    'paid' => 'bg-green-100 text-green-800',
                                                    'shipping' => 'bg-blue-100 text-blue-800',
                                                    'delivered' => 'bg-indigo-100 text-indigo-800',
                                                    'completed' => 'bg-purple-100 text-purple-800',
                                                    'rejected' => 'bg-red-100 text-red-800',
                                                ];
                                            @endphp
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $statusStyles[$request->status] ?? 'bg-gray-100 text-gray-700' }}">
                                                <i class="fas fa-circle text-[8px] mr-2"></i>
                                                {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-900">
                                            {{ number_format($request->quantity, 2, ',', '.') }} {{ $request->unit }}
                                            @if($unitPrice)
                                                <div class="text-xs text-gray-500 mt-1">Rp {{ number_format($unitPrice, 0, ',', '.') }} / {{ $request->unit }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-semibold text-gray-900">
                                            @if($totalAmount !== null)
                                                Rp {{ number_format($totalAmount, 0, ',', '.') }}
                                            @else
                                                <span class="text-gray-400">Awaiting quotation</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-700">
                                            {{ optional($request->created_at)->format('d M Y') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                            <div class="flex flex-col items-center space-y-3">
                                                <div class="w-16 h-16 flex items-center justify-center bg-gray-100 text-gray-400 rounded-full">
                                                    <i class="fas fa-receipt text-2xl"></i>
                                                </div>
                                                <p class="text-lg font-medium text-gray-700">No purchase records found</p>
                                                <p class="text-sm text-gray-500">Try adjusting your filters or start by creating a new request.</p>
                                                <a href="{{ route('customer.ingredients') }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 transition-colors">
                                                    <i class="fas fa-shopping-basket mr-2"></i> Shop Ingredients
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <div class="md:hidden divide-y divide-gray-100">
                            @forelse($requests as $request)
                                @php
                                    $unitPrice = $request->price ?? optional($request->foodItem)->price;
                                    $totalAmount = $unitPrice ? (float) $unitPrice * (float) $request->quantity : null;
                                @endphp
                                <div class="p-5 space-y-4">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900">{{ $request->order_number }}</p>
                                            <p class="text-xs text-gray-500">{{ optional($request->created_at)->format('d M Y') }}</p>
                                        </div>
                                        @php
                                            $statusStyles = [
                                                'pending' => 'bg-gray-100 text-gray-700',
                                                'payment_pending' => 'bg-yellow-100 text-yellow-800',
                                                'paid' => 'bg-green-100 text-green-800',
                                                'shipping' => 'bg-blue-100 text-blue-800',
                                                'delivered' => 'bg-indigo-100 text-indigo-800',
                                                'completed' => 'bg-purple-100 text-purple-800',
                                                'rejected' => 'bg-red-100 text-red-800',
                                            ];
                                        @endphp
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $statusStyles[$request->status] ?? 'bg-gray-100 text-gray-700' }}">
                                            <i class="fas fa-circle text-[8px] mr-2"></i>
                                            {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                                        </span>
                                    </div>

                                    <div class="space-y-2">
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $request->foodItem->name ?? $request->title }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ $request->foodCategory->name ?? 'No category' }}
                                            @if($request->assignedSupplier)
                                                • Supplier: {{ $request->assignedSupplier->name }}
                                            @elseif($request->foodItem && $request->foodItem->supplier)
                                                • Supplier: {{ $request->foodItem->supplier->name }}
                                            @endif
                                        </p>
                                    </div>

                    <div class="grid grid-cols-2 gap-3 text-sm text-gray-700">
                                        <div>
                                            <p class="text-xs text-gray-500 uppercase tracking-wide">Quantity</p>
                                            <p class="font-semibold text-gray-900">
                                                {{ number_format($request->quantity, 2, ',', '.') }} {{ $request->unit }}
                                            </p>
                                            @if($unitPrice)
                                                <p class="text-xs text-gray-500">Rp {{ number_format($unitPrice, 0, ',', '.') }} / {{ $request->unit }}</p>
                                            @endif
                                        </div>
                                        <div class="text-right">
                                            <p class="text-xs text-gray-500 uppercase tracking-wide">Total</p>
                                            <p class="font-semibold text-gray-900">
                                                @if($totalAmount !== null)
                                                    Rp {{ number_format($totalAmount, 0, ',', '.') }}
                                                @else
                                                    <span class="text-gray-400">Awaiting quotation</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>

                                    <div class="pt-2">
                                        <a href="{{ route('customer.requests.show', $request) }}"
                                           class="inline-flex items-center px-3 py-2 text-sm text-green-600 border border-green-200 rounded-lg hover:bg-green-50 transition-colors">
                                            <i class="fas fa-file-invoice mr-2"></i> View invoice
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <div class="p-6 text-center text-gray-500">
                                    <p class="text-sm">No purchase records found.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    @if($requests->hasPages())
                        <div class="px-6 py-4 border-t border-gray-100">
                            {{ $requests->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleButton = document.getElementById('toggleFilters');
        const filtersForm = document.getElementById('filtersForm');

        if (toggleButton && filtersForm) {
            toggleButton.addEventListener('click', () => {
                filtersForm.classList.toggle('hidden');
                const label = toggleButton.querySelector('span');
                if (label) {
                    const isHidden = filtersForm.classList.contains('hidden');
                    label.textContent = isHidden ? 'Show Filters' : 'Hide Filters';
                }
            });
        }
    });
</script>
@endpush
