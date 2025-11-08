@extends('layouts.app')

@section('title', 'Custom Requests - Admin')

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
                        <h1 class="text-3xl font-bold text-gray-900">Custom Requests</h1>
                        <p class="mt-2 text-gray-600">Manage and approve custom ingredient requests from customers</p>
                    </div>
                </div>

                <!-- Requests List -->
                <div class="bg-white rounded-lg shadow mx-4 sm:mx-6 lg:mx-8">
                    <!-- Filter Section inside table container -->
                    <div class="px-4 sm:px-6 py-3 border-b border-gray-200">
                        <form method="GET" action="{{ route('admin.custom-requests.index') }}" class="flex flex-wrap items-end gap-3">
                            <!-- Status Filter -->
                            <div class="flex-1 min-w-[120px]">
                                <label for="status" class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                                <select name="status" id="status" class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-1 focus:ring-green-500 focus:border-green-500">
                                    <option value="">All</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="payment_pending" {{ request('status') == 'payment_pending' ? 'selected' : '' }}>Payment Pending</option>
                                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </div>

                            <!-- Date From -->
                            <div class="flex-1 min-w-[140px]">
                                <label for="date_from" class="block text-xs font-medium text-gray-600 mb-1">Date From</label>
                                <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-1 focus:ring-green-500 focus:border-green-500">
                            </div>

                            <!-- Date To -->
                            <div class="flex-1 min-w-[140px]">
                                <label for="date_to" class="block text-xs font-medium text-gray-600 mb-1">Date To</label>
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
                                <a href="{{ route('admin.custom-requests.index') }}" class="px-3 py-1.5 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors text-sm" title="Reset">
                                    <i class="fas fa-redo"></i>
                                </a>
                            </div>
                        </form>
                    </div>

                    @if($customRequests->count() > 0)
                        @php
                            $statusColors = [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'payment_pending' => 'bg-blue-100 text-blue-800',
                                'paid' => 'bg-green-100 text-green-800',
                                'rejected' => 'bg-red-100 text-red-800',
                            ];
                            $statusLabels = [
                                'pending' => 'Pending',
                                'payment_pending' => 'Payment Pending',
                                'paid' => 'Paid',
                                'rejected' => 'Rejected',
                            ];
                        @endphp

                        <div class="overflow-x-auto">
                            <table class="w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Order</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Customer</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Ingredient</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Quantity</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Status</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Assigned Supplier</th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($customRequests as $request)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                <div class="text-sm font-semibold text-gray-900">{{ $request->order_number }}</div>
                                                <div class="text-xs text-gray-500">{{ $request->created_at->format('d M Y') }}</div>
                                            </td>
                                            <td class="px-4 py-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $request->customer->name }}</div>
                                                <div class="text-xs text-gray-500">{{ $request->customer->email }}</div>
                                            </td>
                                            <td class="px-4 py-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $request->title }}</div>
                                                @if($request->description)
                                                    <div class="text-xs text-gray-500 truncate max-w-xs">{{ $request->description }}</div>
                                                @endif
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ number_format($request->quantity, 2) }} {{ $request->unit }}</div>
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full {{ $statusColors[$request->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                    {{ $statusLabels[$request->status] ?? $request->status }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-4">
                                                @if($request->assignedSupplier)
                                                    <div class="text-sm font-medium text-gray-900">{{ $request->assignedSupplier->name }}</div>
                                                @else
                                                    <span class="text-xs text-gray-400 italic">Not assigned</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-center">
                                                <a href="{{ route('admin.custom-requests.show', $request) }}"
                                                   class="inline-flex items-center justify-center w-9 h-9 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition-colors"
                                                   title="View Details">
                                                    <i class="fas fa-eye text-sm"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="px-4 sm:px-6 py-4 border-t border-gray-200">
                            {{ $customRequests->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-inbox text-4xl text-gray-400 mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No custom requests found</h3>
                            <p class="text-gray-500">There are no custom requests matching your criteria.</p>
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
@endsection
