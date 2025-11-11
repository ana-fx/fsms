@extends('layouts.app')

@section('title', 'Customer Supplier Access - FSMS')

@section('content')
<div class="flex bg-gray-100 min-h-screen w-full overflow-x-hidden">
    @include('admin.partials.sidebar')

    <!-- Mobile Menu Button -->
    <button id="openSidebar" class="lg:hidden fixed top-4 left-4 z-50 p-2 bg-white rounded-lg shadow-lg hover:bg-gray-100 transition-colors">
        <i class="fas fa-bars text-gray-700 text-xl"></i>
    </button>

    <!-- Main Content -->
    <div class="w-full lg:ml-64 transition-all duration-300">
        <div class="flex-1 bg-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <!-- Header -->
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Customer Supplier Access</h1>
                        <p class="text-gray-600 mt-2">Manage which suppliers each customer can view.</p>
                    </div>
                    <a href="{{ route('admin.customer-access.create') }}"
                       class="inline-flex items-center justify-center px-5 py-3 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 transition-colors font-semibold">
                        <i class="fas fa-plus mr-2"></i>
                        Assign Suppliers
                    </a>
                </div>

                @if(session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center space-x-3">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                        <p class="font-semibold mb-2"><i class="fas fa-exclamation-circle mr-2"></i>There were some issues with your request:</p>
                        <ul class="list-disc list-inside text-sm space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Summary Section -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-users mr-2 text-green-600"></i>Customer Access Overview
                    </h2>
                    @if($customers->count() > 0)
                        <div class="space-y-4">
                            @foreach($customers as $customer)
                                <div class="border border-gray-200 rounded-lg p-4 hover:border-green-300 transition-colors">
                                    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900">{{ $customer->name }}</h3>
                                            <p class="text-sm text-gray-500">{{ $customer->email }} • {{ $customer->phone }}</p>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            @php
                                                $queryParams = [
                                                    'customer_id' => $customer->id,
                                                    'supplier_ids' => $customer->accessibleSuppliers->pluck('id')->all(),
                                                ];
                                            @endphp
                                            <a href="{{ route('admin.customer-access.create', $queryParams) }}"
                                               class="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-700 rounded-md hover:bg-blue-100 transition-colors text-sm font-medium">
                                                <i class="fas fa-sync-alt mr-2"></i>Update Assignment
                                            </a>
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        @if($customer->accessibleSuppliers->isNotEmpty())
                                            <div class="flex flex-wrap gap-2">
                                                @foreach($customer->accessibleSuppliers as $supplier)
                                                    <span class="inline-flex items-center px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm">
                                                        <i class="fas fa-store mr-2"></i>{{ $supplier->name }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="text-sm text-yellow-700 bg-yellow-50 border border-yellow-200 rounded-md px-3 py-2 inline-flex items-center">
                                                <i class="fas fa-exclamation-triangle mr-2"></i>No suppliers assigned
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-sm">No customers found.</p>
                    @endif
                </div>

                <!-- Filters -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <form method="GET" action="{{ route('admin.customer-access.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="customer_id" class="block text-sm font-medium text-gray-700 mb-1">Filter by Customer</label>
                            <select id="customer_id" name="customer_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                                <option value="">All Customers</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ ($filters['customer_id'] ?? '') == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="supplier_id" class="block text-sm font-medium text-gray-700 mb-1">Filter by Supplier</label>
                            <select id="supplier_id" name="supplier_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                                <option value="">All Suppliers</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ ($filters['supplier_id'] ?? '') == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-end gap-2">
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold">
                                <i class="fas fa-filter mr-2"></i>Apply
                            </button>
                            <a href="{{ route('admin.customer-access.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-semibold">
                                <i class="fas fa-times mr-2"></i>Reset
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Assignments Table -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-semibold text-gray-900">Access Records</h2>
                        <p class="text-sm text-gray-500">Showing {{ $assignments->firstItem() ?? 0 }} - {{ $assignments->lastItem() ?? 0 }} of {{ $assignments->total() }}</p>
                    </div>
                    @if($assignments->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supplier</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned By</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned At</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($assignments as $assignment)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-4 py-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $assignment->customer->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $assignment->customer->email }}</div>
                                            </td>
                                            <td class="px-4 py-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $assignment->supplier->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $assignment->supplier->email }}</div>
                                            </td>
                                            <td class="px-4 py-4">
                                                <div class="text-sm text-gray-900">{{ optional($assignment->creator)->name ?? 'System' }}</div>
                                                <div class="text-xs text-gray-500">{{ optional($assignment->creator)->email }}</div>
                                            </td>
                                            <td class="px-4 py-4">
                                                <div class="text-sm text-gray-900">{{ $assignment->created_at->format('d M Y') }}</div>
                                                <div class="text-xs text-gray-500">{{ $assignment->created_at->format('H:i') }}</div>
                                            </td>
                                            <td class="px-4 py-4 text-right">
                                                <div class="flex items-center justify-end gap-2">
                                                    <a href="{{ route('admin.customer-access.edit', $assignment) }}"
                                                       class="inline-flex items-center px-3 py-2 bg-blue-50 text-blue-600 rounded-md hover:bg-blue-100 transition-colors text-sm font-medium">
                                                        <i class="fas fa-edit mr-1"></i>Edit
                                                    </a>
                                                    <form method="POST" action="{{ route('admin.customer-access.destroy', $assignment) }}"
                                                          onsubmit="return confirm('Remove access for {{ $assignment->customer->name }} to {{ $assignment->supplier->name }}?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="inline-flex items-center px-3 py-2 bg-red-50 text-red-600 rounded-md hover:bg-red-100 transition-colors text-sm font-medium">
                                                            <i class="fas fa-trash-alt mr-1"></i>Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-6">
                            {{ $assignments->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-link-slash text-5xl text-gray-300 mb-4"></i>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">No access records found</h3>
                            <p class="text-gray-500 mb-4">Create a new assignment to allow a customer to view specific suppliers.</p>
                            <a href="{{ route('admin.customer-access.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold">
                                <i class="fas fa-plus-circle mr-2"></i>Create Assignment
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        window.updateSidebarActiveState && window.updateSidebarActiveState('customer-access');
    });
</script>
@endsection
@endsection

