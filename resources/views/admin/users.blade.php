@extends('layouts.app')

@section('title', 'Manajemen User - FSMS')

@section('content')
<div class="flex min-h-screen">
    @include('admin.partials.sidebar')

    <!-- Main Content -->
    <div class="flex-1 flex flex-col lg:ml-64 min-h-screen">
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
            <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Manajemen User</h1>
            <p class="text-sm lg:text-base text-gray-600 mt-2">Kelola semua pengguna dalam sistem FSMS</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6 card-hover">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <i class="fas fa-users text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Akun</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\User::count() }}</p>
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
                <h2 class="text-lg md:text-xl font-semibold text-gray-900">Daftar User</h2>
                <button onclick="openAddUserModal()" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition flex items-center text-sm md:text-base">
                    <i class="fas fa-user-plus mr-2"></i>
                    <span class="hidden sm:inline">Tambah User Baru</span>
                    <span class="sm:hidden">Tambah</span>
                </button>
            </div>

            <!-- Filter Row -->
            <div class="flex flex-col sm:flex-row gap-2">
                <select class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm flex-1 sm:flex-none sm:w-auto">
                    <option>Semua Role</option>
                    <option>Super Admin</option>
                    <option>Supplier</option>
                    <option>Customer</option>
                </select>
                <input type="text" placeholder="Cari user..." class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 text-sm flex-1 sm:flex-none sm:w-64">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50 hidden md:table-header-group">
                    <tr>
                        <th class="px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                        <th class="px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Email</th>
                        <th class="px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden xl:table-cell">Status</th>
                        <th class="px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Terdaftar</th>
                        <th class="px-3 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach(\App\Models\User::with('roles')->get() as $user)
                    <tr data-user-id="{{ $user->id }}" class="hover:bg-gray-50">
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
                            @if($user->email_verified_at)
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Belum Verifikasi</span>
                            @endif
                        </td>
                        <td class="px-3 lg:px-6 py-3 md:py-4 whitespace-nowrap text-sm text-gray-500 hidden lg:table-cell">{{ $user->created_at->format('d M Y') }}</td>
                        <td class="px-3 lg:px-6 py-3 md:py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-1 md:space-x-2">
                                <button class="text-green-600 hover:text-green-900 p-1 md:p-0" onclick="editUser({{ $user->id }})" title="Edit User">
                                    <i class="fas fa-edit text-sm md:text-base"></i>
                                </button>
                                <button class="text-green-600 hover:text-green-900 p-1 md:p-0" onclick="changePassword({{ $user->id }})" title="Ubah Password">
                                    <i class="fas fa-key text-sm md:text-base"></i>
                                </button>
                                <button class="text-green-600 hover:text-green-900 p-1 md:p-0" onclick="changeRole({{ $user->id }})" title="Ubah Role">
                                    <i class="fas fa-user-tag text-sm md:text-base"></i>
                                </button>
                                @if($user->id !== auth()->id())
                                <button class="text-red-600 hover:text-red-900 p-1 md:p-0" onclick="deleteUser({{ $user->id }})" title="Delete User">
                                    <i class="fas fa-trash text-sm md:text-base"></i>
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
                Menampilkan <span class="font-medium">1</span> sampai <span class="font-medium">{{ \App\Models\User::count() }}</span> dari <span class="font-medium">{{ \App\Models\User::count() }}</span> hasil
            </div>
            @if(\App\Models\User::count() > 0)
            <div class="flex space-x-2">
                <button class="px-2 sm:px-3 py-2 border border-gray-300 rounded-md text-xs sm:text-sm text-gray-700 hover:bg-gray-50 disabled:opacity-50" disabled>Sebelumnya</button>
                <button class="px-2 sm:px-3 py-2 bg-blue-600 text-white rounded-md text-xs sm:text-sm">1</button>
                <button class="px-2 sm:px-3 py-2 border border-gray-300 rounded-md text-xs sm:text-sm text-gray-700 hover:bg-gray-50 disabled:opacity-50" disabled>Selanjutnya</button>
            </div>
            @endif
        </div>

        </div>
        </div>
    </div>
</div>

<!-- Modal untuk Tambah User -->
<div id="addUserModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Tambah User Baru</h3>
                <form method="POST" action="{{ route('admin.users.store') }}">
                    @csrf
                    <div class="mb-4">
                        <label for="addName" class="block text-sm font-medium text-gray-700 mb-2">Nama</label>
                        <input type="text" id="addName" name="name" value="{{ old('name') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" required>
                        @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label for="addEmail" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" id="addEmail" name="email" value="{{ old('email') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" required>
                        @error('email') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label for="addPassword" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <input type="password" id="addPassword" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" required>
                        @error('password') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label for="addPasswordConfirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                        <input type="password" id="addPasswordConfirmation" name="password_confirmation" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" required>
                    </div>
                    <div class="mb-6">
                        <label for="addRole" class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                        <select id="addRole" name="role" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" required>
                            <option value="">Pilih Role</option>
                            <option value="super_admin" {{ old('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                            <option value="supplier" {{ old('role') == 'supplier' ? 'selected' : '' }}>Supplier</option>
                            <option value="customer" {{ old('role') == 'customer' ? 'selected' : '' }}>Customer</option>
                        </select>
                        @error('role') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeAddUserModal()" class="px-4 py-2 text-gray-700 bg-gray-200 rounded hover:bg-gray-300">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Tambah User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk Edit User -->
<div id="editUserModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Edit User</h3>
                <form>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama</label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option>Super Admin</option>
                            <option>Supplier</option>
                            <option>Customer</option>
                        </select>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeModal()" class="px-4 py-2 text-gray-700 bg-gray-200 rounded hover:bg-gray-300">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk Ubah Password -->
<div id="changePasswordModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Ubah Password</h3>
                <form>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                        <input type="password" id="newPassword" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Masukkan password baru">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                        <input type="password" id="confirmPassword" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Konfirmasi password baru">
                    </div>
                    <div class="mb-6">
                        <div class="flex items-center">
                            <input type="checkbox" id="showPassword" class="mr-2">
                            <label for="showPassword" class="text-sm text-gray-700">Tampilkan password</label>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closePasswordModal()" class="px-4 py-2 text-gray-700 bg-gray-200 rounded hover:bg-gray-300">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Ubah Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk Ubah Role -->
<div id="changeRoleModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Ubah Role</h3>
                <form>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">User</label>
                        <input type="text" id="userName" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100" readonly>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Role Baru</label>
                        <select id="newRole" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="super_admin">Super Admin</option>
                            <option value="supplier">Supplier</option>
                            <option value="customer">Customer</option>
                        </select>
                    </div>
                    <div class="mb-6">
                        <div class="bg-yellow-50 border border-yellow-200 rounded-md p-3">
                            <div class="flex">
                                <i class="fas fa-exclamation-triangle text-yellow-400 mr-2 mt-0.5"></i>
                                <div class="text-sm text-yellow-800">
                                    <strong>Peringatan:</strong> Mengubah role akan mempengaruhi akses user ke sistem. Pastikan role yang dipilih sesuai dengan kebutuhan.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeRoleModal()" class="px-4 py-2 text-gray-700 bg-gray-200 rounded hover:bg-gray-300">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Ubah Role</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript Functions -->
<script>
function openAddUserModal() {
    document.getElementById('addUserModal').classList.remove('hidden');
}

function closeAddUserModal() {
    document.getElementById('addUserModal').classList.add('hidden');
    // Clear form
    document.getElementById('addUserModal').querySelector('form').reset();
}

function editUser(userId) {
    document.getElementById('editUserModal').classList.remove('hidden');
    // Implement edit user logic
}

function changePassword(userId) {
    document.getElementById('changePasswordModal').classList.remove('hidden');
    // Implement change password logic
}

function changeRole(userId) {
    // Get user data and populate modal
    const userName = document.querySelector(`tr[data-user-id="${userId}"] td:nth-child(2)`).textContent;
    document.getElementById('userName').value = userName;
    document.getElementById('changeRoleModal').classList.remove('hidden');
}

function deleteUser(userId) {
    if (confirm('Apakah Anda yakin ingin menghapus user ini? Tindakan ini tidak dapat dibatalkan.')) {
        // Implement delete user logic
        alert('User berhasil dihapus!');
    }
}

function closeModal() {
    document.getElementById('editUserModal').classList.add('hidden');
}

function closePasswordModal() {
    document.getElementById('changePasswordModal').classList.add('hidden');
    // Clear form
    document.getElementById('newPassword').value = '';
    document.getElementById('confirmPassword').value = '';
    document.getElementById('showPassword').checked = false;
}

function closeRoleModal() {
    document.getElementById('changeRoleModal').classList.add('hidden');
}

// Show/Hide Password functionality
document.addEventListener('DOMContentLoaded', function() {
    const showPasswordCheckbox = document.getElementById('showPassword');
    const newPasswordInput = document.getElementById('newPassword');
    const confirmPasswordInput = document.getElementById('confirmPassword');

    if (showPasswordCheckbox) {
        showPasswordCheckbox.addEventListener('change', function() {
            const type = this.checked ? 'text' : 'password';
            newPasswordInput.type = type;
            confirmPasswordInput.type = type;
        });
    }

    // Password confirmation validation
    if (confirmPasswordInput) {
        confirmPasswordInput.addEventListener('input', function() {
            const newPassword = newPasswordInput.value;
            const confirmPassword = this.value;

            if (newPassword !== confirmPassword) {
                this.setCustomValidity('Password tidak cocok');
            } else {
                this.setCustomValidity('');
            }
        });
    }
});
</script>
@endsection
