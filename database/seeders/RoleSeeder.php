<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'super_admin',
                'display_name' => 'Super Admin',
                'description' => 'Administrator dengan akses penuh ke semua fitur sistem',
            ],
            [
                'name' => 'supplier',
                'display_name' => 'Supplier',
                'description' => 'Penyedia barang atau jasa yang dapat mengelola produk dan pesanan',
            ],
            [
                'name' => 'customer',
                'display_name' => 'Customer',
                'description' => 'Pelanggan yang dapat membuat permintaan bahan makanan',
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['name' => $role['name']],
                $role
            );
        }
    }
}
