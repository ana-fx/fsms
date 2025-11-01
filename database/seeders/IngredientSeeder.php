<?php

namespace Database\Seeders;

use App\Models\FoodItem;
use App\Models\FoodCategory;
use App\Models\User;
use Illuminate\Database\Seeder;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get categories
        $categories = FoodCategory::all();
        $carbCategory = $categories->where('slug', 'karbohidrat')->first();
        $proteinCategory = $categories->where('slug', 'protein')->first();
        $veggieCategory = $categories->where('slug', 'sayuran')->first();
        $fruitCategory = $categories->where('slug', 'buah-buahan')->first();
        $spiceCategory = $categories->where('slug', 'bumbu-rempah')->first();

        // Define products for specific suppliers by email
        $supplierProducts = [
            'supplier@fsms.com' => [
                [
                    'name' => 'Beras Premium',
                    'description' => 'Beras berkualitas tinggi untuk konsumsi sehari-hari',
                    'price' => 12500,
                    'unit' => 'kg',
                    'stock' => 500,
                    'min_purchase' => 50,
                    'category' => $carbCategory,
                ],
                [
                    'name' => 'Ayam Potong Segar',
                    'description' => 'Ayam potong segar tanpa kepala dan ceker',
                    'price' => 35000,
                    'unit' => 'kg',
                    'stock' => 200,
                    'min_purchase' => 30,
                    'category' => $proteinCategory,
                ],
                [
                    'name' => 'Sayuran Segar Mix',
                    'description' => 'Paket sayuran segar berisi kangkung, bayam, dan sawi',
                    'price' => 15000,
                    'unit' => 'ikat',
                    'stock' => 100,
                    'min_purchase' => 20,
                    'category' => $veggieCategory,
                ],
            ],
            'supplier1@fsms.com' => [
                [
                    'name' => 'Gula Pasir',
                    'description' => 'Gula pasir putih berkualitas baik',
                    'price' => 15000,
                    'unit' => 'kg',
                    'stock' => 300,
                    'min_purchase' => 50,
                    'category' => $spiceCategory,
                ],
                [
                    'name' => 'Ikan Segar',
                    'description' => 'Ikan tongkol segar siap masak',
                    'price' => 25000,
                    'unit' => 'kg',
                    'stock' => 150,
                    'min_purchase' => 25,
                    'category' => $proteinCategory,
                ],
                [
                    'name' => 'Telur Ayam',
                    'description' => 'Telur ayam segar grade A',
                    'price' => 28000,
                    'unit' => 'kg',
                    'stock' => 250,
                    'min_purchase' => 40,
                    'category' => $proteinCategory,
                ],
            ],
            'supplier2@fsms.com' => [
                [
                    'name' => 'Mie Instan',
                    'description' => 'Mie instan berbagai varian rasa',
                    'price' => 3500,
                    'unit' => 'bungkus',
                    'stock' => 1000,
                    'min_purchase' => 100,
                    'category' => $carbCategory,
                ],
                [
                    'name' => 'Minyak Goreng',
                    'description' => 'Minyak goreng kemasan premium',
                    'price' => 18000,
                    'unit' => 'liter',
                    'stock' => 400,
                    'min_purchase' => 60,
                    'category' => $spiceCategory,
                ],
                [
                    'name' => 'Bawang Merah',
                    'description' => 'Bawang merah lokal berkualitas',
                    'price' => 32000,
                    'unit' => 'kg',
                    'stock' => 180,
                    'min_purchase' => 30,
                    'category' => $spiceCategory,
                ],
            ],
            'supplier3@fsms.com' => [
                [
                    'name' => 'Pisang Raja',
                    'description' => 'Pisang Raja matang pohon',
                    'price' => 15000,
                    'unit' => 'sisir',
                    'stock' => 80,
                    'min_purchase' => 15,
                    'category' => $fruitCategory,
                ],
                [
                    'name' => 'Tahu Putih',
                    'description' => 'Tahu putih segar dari produsen lokal',
                    'price' => 12000,
                    'unit' => 'kg',
                    'stock' => 120,
                    'min_purchase' => 20,
                    'category' => $proteinCategory,
                ],
                [
                    'name' => 'Wortel Segar',
                    'description' => 'Wortel segar organik',
                    'price' => 18000,
                    'unit' => 'kg',
                    'stock' => 90,
                    'min_purchase' => 15,
                    'category' => $veggieCategory,
                ],
            ],
        ];

        // Create ingredients for each supplier
        foreach ($supplierProducts as $email => $products) {
            $supplier = User::where('email', $email)->first();

            if (!$supplier) {
                continue;
            }

            foreach ($products as $productData) {
                FoodItem::create([
                    'supplier_id' => $supplier->id,
                    'food_category_id' => $productData['category']->id,
                    'name' => $productData['name'],
                    'description' => $productData['description'],
                    'price' => $productData['price'],
                    'unit' => $productData['unit'],
                    'stock' => $productData['stock'],
                    'min_purchase' => $productData['min_purchase'],
                    'is_active' => true,
                ]);
            }
        }

        $this->command->info('Ingredients created successfully for all suppliers!');
    }
}

