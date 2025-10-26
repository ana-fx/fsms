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
            'customer@fsms.com',
            'customer1@fsms.com',
            'customer2@fsms.com',
            'customer3@fsms.com'
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

        // Create Customer
        $customer = User::updateOrCreate(
            ['email' => 'customer@fsms.com'],
            [
                'name' => 'Customer FSMS',
                'email' => 'customer@fsms.com',
                'password' => Hash::make('customer@fsms.com'),
                'email_verified_at' => now(),
            ]
        );
        $customer->assignRole('customer');

        // Create Additional Customers
        $customers = [
            [
                'name' => 'Customer Peduli Anak',
                'email' => 'customer1@fsms.com',
            ],
            [
                'name' => 'Customer Bantu Lansia',
                'email' => 'customer2@fsms.com',
            ],
            [
                'name' => 'Customer Pemberdayaan Masyarakat',
                'email' => 'customer3@fsms.com',
            ],
        ];

        foreach ($customers as $customerData) {
            $newCustomer = User::updateOrCreate(
                ['email' => $customerData['email']],
                [
                    'name' => $customerData['name'],
                    'email' => $customerData['email'],
                    'password' => Hash::make($customerData['email']),
                    'email_verified_at' => now(),
                ]
            );
            $newCustomer->assignRole('customer');
        }

        $this->command->info('Users created successfully:');
        $this->command->info('- Super Admin: admin@fsms.com');
        $this->command->info('- Supplier: supplier@fsms.com');
        $this->command->info('- PT. Supplier Bahan Pangan: supplier1@fsms.com');
        $this->command->info('- CV. Jaya Makmur Food: supplier2@fsms.com');
        $this->command->info('- UD. Sejahtera Bersama: supplier3@fsms.com');
        $this->command->info('- Customer: customer@fsms.com');
        $this->command->info('- Customer Peduli Anak: customer1@fsms.com');
        $this->command->info('- Customer Bantu Lansia: customer2@fsms.com');
        $this->command->info('- Customer Pemberdayaan Masyarakat: customer3@fsms.com');
        $this->command->info('Password for all users is the same as their email address.');
    }
}
