<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\UserDeliveryAddress;
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

        // Add sample delivery addresses for customer
        $customer->deliveryAddresses()->updateOrCreate(
            ['label' => 'Home'],
            [
                'recipient_name' => 'Customer FSMS',
                'recipient_phone' => '081234567890',
                'delivery_address' => 'Jl. Sudirman No. 123, Gedung Plaza Indonesia',
                'city' => 'Jakarta Pusat',
                'postal_code' => '10220',
                'is_default' => true,
            ]
        );
        $customer->deliveryAddresses()->updateOrCreate(
            ['label' => 'Office'],
            [
                'recipient_name' => 'Customer FSMS',
                'recipient_phone' => '081234567891',
                'delivery_address' => 'Jl. Thamrin No. 45, Lantai 10',
                'city' => 'Jakarta Pusat',
                'postal_code' => '10230',
                'is_default' => false,
            ]
        );

        // Create Additional Customers
        $customers = [
            [
                'name' => 'Customer Peduli Anak',
                'email' => 'customer1@fsms.com',
                'addresses' => [
                    [
                        'label' => 'Head Office',
                        'recipient_name' => 'Customer Peduli Anak',
                        'recipient_phone' => '081234567892',
                        'delivery_address' => 'Jl. Kebon Jeruk No. 88, Ruko Harmoni',
                        'city' => 'Jakarta Barat',
                        'postal_code' => '11530',
                        'is_default' => true,
                    ],
                    [
                        'label' => 'Branch Office',
                        'recipient_name' => 'Customer Peduli Anak',
                        'recipient_phone' => '081234567893',
                        'delivery_address' => 'Jl. Gatot Subroto No. 200, Menara BCA',
                        'city' => 'Jakarta Selatan',
                        'postal_code' => '12190',
                        'is_default' => false,
                    ],
                ],
            ],
            [
                'name' => 'Customer Bantu Lansia',
                'email' => 'customer2@fsms.com',
                'addresses' => [
                    [
                        'label' => 'Main Office',
                        'recipient_name' => 'Customer Bantu Lansia',
                        'recipient_phone' => '081234567894',
                        'delivery_address' => 'Jl. Senopati No. 15, Kebayoran Baru',
                        'city' => 'Jakarta Selatan',
                        'postal_code' => '12190',
                        'is_default' => true,
                    ],
                ],
            ],
            [
                'name' => 'Customer Pemberdayaan Masyarakat',
                'email' => 'customer3@fsms.com',
                'addresses' => [
                    [
                        'label' => 'Central Office',
                        'recipient_name' => 'Customer Pemberdayaan Masyarakat',
                        'recipient_phone' => '081234567895',
                        'delivery_address' => 'Jl. HR Rasuna Said No. 5, Kuningan',
                        'city' => 'Jakarta Selatan',
                        'postal_code' => '12940',
                        'is_default' => true,
                    ],
                    [
                        'label' => 'Field Office',
                        'recipient_name' => 'Customer Pemberdayaan Masyarakat',
                        'recipient_phone' => '081234567896',
                        'delivery_address' => 'Jl. Raya Bogor KM 30, Cimanggis',
                        'city' => 'Depok',
                        'postal_code' => '16452',
                        'is_default' => false,
                    ],
                ],
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

            // Add sample delivery addresses for additional customers
            if (isset($customerData['addresses'])) {
                foreach ($customerData['addresses'] as $address) {
                    $newCustomer->deliveryAddresses()->updateOrCreate(
                        ['label' => $address['label']],
                        [
                            'recipient_name' => $address['recipient_name'],
                            'recipient_phone' => $address['recipient_phone'],
                            'delivery_address' => $address['delivery_address'],
                            'city' => $address['city'],
                            'postal_code' => $address['postal_code'],
                            'is_default' => $address['is_default'],
                        ]
                    );
                }
            }
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
