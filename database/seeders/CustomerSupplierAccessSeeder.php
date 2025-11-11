<?php

namespace Database\Seeders;

use App\Models\CustomerSupplierAccess;
use App\Models\User;
use Illuminate\Database\Seeder;

class CustomerSupplierAccessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CustomerSupplierAccess::query()->delete();

        $customers = User::whereHas('roles', function ($query) {
            $query->where('name', 'customer');
        })->get();

        $suppliers = User::whereHas('roles', function ($query) {
            $query->where('name', 'supplier');
        })->get();

        if ($customers->isEmpty() || $suppliers->isEmpty()) {
            return;
        }

        $assignments = [
            'customer@fsms.com' => ['supplier1@fsms.com', 'supplier2@fsms.com'],
            'customer1@fsms.com' => ['supplier@fsms.com', 'supplier1@fsms.com'],
            'customer2@fsms.com' => ['supplier2@fsms.com'],
        ];

        foreach ($assignments as $customerEmail => $supplierEmails) {
            $customer = $customers->firstWhere('email', $customerEmail);
            if (!$customer) {
                continue;
            }

            foreach ($supplierEmails as $supplierEmail) {
                $supplier = $suppliers->firstWhere('email', $supplierEmail);
                if (!$supplier) {
                    continue;
                }

                CustomerSupplierAccess::updateOrCreate(
                    [
                        'customer_id' => $customer->id,
                        'supplier_id' => $supplier->id,
                    ],
                    [
                        'created_by' => null,
                    ]
                );
            }
        }
    }
}

