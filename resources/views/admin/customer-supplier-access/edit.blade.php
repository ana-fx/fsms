@extends('layouts.app')

@section('title', 'Edit Supplier Access - FSMS')

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
                    <h1 class="text-3xl font-bold text-gray-900">Edit Access Record</h1>
                    <p class="text-gray-600 mt-2">Update the supplier access for a specific customer.</p>
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
                        $currentSupplierId = (int) old('supplier_id', $access->supplier_id);
                    @endphp
                    <form method="POST" action="{{ route('admin.customer-access.update', $access) }}" class="space-y-8">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="customer_id" class="block text-sm font-medium text-gray-700 mb-2">Customer</label>
                            <select id="customer_id" name="customer_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" required>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ old('customer_id', $access->customer_id) == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }} ({{ $customer->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('customer_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">Supplier</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach($suppliers as $supplier)
                                    <label class="group flex items-start border border-gray-200 rounded-lg p-4 hover:border-blue-400 hover:shadow-sm transition-colors cursor-pointer">
                                        <input type="radio"
                                               name="supplier_id"
                                               value="{{ $supplier->id }}"
                                               class="mt-1 h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500 supplier-radio"
                                               {{ $currentSupplierId === $supplier->id ? 'checked' : '' }}>
                                        <div class="ml-3">
                                            <p class="text-sm font-semibold text-gray-900 group-hover:text-blue-700">{{ $supplier->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $supplier->email }} • {{ $supplier->phone }}</p>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            @error('supplier_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between bg-gray-50 border border-gray-200 rounded-lg px-4 py-3">
                            <div class="text-sm text-gray-600">
                                <p><span class="font-semibold text-gray-800">Created:</span> {{ $access->created_at->format('d M Y H:i') }}</p>
                                <p><span class="font-semibold text-gray-800">Last Updated:</span> {{ $access->updated_at->format('d M Y H:i') }}</p>
                            </div>
                            <div class="text-sm text-gray-600">
                                <p><span class="font-semibold text-gray-800">Assigned by:</span> {{ optional($access->creator)->name ?? 'System' }}</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('admin.customer-access.index') }}" class="px-5 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-semibold">
                                Cancel
                            </a>
                            <button type="submit" class="px-5 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold">
                                <i class="fas fa-save mr-2"></i>Update Access
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
    });
</script>
@endsection
@endsection

