@extends('layouts.app')

@section('title', 'User Management - FSMS')

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
        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">User Management</h1>
            <p class="text-sm lg:text-base text-gray-600 mt-2">Manage all users in the FSMS system</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6 card-hover">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <i class="fas fa-users text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Accounts</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $users->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 card-hover">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                        <i class="fas fa-user-shield text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Super Admin</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\User::whereHas('roles', function($q) { $q->where('name', 'super_admin'); })->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 card-hover">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <i class="fas fa-store text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Supplier</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\User::whereHas('roles', function($q) { $q->where('name', 'supplier'); })->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 card-hover">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                        <i class="fas fa-building text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Customer</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\User::whereHas('roles', function($q) { $q->where('name', 'customer'); })->count() }}</p>
                    </div>
                </div>
            </div>
        </div>
    <!-- User Management Table -->
    <div class="bg-white rounded-lg shadow-md p-4 lg:p-6">
        <div class="flex flex-col gap-4 mb-6">
            <!-- Header Row -->
            <div class="flex items-center justify-between">
                <h2 class="text-lg md:text-xl font-semibold text-gray-900">User List</h2>
                <button onclick="openAddUserModal()" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition flex items-center text-sm md:text-base">
                    <i class="fas fa-user-plus mr-2"></i>
                    <span class="hidden sm:inline">Add New User</span>
                    <span class="sm:hidden">Add</span>
                </button>
            </div>

            <!-- Filter Row -->
            <div class="flex flex-col sm:flex-row gap-2">
                <select class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm flex-1 sm:flex-none sm:w-auto">
                    <option>All Roles</option>
                    <option>Super Admin</option>
                    <option>Supplier</option>
                    <option>Customer</option>
                </select>
                <input type="text" placeholder="Search users..." class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 text-sm flex-1 sm:flex-none sm:w-64">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50 hidden md:table-header-group">
                    <tr>
                        <th class="px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Email</th>
                        <th class="px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden xl:table-cell">Status</th>
                        <th class="px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Registered</th>
                        <th class="px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($users as $user)
                    <tr data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}" data-user-email="{{ $user->email }}" data-user-phone="{{ $user->phone ?? '' }}" data-user-role="{{ $user->roles->first()->name ?? '' }}" class="hover:bg-gray-50">
                        <td class="px-3 lg:px-6 py-3 md:py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $user->id }}</td>
                        <td class="px-3 lg:px-6 py-3 md:py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->name }}</td>
                        <td class="px-3 lg:px-6 py-3 md:py-4 whitespace-nowrap text-sm text-gray-500 hidden lg:table-cell">{{ $user->email }}</td>
                        <td class="px-3 lg:px-6 py-3 md:py-4 whitespace-nowrap">
                            @if($user->roles->count() > 0)
                                @if($user->roles->first()->name === 'super_admin')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ $user->roles->first()->display_name }}
                                    </span>
                                @elseif($user->roles->first()->name === 'supplier')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ $user->roles->first()->display_name }}
                                    </span>
                                @elseif($user->roles->first()->name === 'customer')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        {{ $user->roles->first()->display_name }}
                                    </span>
                                @endif
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">No Role</span>
                            @endif
                        </td>
                        <td class="px-3 lg:px-6 py-3 md:py-4 whitespace-nowrap hidden xl:table-cell">
                            @if($user->is_active == false)
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Disabled</span>
                            @elseif($user->email_verified_at)
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Unverified</span>
                            @endif
                        </td>
                        <td class="px-3 lg:px-6 py-3 md:py-4 whitespace-nowrap text-sm text-gray-500 hidden lg:table-cell">{{ $user->created_at->format('d M Y') }}</td>
                        <td class="px-3 lg:px-6 py-3 md:py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-1 md:space-x-2">
                                <button class="text-green-600 hover:text-green-900 p-1 md:p-0" onclick="editUser({{ $user->id }})" title="Edit User">
                                    <i class="fas fa-edit text-sm md:text-base"></i>
                                </button>
                                <button class="text-green-600 hover:text-green-900 p-1 md:p-0" onclick="changePassword({{ $user->id }})" title="Change Password">
                                    <i class="fas fa-key text-sm md:text-base"></i>
                                </button>
                                @if($user->id !== auth()->id())
                                <button class="{{ ($user->is_active == false) ? 'text-green-600 hover:text-green-900' : 'text-orange-600 hover:text-orange-900' }} p-1 md:p-0" 
                                        onclick="toggleUserStatus({{ $user->id }}, {{ $user->is_active ? 'true' : 'false' }})" 
                                        title="{{ ($user->is_active == false) ? 'Enable Account' : 'Disable Account' }}">
                                    <i class="fas {{ ($user->is_active == false) ? 'fa-check-circle' : 'fa-ban' }} text-sm md:text-base"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="text-xs sm:text-sm text-gray-700 text-center sm:text-left">
                Showing <span class="font-medium">1</span> to <span class="font-medium">{{ $users->count() }}</span> of <span class="font-medium">{{ $users->count() }}</span> results
            </div>
            @if($users->count() > 0)
            <div class="flex space-x-2">
                <button class="px-2 sm:px-3 py-2 border border-gray-300 rounded-md text-xs sm:text-sm text-gray-700 hover:bg-gray-50 disabled:opacity-50" disabled>Previous</button>
                <button class="px-2 sm:px-3 py-2 bg-blue-600 text-white rounded-md text-xs sm:text-sm">1</button>
                <button class="px-2 sm:px-3 py-2 border border-gray-300 rounded-md text-xs sm:text-sm text-gray-700 hover:bg-gray-50 disabled:opacity-50" disabled>Next</button>
            </div>
            @endif
        </div>

        </div>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div id="addUserModal" class="fixed inset-0 z-[100] hidden" style="display: none;">
    <!-- Background overlay - covers full viewport including sidebar -->
    <div class="fixed inset-0 transition-opacity" style="background-color: rgba(0, 0, 0, 0.5); left: 0; right: 0; top: 0; bottom: 0;" onclick="closeAddUserModal()"></div>

    <!-- Modal panel - centered in area after sidebar -->
    <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none" id="addUserModalContainer">
        <div class="bg-white bg-opacity-90 backdrop-blur-lg rounded-lg shadow-xl max-w-md w-full border border-white border-opacity-30 pointer-events-auto transform transition-all" onclick="event.stopPropagation()">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Add New User</h3>
                <form method="POST" action="{{ route('admin.users.store') }}">
                    @csrf
                    <div class="mb-4">
                        <label for="addName" class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                        <input type="text" id="addName" name="name" value="{{ old('name') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" required>
                        @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label for="addEmail" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" id="addEmail" name="email" value="{{ old('email') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" required>
                        @error('email') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label for="addPhone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                        <input type="tel" id="addPhone" name="phone" value="{{ old('phone') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Enter phone number (e.g., 081234567890)" required>
                        @error('phone') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label for="addPassword" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <input type="password" id="addPassword" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" required>
                        @error('password') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label for="addPasswordConfirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                        <input type="password" id="addPasswordConfirmation" name="password_confirmation" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" required>
                    </div>
                    <div class="mb-6">
                        <label for="addRole" class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                        <select id="addRole" name="role" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" required>
                            <option value="">Select Role</option>
                            <option value="super_admin" {{ old('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                            <option value="supplier" {{ old('role') == 'supplier' ? 'selected' : '' }}>Supplier</option>
                            <option value="customer" {{ old('role') == 'customer' ? 'selected' : '' }}>Customer</option>
                        </select>
                        @error('role') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeAddUserModal()" class="px-4 py-2 text-gray-700 bg-gray-200 rounded hover:bg-gray-300">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Add User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div id="editUserModal" class="fixed inset-0 z-[100] hidden" style="display: none;">
    <!-- Background overlay - covers full viewport including sidebar -->
    <div class="fixed inset-0 transition-opacity" style="background-color: rgba(0, 0, 0, 0.5); left: 0; right: 0; top: 0; bottom: 0;" onclick="closeEditUserModal()"></div>

    <!-- Modal panel - centered in area after sidebar -->
    <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none" id="editUserModalContainer">
        <div class="bg-white bg-opacity-90 backdrop-blur-lg rounded-lg shadow-xl max-w-md w-full border border-white border-opacity-30 pointer-events-auto transform transition-all" onclick="event.stopPropagation()">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Edit User</h3>
                <form method="POST" id="editUserForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editUserId" name="id">

                    <div class="mb-4">
                        <label for="editName" class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                        <input type="text" id="editName" name="name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" required>
                        @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label for="editEmail" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" id="editEmail" name="email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" required>
                        @error('email') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-6">
                        <label for="editPhone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                        <input type="tel" id="editPhone" name="phone" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Enter phone number (e.g., 081234567890)" required>
                        @error('phone') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeEditUserModal()" class="px-4 py-2 text-gray-700 bg-gray-200 rounded hover:bg-gray-300">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div id="changePasswordModal" class="fixed inset-0 z-[100] hidden" style="display: none;">
    <!-- Background overlay - covers full viewport including sidebar -->
    <div class="fixed inset-0 transition-opacity" style="background-color: rgba(0, 0, 0, 0.5); left: 0; right: 0; top: 0; bottom: 0;" onclick="closePasswordModal()"></div>

    <!-- Modal panel - centered in area after sidebar -->
    <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none" id="changePasswordModalContainer">
        <div class="bg-white bg-opacity-90 backdrop-blur-lg rounded-lg shadow-xl max-w-md w-full border border-white border-opacity-30 pointer-events-auto transform transition-all" onclick="event.stopPropagation()">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Change Password</h3>
                <form>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                        <input type="password" id="newPassword" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Enter new password">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                        <input type="password" id="confirmPassword" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Confirm new password">
                    </div>
                    <div class="mb-6">
                        <div class="flex items-center">
                            <input type="checkbox" id="showPassword" class="mr-2">
                            <label for="showPassword" class="text-sm text-gray-700">Show password</label>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closePasswordModal()" class="px-4 py-2 text-gray-700 bg-gray-200 rounded hover:bg-gray-300">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Change Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Confirm Modal -->
<div id="confirmModal" class="fixed inset-0 z-[100] hidden" style="display: none;">
    <!-- Background overlay - covers full viewport including sidebar -->
    <div class="fixed inset-0 transition-opacity" style="background-color: rgba(0, 0, 0, 0.5); left: 0; right: 0; top: 0; bottom: 0;" onclick="closeConfirmModal()"></div>

    <!-- Modal panel - centered in area after sidebar -->
    <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none" id="confirmModalContainer">
        <div class="bg-white bg-opacity-90 backdrop-blur-lg rounded-lg shadow-xl max-w-md w-full border border-white border-opacity-30 pointer-events-auto transform transition-all" onclick="event.stopPropagation()">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-orange-500 text-2xl"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-semibold text-gray-900">Confirm Action</h3>
                    </div>
                </div>
                <p id="confirmMessage" class="text-sm text-gray-600 mb-6"></p>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeConfirmModal()" class="px-4 py-2 text-gray-700 bg-gray-200 rounded hover:bg-gray-300">Cancel</button>
                    <button type="button" onclick="executeConfirmAction()" class="px-4 py-2 bg-orange-600 text-white rounded hover:bg-orange-700">Confirm</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript Functions -->
<script>
function openAddUserModal() {
    const modal = document.getElementById('addUserModal');
    if (!modal) {
        console.error('Add user modal not found');
        return;
    }
    
    // Calculate center position considering sidebar on desktop
    const isDesktop = window.innerWidth >= 1024; // lg breakpoint
    const sidebarWidth = isDesktop ? 256 : 0; // 64 * 4 = 256px (lg:w-64)
    
    // Get viewport dimensions
    const viewportWidth = window.innerWidth;
    
    // Calculate available width (viewport minus sidebar)
    const availableWidth = viewportWidth - sidebarWidth;
    
    // Center modal in available content area (ignoring sidebar)
    const modalContainer = document.getElementById('addUserModalContainer');
    if (modalContainer) {
        modalContainer.style.left = sidebarWidth + 'px';
        modalContainer.style.width = availableWidth + 'px';
    }
    
    // Show modal
    modal.classList.remove('hidden');
    modal.style.display = 'block';
    
    // Disable body scroll
    document.body.style.overflow = 'hidden';
}

function closeAddUserModal() {
    const modal = document.getElementById('addUserModal');
    if (!modal) {
        return;
    }
    
    modal.classList.add('hidden');
    modal.style.display = 'none';
    
    // Clear form
    const form = modal.querySelector('form');
    if (form) {
        form.reset();
    }
    
    // Enable body scroll
    document.body.style.overflow = '';
}

function editUser(userId) {
    const row = document.querySelector(`tr[data-user-id="${userId}"]`);
    if (!row) return;

    const userData = {
        id: row.getAttribute('data-user-id'),
        name: row.getAttribute('data-user-name'),
        email: row.getAttribute('data-user-email'),
        phone: row.getAttribute('data-user-phone') || '',
        role: row.getAttribute('data-user-role')
    };

    // Populate form
    document.getElementById('editUserId').value = userData.id;
    document.getElementById('editName').value = userData.name;
    document.getElementById('editEmail').value = userData.email;
    document.getElementById('editPhone').value = userData.phone;

    // Set form action
    document.getElementById('editUserForm').action = `/admin/users/${userData.id}`;

    // Show modal
    const modal = document.getElementById('editUserModal');
    if (!modal) {
        console.error('Edit user modal not found');
        return;
    }
    
    // Calculate center position considering sidebar on desktop
    const isDesktop = window.innerWidth >= 1024; // lg breakpoint
    const sidebarWidth = isDesktop ? 256 : 0; // 64 * 4 = 256px (lg:w-64)
    
    // Get viewport dimensions
    const viewportWidth = window.innerWidth;
    
    // Calculate available width (viewport minus sidebar)
    const availableWidth = viewportWidth - sidebarWidth;
    
    // Center modal in available content area (ignoring sidebar)
    const modalContainer = document.getElementById('editUserModalContainer');
    if (modalContainer) {
        modalContainer.style.left = sidebarWidth + 'px';
        modalContainer.style.width = availableWidth + 'px';
    }
    
    modal.classList.remove('hidden');
    modal.style.display = 'block';
    
    // Disable body scroll
    document.body.style.overflow = 'hidden';
}

function closeEditUserModal() {
    const modal = document.getElementById('editUserModal');
    if (!modal) {
        return;
    }
    
    modal.classList.add('hidden');
    modal.style.display = 'none';
    
    const form = document.getElementById('editUserForm');
    if (form) {
        form.reset();
    }
    
    // Enable body scroll
    document.body.style.overflow = '';
}

function changePassword(userId) {
    const modal = document.getElementById('changePasswordModal');
    if (!modal) {
        console.error('Change password modal not found');
        return;
    }
    
    // Calculate center position considering sidebar on desktop
    const isDesktop = window.innerWidth >= 1024; // lg breakpoint
    const sidebarWidth = isDesktop ? 256 : 0; // 64 * 4 = 256px (lg:w-64)
    
    // Get viewport dimensions
    const viewportWidth = window.innerWidth;
    
    // Calculate available width (viewport minus sidebar)
    const availableWidth = viewportWidth - sidebarWidth;
    
    // Center modal in available content area (ignoring sidebar)
    const modalContainer = document.getElementById('changePasswordModalContainer');
    if (modalContainer) {
        modalContainer.style.left = sidebarWidth + 'px';
        modalContainer.style.width = availableWidth + 'px';
    }
    
    // Show modal
    modal.classList.remove('hidden');
    modal.style.display = 'block';
    
    // Disable body scroll
    document.body.style.overflow = 'hidden';
    
    // TODO: Implement change password logic
}

function toggleUserStatus(userId, isActive) {
    const action = isActive ? 'disable' : 'enable';
    const message = `Are you sure you want to ${action} this user account? ${isActive ? 'The user will not be able to login.' : 'The user will be able to login again.'}`;
    
    showConfirmModal(message, function() {
        // Get CSRF token
        let csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (!csrfToken) {
            // Fallback: try to get from existing form
            const existingForm = document.querySelector('form[method="POST"]');
            if (existingForm) {
                const existingToken = existingForm.querySelector('input[name="_token"]');
                if (existingToken) {
                    csrfToken = existingToken.value;
                }
            }
        }
        
        if (!csrfToken) {
            alert('CSRF token not found. Please refresh the page and try again.');
            return;
        }
        
        // Create form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/users/${userId}/toggle-status`;
        form.style.display = 'none';
        
        // Add CSRF token
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken;
        form.appendChild(csrfInput);
        
        document.body.appendChild(form);
        form.submit();
    });
}

function showNotification(message, type = 'success') {
    const colors = {
        success: { bg: 'bg-green-50', border: 'border-green-200', text: 'text-green-800', icon: 'fa-check-circle', iconColor: 'text-green-600' },
        error: { bg: 'bg-red-50', border: 'border-red-200', text: 'text-red-800', icon: 'fa-exclamation-circle', iconColor: 'text-red-600' },
        warning: { bg: 'bg-yellow-50', border: 'border-yellow-200', text: 'text-yellow-800', icon: 'fa-exclamation-triangle', iconColor: 'text-yellow-600' },
        info: { bg: 'bg-blue-50', border: 'border-blue-200', text: 'text-blue-800', icon: 'fa-info-circle', iconColor: 'text-blue-600' }
    };

    const color = colors[type] || colors.success;

    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 ${color.bg} ${color.border} border rounded-lg shadow-lg z-50 flex items-center space-x-3 p-4 animate-slide-in`;
    notification.style.minWidth = '300px';
    notification.innerHTML = `
        <div class="flex-shrink-0">
            <i class="fas ${color.icon} ${color.iconColor} text-xl"></i>
        </div>
        <div class="flex-1">
            <p class="${color.text} font-medium">${message}</p>
        </div>
        <button onclick="this.parentElement.remove()" class="flex-shrink-0 ${color.text} hover:opacity-70 transition-opacity">
            <i class="fas fa-times"></i>
        </button>
    `;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.style.animation = 'slide-out 0.3s ease-out';
        setTimeout(() => notification.remove(), 300);
    }, 5000);
}

let confirmCallback = null;

function showConfirmModal(message, callback) {
    const modal = document.getElementById('confirmModal');
    const messageEl = document.getElementById('confirmMessage');
    if (modal && messageEl) {
        messageEl.textContent = message;
        confirmCallback = callback;
        
        // Calculate center position considering sidebar on desktop
        const isDesktop = window.innerWidth >= 1024; // lg breakpoint
        const sidebarWidth = isDesktop ? 256 : 0; // 64 * 4 = 256px (lg:w-64)
        
        // Get viewport dimensions
        const viewportWidth = window.innerWidth;
        
        // Calculate available width (viewport minus sidebar)
        const availableWidth = viewportWidth - sidebarWidth;
        
        // Center modal in available content area (ignoring sidebar)
        const modalContainer = document.getElementById('confirmModalContainer');
        if (modalContainer) {
            modalContainer.style.left = sidebarWidth + 'px';
            modalContainer.style.width = availableWidth + 'px';
        }
        
        modal.classList.remove('hidden');
        modal.style.display = 'block';
        
        // Disable body scroll
        document.body.style.overflow = 'hidden';
    }
}

function closeConfirmModal() {
    const modal = document.getElementById('confirmModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.style.display = 'none';
        confirmCallback = null;
        
        // Enable body scroll
        document.body.style.overflow = '';
    }
}

function executeConfirmAction() {
    if (confirmCallback) {
        confirmCallback();
    }
    closeConfirmModal();
}

function closeModal() {
    closeEditUserModal();
}

function closePasswordModal() {
    const modal = document.getElementById('changePasswordModal');
    if (!modal) {
        return;
    }
    
    modal.classList.add('hidden');
    modal.style.display = 'none';
    
    // Clear form
    const newPassword = document.getElementById('newPassword');
    const confirmPassword = document.getElementById('confirmPassword');
    const showPassword = document.getElementById('showPassword');
    
    if (newPassword) newPassword.value = '';
    if (confirmPassword) confirmPassword.value = '';
    if (showPassword) showPassword.checked = false;
    
    // Enable body scroll
    document.body.style.overflow = '';
}

// Show/Hide Password functionality
document.addEventListener('DOMContentLoaded', function() {
    const showPasswordCheckbox = document.getElementById('showPassword');
    const newPasswordInput = document.getElementById('newPassword');
    const confirmPasswordInput = document.getElementById('confirmPassword');

    if (showPasswordCheckbox) {
        showPasswordCheckbox.addEventListener('change', function() {
            const type = this.checked ? 'text' : 'password';
            if (newPasswordInput) newPasswordInput.type = type;
            if (confirmPasswordInput) confirmPasswordInput.type = type;
        });
    }

    // Password confirmation validation
    if (confirmPasswordInput && newPasswordInput) {
        confirmPasswordInput.addEventListener('input', function() {
            const newPassword = newPasswordInput.value;
            const confirmPassword = this.value;

            if (newPassword !== confirmPassword) {
                this.setCustomValidity('Password does not match');
            } else {
                this.setCustomValidity('');
            }
        });
    }

    // Close modals on ESC key press
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            // Check which modal is open and close it
            const addUserModal = document.getElementById('addUserModal');
            const editUserModal = document.getElementById('editUserModal');
            const changePasswordModal = document.getElementById('changePasswordModal');

            if (addUserModal && !addUserModal.classList.contains('hidden')) {
                closeAddUserModal();
            } else if (editUserModal && !editUserModal.classList.contains('hidden')) {
                closeEditUserModal();
            } else if (changePasswordModal && !changePasswordModal.classList.contains('hidden')) {
                closePasswordModal();
            }
            
            const confirmModal = document.getElementById('confirmModal');
            if (confirmModal && !confirmModal.classList.contains('hidden')) {
                closeConfirmModal();
            }
        }
    });

    // Handle window resize to recalculate modal position
    let resizeTimeout;
    window.addEventListener('resize', function() {
        const addUserModal = document.getElementById('addUserModal');
        const editUserModal = document.getElementById('editUserModal');
        const changePasswordModal = document.getElementById('changePasswordModal');

        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(function() {
            // Recalculate modal position on resize
            const isDesktop = window.innerWidth >= 1024;
            const sidebarWidth = isDesktop ? 256 : 0;
            const viewportWidth = window.innerWidth;
            const availableWidth = viewportWidth - sidebarWidth;

            // Update all open modals
            if (addUserModal && !addUserModal.classList.contains('hidden')) {
                const modalContainer = document.getElementById('addUserModalContainer');
                if (modalContainer) {
                    modalContainer.style.left = sidebarWidth + 'px';
                    modalContainer.style.width = availableWidth + 'px';
                }
            }
            if (editUserModal && !editUserModal.classList.contains('hidden')) {
                const modalContainer = document.getElementById('editUserModalContainer');
                if (modalContainer) {
                    modalContainer.style.left = sidebarWidth + 'px';
                    modalContainer.style.width = availableWidth + 'px';
                }
            }
            if (changePasswordModal && !changePasswordModal.classList.contains('hidden')) {
                const modalContainer = document.getElementById('changePasswordModalContainer');
                if (modalContainer) {
                    modalContainer.style.left = sidebarWidth + 'px';
                    modalContainer.style.width = availableWidth + 'px';
                }
            }
        }, 100);
    });
});
</script>
@endsection
