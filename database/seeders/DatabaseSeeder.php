<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed roles first
        $this->call(RoleSeeder::class);

        // Seed food categories
        $this->call(FoodCategorySeeder::class);

        // Seed users
        $this->call(UserSeeder::class);

        // Seed ingredients for suppliers
        $this->call(IngredientSeeder::class);

        // Seed customer-supplier access assignments
        $this->call(CustomerSupplierAccessSeeder::class);

        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
