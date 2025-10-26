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
        User::whereIn('email', [
            'admin@fsms.com',
            'supplier@fsms.com',
            'supplier1@fsms.com',
            'supplier2@fsms.com',
            'supplier3@fsms.com',
            'foundation@fsms.com',
            'yayasan1@fsms.com',
            'yayasan2@fsms.com',
            'yayasan3@fsms.com'
        ])->delete();

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

        // Create Additional Suppliers
        $suppliers = [
            [
                'name' => 'PT. Supplier Bahan Pangan',
                'email' => 'supplier1@fsms.com',
            ],
            [
                'name' => 'CV. Jaya Makmur Food',
                'email' => 'supplier2@fsms.com',
            ],
            [
                'name' => 'UD. Sejahtera Bersama',
                'email' => 'supplier3@fsms.com',
            ],
        ];

        foreach ($suppliers as $supplierData) {
            $newSupplier = User::updateOrCreate(
                ['email' => $supplierData['email']],
                [
                    'name' => $supplierData['name'],
                    'email' => $supplierData['email'],
                    'password' => Hash::make($supplierData['email']),
                    'email_verified_at' => now(),
                ]
            );
            $newSupplier->assignRole('supplier');
        }

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

        // Create Additional Foundations
        $foundations = [
            [
                'name' => 'Yayasan Peduli Anak',
                'email' => 'yayasan1@fsms.com',
            ],
            [
                'name' => 'Yayasan Bantu Lansia',
                'email' => 'yayasan2@fsms.com',
            ],
            [
                'name' => 'Yayasan Pemberdayaan Masyarakat',
                'email' => 'yayasan3@fsms.com',
            ],
        ];

        foreach ($foundations as $foundationData) {
            $newFoundation = User::updateOrCreate(
                ['email' => $foundationData['email']],
                [
                    'name' => $foundationData['name'],
                    'email' => $foundationData['email'],
                    'password' => Hash::make($foundationData['email']),
                    'email_verified_at' => now(),
                ]
            );
            $newFoundation->assignRole('foundation');
        }

        $this->command->info('Users created successfully:');
        $this->command->info('- Super Admin: admin@fsms.com');
        $this->command->info('- Supplier: supplier@fsms.com');
        $this->command->info('- PT. Supplier Bahan Pangan: supplier1@fsms.com');
        $this->command->info('- CV. Jaya Makmur Food: supplier2@fsms.com');
        $this->command->info('- UD. Sejahtera Bersama: supplier3@fsms.com');
        $this->command->info('- Foundation: foundation@fsms.com');
        $this->command->info('- Yayasan Peduli Anak: yayasan1@fsms.com');
        $this->command->info('- Yayasan Bantu Lansia: yayasan2@fsms.com');
        $this->command->info('- Yayasan Pemberdayaan Masyarakat: yayasan3@fsms.com');
        $this->command->info('Password for all users is the same as their email address.');
    }
}
