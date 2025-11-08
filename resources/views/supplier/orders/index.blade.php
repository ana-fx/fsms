@extends('layouts.app')

@section('title', 'Orders - FSMS')

@section('content')
<div class="flex bg-gray-100 min-h-screen w-full overflow-x-hidden">
    @include('supplier.partials.sidebar')

    <!-- Mobile Menu Button -->
    <button id="openSidebar" class="lg:hidden fixed top-4 left-4 z-50 p-2 bg-white rounded-lg shadow-lg hover:bg-gray-100 transition-colors">
        <i class="fas fa-bars text-gray-700 text-xl"></i>
    </button>

    <!-- Main Content -->
    <div class="w-full lg:ml-64 transition-all duration-300">
        <div class="flex-1 bg-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <!-- Header -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900">Orders</h1>
                    <p class="text-gray-600 mt-2">Manage and track customer orders</p>
                </div>

                <!-- Filters and Search -->
                <div class="bg-white rounded-lg shadow-md p-4 mb-6">
                    <form method="GET" action="{{ route('supplier.orders.index') }}" class="flex flex-col md:flex-row gap-4">
                        <div class="flex-1">
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   placeholder="Search by order number or customer name..." 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        </div>
                        @if(request('status'))
                            <input type="hidden" name="status" value="{{ request('status') }}">
                        @endif
                        <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold">
                            <i class="fas fa-search mr-2"></i>Search
                        </button>
                        @if(request('search') || request('status'))
                            <a href="{{ route('supplier.orders.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-semibold">
                                <i class="fas fa-times mr-2"></i>Clear
                            </a>
                        @endif
                    </form>
                </div>

                <!-- Orders Table -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    @if($orders->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order #</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($orders as $order)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $order->order_number ?? 'N/A' }}
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center">
                                                @if($order->foodItem)
                                                    <div class="p-2 rounded-lg" style="background-color: {{ $order->foodItem->foodCategory->color }}20">
                                                        <i class="{{ $order->foodItem->foodCategory->icon }} text-sm" style="color: {{ $order->foodItem->foodCategory->color }}"></i>
                                                    </div>
                                                    <div class="ml-3">
                                                        <div class="text-sm font-medium text-gray-900">{{ $order->foodItem->name }}</div>
                                                        <div class="text-xs text-gray-500">Rp {{ number_format($order->foodItem->price, 0, ',', '.') }}/{{ $order->foodItem->unit }}</div>
                                                    </div>
                                                @else
                                                    <div class="text-sm font-medium text-gray-900">{{ $order->title }}</div>
                                                    @if($order->price)
                                                        <div class="text-xs text-gray-500">Rp {{ number_format($order->price, 0, ',', '.') }}/{{ $order->unit }}</div>
                                                    @endif
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            <div class="text-gray-900">{{ $order->customer->name }}</div>
                                            <div class="text-gray-500 text-xs">{{ $order->customer->email }}</div>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900">
                                            {{ number_format($order->quantity, 2) }} {{ $order->unit }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900 font-semibold">
                                            @if($order->foodItem)
                                                Rp {{ number_format($order->foodItem->price * $order->quantity, 0, ',', '.') }}
                                            @elseif($order->price)
                                                Rp {{ number_format($order->price * $order->quantity, 0, ',', '.') }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm">
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
                                                    'payment_pending' => 'Payment Pending',
                                                    'paid' => 'Paid',
                                                    'shipping' => 'Shipping',
                                                    'delivered' => 'Delivered',
                                                    'completed' => 'Completed',
                                                    'rejected' => 'Rejected',
                                                ];
                                            @endphp
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                {{ $statusLabels[$order->status] ?? $order->status }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-500 whitespace-nowrap">
                                            {{ $order->created_at->format('d M Y') }}
                                        </td>
                                        <td class="px-4 py-3 text-right text-sm font-medium">
                                            <a href="{{ route('supplier.orders.show', $order) }}" class="text-green-600 hover:text-green-900">
                                                <i class="fas fa-eye mr-1"></i>View
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="px-4 py-3 border-t border-gray-200">
                            {{ $orders->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-inbox text-4xl text-gray-400 mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No orders found</h3>
                            <p class="text-gray-500">
                                @if(request('status') || request('search'))
                                    Try adjusting your filters or search terms
                                @else
                                    Start by adding active ingredients to receive orders
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

