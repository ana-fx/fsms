@extends('layouts.app')

@section('title', 'Create Ingredient Request - FSMS')

@section('content')
<div class="flex bg-gray-100 min-h-screen w-full overflow-x-hidden">
    @include('customer.partials.sidebar')

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
        <h1 class="text-3xl font-bold text-gray-900">Create Ingredient Request</h1>
        <p class="text-gray-600 mt-2">Submit ingredient requests for your programs</p>
    </div>

    <!-- Request Form -->
    <div class="bg-white rounded-lg shadow-md p-8">
        <form class="space-y-6">
            <!-- Customer Info -->
            <div class="border-b border-gray-200 pb-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Customer Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Customer Name</label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" value="{{ auth()->user()->name }}" readonly>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Program</label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option>Children Lunch Program</option>
                            <option>Family Assistance Program</option>
                            <option>Community Kitchen Program</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Food Items -->
            <div class="border-b border-gray-200 pb-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-900">Ingredients</h2>
                    <button type="button" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 text-sm">
                        <i class="fas fa-plus mr-2"></i>Add Item
                    </button>
                </div>

                <!-- Food Item 1 -->
                <div class="border border-gray-200 rounded-lg p-4 mb-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Ingredient Name</label>
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                                <option>Beras Premium</option>
                                <option>Minyak Goreng</option>
                                <option>Telur Ayam</option>
                                <option>Daging Ayam</option>
                                <option>Sayuran Segar</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                            <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="50">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Unit</label>
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                                <option>kg</option>
                                <option>liter</option>
                                <option>butir</option>
                                <option>pack</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="button" class="text-red-600 hover:text-red-800">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Food Item 2 -->
                <div class="border border-gray-200 rounded-lg p-4 mb-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Bahan</label>
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                                <option>Minyak Goreng</option>
                                <option>Beras Premium</option>
                                <option>Telur Ayam</option>
                                <option>Daging Ayam</option>
                                <option>Sayuran Segar</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah</label>
                            <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="20">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Satuan</label>
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                                <option>liter</option>
                                <option>kg</option>
                                <option>butir</option>
                                <option>pack</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="button" class="text-red-600 hover:text-red-800">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Info -->
            <div class="border-b border-gray-200 pb-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Additional Information</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option>Normal</option>
                            <option>High</option>
                            <option>Urgent</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Required Date</label>
                        <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Special Notes</label>
                        <textarea rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter any special notes..."></textarea>
                    </div>
                </div>
            </div>

            <!-- Budget Info -->
            <div class="bg-blue-50 rounded-lg p-4 mb-6">
                <h3 class="text-lg font-semibold text-blue-900 mb-2">Budget Information</h3>
                <p class="text-blue-800 text-sm">
                    Admin will set a maximum price per ingredient based on the available budget. Suppliers will propose their prices which must not exceed the admin's limit.
                </p>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-4">
                <button type="button" class="px-6 py-3 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition">
                    Save Draft
                </button>
                <button type="submit" class="px-6 py-3 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                    <i class="fas fa-paper-plane mr-2"></i>Submit Request
                </button>
            </div>
        </form>
    </div>

    <!-- Help Section -->
    <div class="mt-8 bg-gray-50 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-3">Request Guide</h3>
        <div class="space-y-2 text-sm text-gray-600">
            <p><i class="fas fa-check-circle text-green-500 mr-2"></i>Ensure requested quantities match program needs</p>
            <p><i class="fas fa-check-circle text-green-500 mr-2"></i>Add special notes if there are specific requirements</p>
            <p><i class="fas fa-check-circle text-green-500 mr-2"></i>Requests are processed by admin within 1-2 business days</p>
            <p><i class="fas fa-check-circle text-green-500 mr-2"></i>You will receive notifications when a request is approved or rejected</p>
        </div>
    </div>
        </div>
        </div>
    </div>
</div>
@endsection
