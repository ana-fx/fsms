<?php

namespace Database\Seeders;

use App\Models\FoodCategory;
use Illuminate\Database\Seeder;

class FoodCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Karbohidrat',
                'slug' => 'karbohidrat',
                'description' => 'Bahan makanan sumber karbohidrat seperti beras, gandum, jagung, kentang, dll',
                'icon' => 'fas fa-bread-slice',
                'color' => '#F59E0B', // Amber
                'sort_order' => 1,
            ],
            [
                'name' => 'Protein',
                'slug' => 'protein',
                'description' => 'Bahan makanan sumber protein seperti daging, ikan, telur, tahu, tempe, dll',
                'icon' => 'fas fa-drumstick-bite',
                'color' => '#EF4444', // Red
                'sort_order' => 2,
            ],
            [
                'name' => 'Sayuran',
                'slug' => 'sayuran',
                'description' => 'Berbagai jenis sayuran segar seperti bayam, kangkung, wortel, tomat, dll',
                'icon' => 'fas fa-leaf',
                'color' => '#10B981', // Green
                'sort_order' => 3,
            ],
            [
                'name' => 'Buah-buahan',
                'slug' => 'buah-buahan',
                'description' => 'Berbagai jenis buah-buahan segar seperti pisang, apel, jeruk, mangga, dll',
                'icon' => 'fas fa-apple-alt',
                'color' => '#8B5CF6', // Purple
                'sort_order' => 4,
            ],
            [
                'name' => 'Bumbu & Rempah',
                'slug' => 'bumbu-rempah',
                'description' => 'Bumbu dapur dan rempah-rempah seperti garam, gula, merica, bawang, dll',
                'icon' => 'fas fa-pepper-hot',
                'color' => '#F97316', // Orange
                'sort_order' => 5,
            ],
        ];

        foreach ($categories as $category) {
            FoodCategory::create($category);
        }
    }
}
