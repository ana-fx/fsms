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
        // Create parent categories
        $parentCategories = [
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

        $createdCategories = [];
        foreach ($parentCategories as $category) {
            $createdCategories[$category['slug']] = FoodCategory::firstOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }

        // Create sub-categories for Karbohidrat
        $carbSubCategories = [
            [
                'name' => 'Beras',
                'slug' => 'beras',
                'description' => 'Berbagai jenis beras seperti beras premium, beras medium, beras standard',
                'icon' => 'fas fa-rice',
                'color' => '#F59E0B',
                'max_price' => 15000, // Maximum price for all beras ingredients
                'parent_id' => $createdCategories['karbohidrat']->id,
                'sort_order' => 1,
            ],
            [
                'name' => 'Mie',
                'slug' => 'mie',
                'description' => 'Berbagai jenis mie seperti mie instan, mie kering, mie basah',
                'icon' => 'fas fa-utensils',
                'color' => '#F59E0B',
                'max_price' => 15000, // Maximum price for all mie ingredients
                'parent_id' => $createdCategories['karbohidrat']->id,
                'sort_order' => 2,
            ],
            [
                'name' => 'Jagung',
                'slug' => 'jagung',
                'description' => 'Jagung pipil dan jagung segar',
                'icon' => 'fas fa-seedling',
                'color' => '#F59E0B',
                'max_price' => 15000, // Maximum price for all jagung ingredients
                'parent_id' => $createdCategories['karbohidrat']->id,
                'sort_order' => 3,
            ],
            [
                'name' => 'Kentang',
                'slug' => 'kentang',
                'description' => 'Kentang segar untuk konsumsi',
                'icon' => 'fas fa-drumstick-bite',
                'color' => '#F59E0B',
                'max_price' => 18000, // Maximum price for all kentang ingredients
                'parent_id' => $createdCategories['karbohidrat']->id,
                'sort_order' => 4,
            ],
            [
                'name' => 'Tepung',
                'slug' => 'tepung',
                'description' => 'Berbagai jenis tepung seperti tepung terigu, tepung beras',
                'icon' => 'fas fa-wheat-awn',
                'color' => '#F59E0B',
                'max_price' => 17000, // Maximum price for all tepung ingredients
                'parent_id' => $createdCategories['karbohidrat']->id,
                'sort_order' => 5,
            ],
        ];

        foreach ($carbSubCategories as $subCategory) {
            FoodCategory::firstOrCreate(
                ['slug' => $subCategory['slug']],
                $subCategory
            );
        }

        // Create sub-categories for Protein
        $proteinSubCategories = [
            [
                'name' => 'Daging',
                'slug' => 'daging',
                'description' => 'Berbagai jenis daging seperti ayam, sapi, kambing',
                'icon' => 'fas fa-drumstick-bite',
                'color' => '#EF4444',
                'max_price' => 150000, // Maximum price for all daging ingredients
                'parent_id' => $createdCategories['protein']->id,
                'sort_order' => 1,
            ],
            [
                'name' => 'Ikan',
                'slug' => 'ikan',
                'description' => 'Berbagai jenis ikan segar',
                'icon' => 'fas fa-fish',
                'color' => '#EF4444',
                'max_price' => 40000, // Maximum price for all ikan ingredients
                'parent_id' => $createdCategories['protein']->id,
                'sort_order' => 2,
            ],
            [
                'name' => 'Telur',
                'slug' => 'telur',
                'description' => 'Berbagai jenis telur seperti telur ayam, telur bebek',
                'icon' => 'fas fa-egg',
                'color' => '#EF4444',
                'max_price' => 40000, // Maximum price for all telur ingredients
                'parent_id' => $createdCategories['protein']->id,
                'sort_order' => 3,
            ],
            [
                'name' => 'Tahu & Tempe',
                'slug' => 'tahu-tempe',
                'description' => 'Tahu dan tempe segar',
                'icon' => 'fas fa-cube',
                'color' => '#EF4444',
                'max_price' => 17000, // Maximum price for all tahu-tempe ingredients
                'parent_id' => $createdCategories['protein']->id,
                'sort_order' => 4,
            ],
            [
                'name' => 'Udang & Seafood',
                'slug' => 'udang-seafood',
                'description' => 'Udang dan seafood lainnya',
                'icon' => 'fas fa-shrimp',
                'color' => '#EF4444',
                'max_price' => 95000, // Maximum price for all udang-seafood ingredients
                'parent_id' => $createdCategories['protein']->id,
                'sort_order' => 5,
            ],
        ];

        foreach ($proteinSubCategories as $subCategory) {
            FoodCategory::firstOrCreate(
                ['slug' => $subCategory['slug']],
                $subCategory
            );
        }

        // Create sub-categories for Sayuran
        $veggieSubCategories = [
            [
                'name' => 'Sayuran Daun',
                'slug' => 'sayuran-daun',
                'description' => 'Sayuran berdaun seperti bayam, kangkung, sawi',
                'icon' => 'fas fa-leaf',
                'color' => '#10B981',
                'max_price' => 10000, // Maximum price for all sayuran daun ingredients
                'parent_id' => $createdCategories['sayuran']->id,
                'sort_order' => 1,
            ],
            [
                'name' => 'Sayuran Umbi',
                'slug' => 'sayuran-umbi',
                'description' => 'Sayuran umbi seperti wortel, kentang, singkong',
                'icon' => 'fas fa-carrot',
                'color' => '#10B981',
                'max_price' => 22000, // Maximum price for all sayuran umbi ingredients
                'parent_id' => $createdCategories['sayuran']->id,
                'sort_order' => 2,
            ],
            [
                'name' => 'Sayuran Buah',
                'slug' => 'sayuran-buah',
                'description' => 'Sayuran buah seperti tomat, terong, cabai',
                'icon' => 'fas fa-pepper-hot',
                'color' => '#10B981',
                'max_price' => 55000, // Maximum price for all sayuran buah ingredients
                'parent_id' => $createdCategories['sayuran']->id,
                'sort_order' => 3,
            ],
            [
                'name' => 'Sayuran Kacang',
                'slug' => 'sayuran-kacang',
                'description' => 'Sayuran kacang seperti kacang panjang, buncis',
                'icon' => 'fas fa-seedling',
                'color' => '#10B981',
                'max_price' => 20000, // Maximum price for all sayuran kacang ingredients
                'parent_id' => $createdCategories['sayuran']->id,
                'sort_order' => 4,
            ],
        ];

        foreach ($veggieSubCategories as $subCategory) {
            FoodCategory::firstOrCreate(
                ['slug' => $subCategory['slug']],
                $subCategory
            );
        }

        // Create sub-categories for Buah-buahan
        $fruitSubCategories = [
            [
                'name' => 'Buah Tropis',
                'slug' => 'buah-tropis',
                'description' => 'Buah-buahan tropis seperti pisang, mangga, pepaya',
                'icon' => 'fas fa-apple-alt',
                'color' => '#8B5CF6',
                'max_price' => 30000, // Maximum price for all buah tropis ingredients
                'parent_id' => $createdCategories['buah-buahan']->id,
                'sort_order' => 1,
            ],
            [
                'name' => 'Buah Import',
                'slug' => 'buah-import',
                'description' => 'Buah-buahan import seperti apel, jeruk, anggur',
                'icon' => 'fas fa-apple-alt',
                'color' => '#8B5CF6',
                'max_price' => 42000, // Maximum price for all buah import ingredients
                'parent_id' => $createdCategories['buah-buahan']->id,
                'sort_order' => 2,
            ],
        ];

        foreach ($fruitSubCategories as $subCategory) {
            FoodCategory::firstOrCreate(
                ['slug' => $subCategory['slug']],
                $subCategory
            );
        }

        // Create sub-categories for Bumbu & Rempah
        $spiceSubCategories = [
            [
                'name' => 'Bumbu Dasar',
                'slug' => 'bumbu-dasar',
                'description' => 'Bumbu dasar seperti garam, gula, merica',
                'icon' => 'fas fa-pepper-hot',
                'color' => '#F97316',
                'max_price' => 18000, // Maximum price for all bumbu dasar ingredients
                'parent_id' => $createdCategories['bumbu-rempah']->id,
                'sort_order' => 1,
            ],
            [
                'name' => 'Bawang',
                'slug' => 'bawang',
                'description' => 'Berbagai jenis bawang seperti bawang merah, bawang putih',
                'icon' => 'fas fa-onion',
                'color' => '#F97316',
                'max_price' => 42000, // Maximum price for all bawang ingredients
                'parent_id' => $createdCategories['bumbu-rempah']->id,
                'sort_order' => 2,
            ],
            [
                'name' => 'Rempah-rempah',
                'slug' => 'rempah-rempah',
                'description' => 'Rempah-rempah seperti kunyit, jahe, lengkuas',
                'icon' => 'fas fa-spa',
                'color' => '#F97316',
                'max_price' => 27000, // Maximum price for all rempah-rempah ingredients
                'parent_id' => $createdCategories['bumbu-rempah']->id,
                'sort_order' => 3,
            ],
            [
                'name' => 'Minyak & Lemak',
                'slug' => 'minyak-lemak',
                'description' => 'Minyak goreng dan lemak',
                'icon' => 'fas fa-oil-can',
                'color' => '#F97316',
                'max_price' => 30000, // Maximum price for all minyak-lemak ingredients
                'parent_id' => $createdCategories['bumbu-rempah']->id,
                'sort_order' => 4,
            ],
        ];

        foreach ($spiceSubCategories as $subCategory) {
            FoodCategory::firstOrCreate(
                ['slug' => $subCategory['slug']],
                $subCategory
            );
        }
    }
}
