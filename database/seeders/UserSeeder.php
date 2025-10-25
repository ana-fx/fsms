<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing users to avoid duplicates
        User::whereIn('email', ['admin@fsms.com', 'supplier@fsms.com', 'foundation@fsms.com'])->delete();

        // Create Super Admin
        $admin = User::updateOrCreate(
            ['email' => 'admin@fsms.com'],
            [
                'name' => 'Super Admin FSMS',
                'email' => 'admin@fsms.com',
                'password' => Hash::make('admin@fsms.com'),
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole('super_admin');

        // Create Supplier
        $supplier = User::updateOrCreate(
            ['email' => 'supplier@fsms.com'],
            [
                'name' => 'Supplier FSMS',
                'email' => 'supplier@fsms.com',
                'password' => Hash::make('supplier@fsms.com'),
                'email_verified_at' => now(),
            ]
        );
        $supplier->assignRole('supplier');

        // Create Foundation
        $foundation = User::updateOrCreate(
            ['email' => 'foundation@fsms.com'],
            [
                'name' => 'Foundation FSMS',
                'email' => 'foundation@fsms.com',
                'password' => Hash::make('foundation@fsms.com'),
                'email_verified_at' => now(),
            ]
        );
        $foundation->assignRole('foundation');

        $this->command->info('Users created successfully:');
        $this->command->info('- Super Admin: admin@fsms.com');
        $this->command->info('- Supplier: supplier@fsms.com');
        $this->command->info('- Foundation: foundation@fsms.com');
        $this->command->info('Password for all users is the same as their email address.');
    }
}
