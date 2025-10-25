<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Manage Products' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-green-600 text-white p-4">
            <div class="container mx-auto flex justify-between items-center">
                <h1 class="text-xl font-bold">{{ $title ?? 'Manage Products' }}</h1>
                <div class="space-x-4">
                    <span>Welcome, {{ auth()->user()->name }}</span>
                    <span class="bg-green-500 px-2 py-1 rounded text-sm">{{ auth()->user()->roles->first()->display_name ?? 'No Role' }}</span>
                    <a href="{{ route('supplier.dashboard') }}" class="hover:underline">Supplier Dashboard</a>
                    <a href="{{ route('dashboard') }}" class="hover:underline">Main Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="hover:underline">Logout</button>
                    </form>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="container mx-auto p-6">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-2xl font-bold mb-4">Product Management</h2>

                <div class="mb-6">
                    <button class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Add New Product</button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Sample Product Cards -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="bg-gray-200 h-48 rounded mb-4 flex items-center justify-center">
                            <span class="text-gray-500">Product Image</span>
                        </div>
                        <h3 class="font-semibold text-lg mb-2">Sample Product 1</h3>
                        <p class="text-gray-600 mb-2">Description of the product goes here...</p>
                        <div class="flex justify-between items-center">
                            <span class="text-green-600 font-bold">$99.99</span>
                            <span class="text-sm text-gray-500">Stock: 50</span>
                        </div>
                        <div class="mt-3 space-x-2">
                            <button class="text-blue-600 hover:text-blue-800 text-sm">Edit</button>
                            <button class="text-red-600 hover:text-red-800 text-sm">Delete</button>
                        </div>
                    </div>

                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="bg-gray-200 h-48 rounded mb-4 flex items-center justify-center">
                            <span class="text-gray-500">Product Image</span>
                        </div>
                        <h3 class="font-semibold text-lg mb-2">Sample Product 2</h3>
                        <p class="text-gray-600 mb-2">Description of the product goes here...</p>
                        <div class="flex justify-between items-center">
                            <span class="text-green-600 font-bold">$149.99</span>
                            <span class="text-sm text-gray-500">Stock: 25</span>
                        </div>
                        <div class="mt-3 space-x-2">
                            <button class="text-blue-600 hover:text-blue-800 text-sm">Edit</button>
                            <button class="text-red-600 hover:text-red-800 text-sm">Delete</button>
                        </div>
                    </div>

                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="bg-gray-200 h-48 rounded mb-4 flex items-center justify-center">
                            <span class="text-gray-500">Product Image</span>
                        </div>
                        <h3 class="font-semibold text-lg mb-2">Sample Product 3</h3>
                        <p class="text-gray-600 mb-2">Description of the product goes here...</p>
                        <div class="flex justify-between items-center">
                            <span class="text-green-600 font-bold">$79.99</span>
                            <span class="text-sm text-gray-500">Stock: 100</span>
                        </div>
                        <div class="mt-3 space-x-2">
                            <button class="text-blue-600 hover:text-blue-800 text-sm">Edit</button>
                            <button class="text-red-600 hover:text-red-800 text-sm">Delete</button>
                        </div>
                    </div>
                </div>

                <div class="mt-8">
                    <h3 class="text-xl font-semibold mb-4">Product Statistics</h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="bg-green-50 p-4 rounded-lg text-center">
                            <div class="text-2xl font-bold text-green-600">15</div>
                            <div class="text-sm text-gray-600">Total Products</div>
                        </div>
                        <div class="bg-blue-50 p-4 rounded-lg text-center">
                            <div class="text-2xl font-bold text-blue-600">3</div>
                            <div class="text-sm text-gray-600">Low Stock</div>
                        </div>
                        <div class="bg-purple-50 p-4 rounded-lg text-center">
                            <div class="text-2xl font-bold text-purple-600">$2,450</div>
                            <div class="text-sm text-gray-600">Total Value</div>
                        </div>
                        <div class="bg-yellow-50 p-4 rounded-lg text-center">
                            <div class="text-2xl font-bold text-yellow-600">5</div>
                            <div class="text-sm text-gray-600">Pending Orders</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
