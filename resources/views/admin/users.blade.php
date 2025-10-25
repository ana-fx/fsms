<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Manage Users' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-green-600 text-white p-4">
            <div class="container mx-auto flex justify-between items-center">
                <h1 class="text-xl font-bold">{{ $title ?? 'Manage Users' }}</h1>
                <div class="space-x-4">
                    <span>Welcome, {{ auth()->user()->name }}</span>
                    <span class="bg-green-500 px-2 py-1 rounded text-sm">{{ auth()->user()->roles->first()->display_name ?? 'No Role' }}</span>
                    <a href="{{ route('admin.dashboard') }}" class="hover:underline">Admin Dashboard</a>
                    <a href="{{ route('home') }}" class="hover:underline">Home</a>
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
                <h2 class="text-2xl font-bold mb-4">User Management</h2>

                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach(\App\Models\User::with('roles')->get() as $user)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $user->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($user->roles->count() > 0)
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                                            @if($user->roles->first()->name === 'super_admin') bg-blue-100 text-blue-800
                                            @elseif($user->roles->first()->name === 'supplier') bg-green-100 text-green-800
                                            @elseif($user->roles->first()->name === 'foundation') bg-purple-100 text-purple-800
                                            @endif">
                                            {{ $user->roles->first()->display_name }}
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">No Role</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->created_at->format('M d, Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button class="text-green-600 hover:text-green-900 mr-3">Edit</button>
                                    <button class="text-red-600 hover:text-red-900">Delete</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    <h3 class="text-lg font-semibold mb-4">Add New User</h3>
                    <div class="space-x-4">
                        <button class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Add User</button>
                        <button class="bg-green-700 text-white px-4 py-2 rounded hover:bg-green-800">Bulk Import</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
