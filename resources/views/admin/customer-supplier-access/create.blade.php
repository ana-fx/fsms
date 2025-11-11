@extends('layouts.app')

@section('title', 'Assign Supplier Access - FSMS')

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
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="mb-6">
                    <h1 class="text-3xl font-bold text-gray-900">Assign Suppliers to Customer</h1>
                    <p class="text-gray-600 mt-2">Select which suppliers can be viewed by a customer.</p>
                </div>

                @if($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                        <p class="font-semibold mb-2"><i class="fas fa-exclamation-circle mr-2"></i>There were some issues with your input:</p>
                        <ul class="list-disc list-inside text-sm space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="bg-white rounded-lg shadow-md p-6">
                    @php
                        $preselectedSuppliers = collect(old('supplier_ids', $selectedSupplierIds?->all() ?? []))
                            ->map(fn ($id) => (int) $id)
                            ->all();
                    @endphp
                    <form method="POST" action="{{ route('admin.customer-access.store') }}" class="space-y-8">
                        @csrf

                        <div>
                            <label for="customer_id" class="block text-sm font-medium text-gray-700 mb-2">Customer</label>
                            <select id="customer_id" name="customer_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" required>
                                <option value="">Select customer</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}"
                                        {{ old('customer_id', $selectedCustomerId) == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }} ({{ $customer->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('customer_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Suppliers</label>
                                    <p class="text-xs text-gray-500 mt-1">Choose suppliers that this customer may access. Leave all unchecked to revoke access.</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button type="button"
                                            id="selectAllSuppliers"
                                            class="px-3 py-1.5 text-xs font-semibold bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition-colors">
                                        <i class="fas fa-check-double mr-1"></i>Select All
                                    </button>
                                    <button type="button"
                                            id="clearAllSuppliers"
                                            class="px-3 py-1.5 text-xs font-semibold bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                                        <i class="fas fa-times-circle mr-1"></i>Clear
                                    </button>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach($suppliers as $supplier)
                                    @php
                                        $isChecked = in_array($supplier->id, $preselectedSuppliers, true);
                                    @endphp
                                    <label class="group flex items-start border border-gray-200 rounded-lg p-4 hover:border-green-400 hover:shadow-sm transition-colors cursor-pointer">
                                        <input type="checkbox"
                                               name="supplier_ids[]"
                                               value="{{ $supplier->id }}"
                                               class="mt-1 h-4 w-4 text-green-600 border-gray-300 rounded focus:ring-green-500 supplier-checkbox"
                                               {{ $isChecked ? 'checked' : '' }}>
                                        <div class="ml-3">
                                            <p class="text-sm font-semibold text-gray-900 group-hover:text-green-700">{{ $supplier->name }}</p>
                        <p class="text-xs text-gray-500">{{ $supplier->email }} • {{ $supplier->phone }}</p>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            @error('supplier_ids')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            @error('supplier_ids.*')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('admin.customer-access.index') }}" class="px-5 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-semibold">
                                Cancel
                            </a>
                            <button type="submit" class="px-5 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold">
                                <i class="fas fa-save mr-2"></i>Save Assignment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        window.updateSidebarActiveState && window.updateSidebarActiveState('customer-access');

        const selectAllBtn = document.getElementById('selectAllSuppliers');
        const clearAllBtn = document.getElementById('clearAllSuppliers');
        const checkboxes = Array.from(document.querySelectorAll('.supplier-checkbox'));

        if (selectAllBtn) {
            selectAllBtn.addEventListener('click', () => {
                checkboxes.forEach(cb => cb.checked = true);
            });
        }

        if (clearAllBtn) {
            clearAllBtn.addEventListener('click', () => {
                checkboxes.forEach(cb => cb.checked = false);
            });
        }
    });
</script>
@endsection
@endsection

