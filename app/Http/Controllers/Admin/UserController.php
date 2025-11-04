<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as IlluminateAuth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index()
    {
        $users = User::with('roles')->paginate(10);

        return view('admin.users', compact('users'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => ['required', 'string', 'in:super_admin,supplier,customer'],
        ]);

        // Create the user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'email_verified_at' => now(),
        ]);

        // Assign role
        $role = Role::where('name', $validated['role'])->first();
        if ($role) {
            $user->roles()->attach($role->id);
        }

        return redirect()->route('admin.users')
            ->with('success', 'User berhasil ditambahkan!');
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['required', 'string', 'max:20'],
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
        ]);

        return redirect()->route('admin.users')
            ->with('success', 'User berhasil diupdate!');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy($id)
    {
        // Prevent deleting yourself
        if ($id == IlluminateAuth::id()) {
            return redirect()->route('admin.users')
                ->with('error', 'Anda tidak dapat menghapus akun sendiri!');
        }

        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users')
            ->with('success', 'User berhasil dihapus!');
    }

    /**
     * Change user password.
     */
    public function changePassword(Request $request, $id)
    {
        $validated = $request->validate([
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('admin.users')
            ->with('success', 'Password berhasil diubah!');
    }

    /**
     * Change user role.
     */
    public function changeRole(Request $request, $id)
    {
        $validated = $request->validate([
            'role' => ['required', 'string', 'in:super_admin,supplier,customer'],
        ]);

        $user = User::findOrFail($id);

        // Remove all existing roles
        $user->roles()->detach();

        // Assign new role
        $role = Role::where('name', $validated['role'])->first();
        if ($role) {
            $user->roles()->attach($role->id);
        }

        return redirect()->route('admin.users')
            ->with('success', 'Role berhasil diubah!');
    }
}

