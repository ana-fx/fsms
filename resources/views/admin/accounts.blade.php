@extends('layouts.app')

@section('title', 'Manajemen Akun - FSMS')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Manajemen Akun</h1>
        <p class="text-gray-600 mt-2">Kelola semua akun pengguna dalam sistem FSMS</p>
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
                    <p class="text-sm font-medium text-gray-600">Foundation</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\User::whereHas('roles', function($q) { $q->where('name', 'foundation'); })->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Aksi Cepat</h2>
        <div class="flex flex-wrap gap-4">
            <button class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition flex items-center">
                <i class="fas fa-user-plus mr-2"></i>
                Tambah Akun Baru
            </button>
            <button class="bg-green-700 text-white px-6 py-3 rounded-lg hover:bg-green-800 transition flex items-center">
                <i class="fas fa-download mr-2"></i>
                Export Data Akun
            </button>
            <button class="bg-green-800 text-white px-6 py-3 rounded-lg hover:bg-green-900 transition flex items-center">
                <i class="fas fa-key mr-2"></i>
                Reset Password Massal
            </button>
            <button class="bg-green-900 text-white px-6 py-3 rounded-lg hover:bg-green-950 transition flex items-center">
                <i class="fas fa-chart-bar mr-2"></i>
                Laporan Aktivitas
            </button>
        </div>
    </div>

    <!-- Account Management Table -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-900">Daftar Akun</h2>
            <div class="flex space-x-2">
                <select class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option>Semua Role</option>
                    <option>Super Admin</option>
                    <option>Supplier</option>
                    <option>Foundation</option>
                </select>
                <input type="text" placeholder="Cari akun..." class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Terdaftar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach(\App\Models\User::with('roles')->get() as $user)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $user->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->roles->count() > 0)
                                <span class="px-2 py-1 text-xs font-semibold rounded-full
                                    @if($user->roles->first()->name === 'super_admin') bg-blue-100 text-blue-800
                                    @elseif($user->roles->first()->name === 'supplier') bg-green-100 text-green-800
                                    @elseif($user->roles->first()->name === 'foundation') bg-yellow-100 text-yellow-800
                                    @endif">
                                    {{ $user->roles->first()->display_name }}
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">No Role</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->email_verified_at)
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Belum Verifikasi</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <button class="text-green-600 hover:text-green-900 text-sm" onclick="editUser({{ $user->id }})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="text-green-600 hover:text-green-900 text-sm" onclick="resetPassword({{ $user->id }})">
                                    <i class="fas fa-key"></i>
                                </button>
                                <button class="text-green-600 hover:text-green-900 text-sm" onclick="changeRole({{ $user->id }})">
                                    <i class="fas fa-user-tag"></i>
                                </button>
                                @if($user->id !== auth()->id())
                                <button class="text-red-600 hover:text-red-900 text-sm" onclick="deleteUser({{ $user->id }})">
                                    <i class="fas fa-trash"></i>
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
        <div class="mt-6 flex items-center justify-between">
            <div class="text-sm text-gray-700">
                Menampilkan <span class="font-medium">1</span> sampai <span class="font-medium">{{ \App\Models\User::count() }}</span> dari <span class="font-medium">{{ \App\Models\User::count() }}</span> hasil
            </div>
            <div class="flex space-x-2">
                <button class="px-3 py-2 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50">Sebelumnya</button>
                <button class="px-3 py-2 bg-blue-600 text-white rounded-md text-sm">1</button>
                <button class="px-3 py-2 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50">Selanjutnya</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk Edit User -->
<div id="editUserModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Edit Akun</h3>
                <form>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama</label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option>Super Admin</option>
                            <option>Supplier</option>
                            <option>Foundation</option>
                        </select>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50" onclick="closeModal()">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
function editUser(userId) {
    document.getElementById('editUserModal').classList.remove('hidden');
    // Implement edit user logic
}

function resetPassword(userId) {
    if (confirm('Apakah Anda yakin ingin mereset password untuk akun ini?')) {
        // Implement reset password logic
        alert('Password berhasil direset!');
    }
}

function changeRole(userId) {
    if (confirm('Apakah Anda yakin ingin mengubah role akun ini?')) {
        // Implement change role logic
        alert('Role berhasil diubah!');
    }
}

function deleteUser(userId) {
    if (confirm('Apakah Anda yakin ingin menghapus akun ini? Tindakan ini tidak dapat dibatalkan.')) {
        // Implement delete user logic
        alert('Akun berhasil dihapus!');
    }
}

function closeModal() {
    document.getElementById('editUserModal').classList.add('hidden');
}
</script>
@endsection
