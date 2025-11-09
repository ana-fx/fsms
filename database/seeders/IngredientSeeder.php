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
        // Get categories and sub-categories
        $categories = FoodCategory::all();

        // Parent categories
        $carbCategory = $categories->where('slug', 'karbohidrat')->first();
        $proteinCategory = $categories->where('slug', 'protein')->first();
        $veggieCategory = $categories->where('slug', 'sayuran')->first();
        $fruitCategory = $categories->where('slug', 'buah-buahan')->first();
        $spiceCategory = $categories->where('slug', 'bumbu-rempah')->first();

        // Sub-categories - Karbohidrat
        $berasCategory = $categories->where('slug', 'beras')->first();
        $mieCategory = $categories->where('slug', 'mie')->first();
        $jagungCategory = $categories->where('slug', 'jagung')->first();
        $kentangCategory = $categories->where('slug', 'kentang')->first();
        $tepungCategory = $categories->where('slug', 'tepung')->first();

        // Sub-categories - Protein
        $dagingCategory = $categories->where('slug', 'daging')->first();
        $ikanCategory = $categories->where('slug', 'ikan')->first();
        $telurCategory = $categories->where('slug', 'telur')->first();
        $tahuTempeCategory = $categories->where('slug', 'tahu-tempe')->first();
        $udangSeafoodCategory = $categories->where('slug', 'udang-seafood')->first();

        // Sub-categories - Sayuran
        $sayuranDaunCategory = $categories->where('slug', 'sayuran-daun')->first();
        $sayuranUmbiCategory = $categories->where('slug', 'sayuran-umbi')->first();
        $sayuranBuahCategory = $categories->where('slug', 'sayuran-buah')->first();
        $sayuranKacangCategory = $categories->where('slug', 'sayuran-kacang')->first();

        // Sub-categories - Buah-buahan
        $buahTropisCategory = $categories->where('slug', 'buah-tropis')->first();
        $buahImportCategory = $categories->where('slug', 'buah-import')->first();

        // Sub-categories - Bumbu & Rempah
        $bumbuDasarCategory = $categories->where('slug', 'bumbu-dasar')->first();
        $bawangCategory = $categories->where('slug', 'bawang')->first();
        $rempahCategory = $categories->where('slug', 'rempah-rempah')->first();
        $minyakLemakCategory = $categories->where('slug', 'minyak-lemak')->first();

        // Define all available products (pool of products)
        $allProducts = [
            // Karbohidrat - Beras
            [
                'name' => 'Beras Premium',
                'description' => 'Beras berkualitas tinggi untuk konsumsi sehari-hari',
                'price' => 12500,
                'max_price' => 15000,
                'unit' => 'kg',
                'stock' => 500,
                'min_purchase' => 50,
                'max_purchase' => 500,
                'category' => $berasCategory ?? $carbCategory,
            ],
            [
                'name' => 'Beras Medium',
                'description' => 'Beras medium grade untuk konsumsi',
                'price' => 11000,
                'max_price' => 13000,
                'unit' => 'kg',
                'stock' => 400,
                'min_purchase' => 50,
                'max_purchase' => 400,
                'category' => $berasCategory ?? $carbCategory,
            ],
            [
                'name' => 'Beras Standard',
                'description' => 'Beras standard harga terjangkau',
                'price' => 9500,
                'max_price' => 12000,
                'unit' => 'kg',
                'stock' => 600,
                'min_purchase' => 50,
                'max_purchase' => 600,
                'category' => $berasCategory ?? $carbCategory,
            ],
            // Karbohidrat - Mie
            [
                'name' => 'Mie Instan',
                'description' => 'Mie instan berbagai varian rasa',
                'price' => 3500,
                'max_price' => 5000,
                'unit' => 'bungkus',
                'stock' => 1000,
                'min_purchase' => 100,
                'max_purchase' => 1000,
                'category' => $mieCategory ?? $carbCategory,
            ],
            [
                'name' => 'Mie Kering',
                'description' => 'Mie kering untuk masakan',
                'price' => 12000,
                'max_price' => 15000,
                'unit' => 'kg',
                'stock' => 300,
                'min_purchase' => 50,
                'max_purchase' => null,
                'category' => $mieCategory ?? $carbCategory,
            ],
            // Karbohidrat - Jagung
            [
                'name' => 'Jagung Pipil',
                'description' => 'Jagung pipil siap masak',
                'price' => 12000,
                'max_price' => 15000,
                'unit' => 'kg',
                'stock' => 200,
                'min_purchase' => 40,
                'max_purchase' => null,
                'category' => $jagungCategory ?? $carbCategory,
            ],
            // Karbohidrat - Kentang
            [
                'name' => 'Kentang Segar',
                'description' => 'Kentang segar untuk masakan',
                'price' => 15000,
                'max_price' => 18000,
                'unit' => 'kg',
                'stock' => 180,
                'min_purchase' => 30,
                'max_purchase' => null,
                'category' => $kentangCategory ?? $carbCategory,
            ],
            // Karbohidrat - Tepung
            [
                'name' => 'Tepung Terigu',
                'description' => 'Tepung terigu protein tinggi',
                'price' => 14000,
                'max_price' => 17000,
                'unit' => 'kg',
                'stock' => 250,
                'min_purchase' => 50,
                'max_purchase' => null,
                'category' => $tepungCategory ?? $carbCategory,
            ],
            [
                'name' => 'Tepung Beras',
                'description' => 'Tepung beras halus',
                'price' => 12000,
                'max_price' => 15000,
                'unit' => 'kg',
                'stock' => 200,
                'min_purchase' => 40,
                'max_purchase' => null,
                'category' => $tepungCategory ?? $carbCategory,
            ],
            // Protein - Daging
            [
                'name' => 'Ayam Potong Segar',
                'description' => 'Ayam potong segar tanpa kepala dan ceker',
                'price' => 35000,
                'max_price' => 40000,
                'unit' => 'kg',
                'stock' => 200,
                'min_purchase' => 30,
                'max_purchase' => null,
                'category' => $dagingCategory ?? $proteinCategory,
            ],
            [
                'name' => 'Daging Sapi',
                'description' => 'Daging sapi segar pilihan',
                'price' => 120000,
                'max_price' => 140000,
                'unit' => 'kg',
                'stock' => 100,
                'min_purchase' => 10,
                'max_purchase' => null,
                'category' => $dagingCategory ?? $proteinCategory,
            ],
            [
                'name' => 'Daging Kambing',
                'description' => 'Daging kambing segar',
                'price' => 130000,
                'max_price' => 150000,
                'unit' => 'kg',
                'stock' => 80,
                'min_purchase' => 10,
                'max_purchase' => null,
                'category' => $dagingCategory ?? $proteinCategory,
            ],
            // Protein - Ikan
            [
                'name' => 'Ikan Tongkol',
                'description' => 'Ikan tongkol segar siap masak',
                'price' => 25000,
                'max_price' => 30000,
                'unit' => 'kg',
                'stock' => 150,
                'min_purchase' => 25,
                'max_purchase' => null,
                'category' => $ikanCategory ?? $proteinCategory,
            ],
            [
                'name' => 'Ikan Tenggiri',
                'description' => 'Ikan tenggiri segar',
                'price' => 35000,
                'max_price' => 40000,
                'unit' => 'kg',
                'stock' => 100,
                'min_purchase' => 20,
                'max_purchase' => null,
                'category' => $ikanCategory ?? $proteinCategory,
            ],
            [
                'name' => 'Ikan Bandeng',
                'description' => 'Ikan bandeng segar',
                'price' => 28000,
                'max_price' => 33000,
                'unit' => 'kg',
                'stock' => 120,
                'min_purchase' => 25,
                'max_purchase' => null,
                'category' => $ikanCategory ?? $proteinCategory,
            ],
            // Protein - Telur
            [
                'name' => 'Telur Ayam',
                'description' => 'Telur ayam segar grade A',
                'price' => 28000,
                'max_price' => 32000,
                'unit' => 'kg',
                'stock' => 250,
                'min_purchase' => 40,
                'max_purchase' => null,
                'category' => $telurCategory ?? $proteinCategory,
            ],
            [
                'name' => 'Telur Bebek',
                'description' => 'Telur bebek segar',
                'price' => 35000,
                'max_price' => 40000,
                'unit' => 'kg',
                'stock' => 150,
                'min_purchase' => 30,
                'max_purchase' => null,
                'category' => $telurCategory ?? $proteinCategory,
            ],
            // Protein - Tahu & Tempe
            [
                'name' => 'Tahu Putih',
                'description' => 'Tahu putih segar dari produsen lokal',
                'price' => 12000,
                'max_price' => 15000,
                'unit' => 'kg',
                'stock' => 120,
                'min_purchase' => 20,
                'max_purchase' => null,
                'category' => $tahuTempeCategory ?? $proteinCategory,
            ],
            [
                'name' => 'Tempe',
                'description' => 'Tempe segar dari produsen lokal',
                'price' => 14000,
                'max_price' => 17000,
                'unit' => 'kg',
                'stock' => 100,
                'min_purchase' => 20,
                'max_purchase' => null,
                'category' => $tahuTempeCategory ?? $proteinCategory,
            ],
            // Protein - Udang & Seafood
            [
                'name' => 'Udang Segar',
                'description' => 'Udang segar ukuran sedang',
                'price' => 80000,
                'max_price' => 95000,
                'unit' => 'kg',
                'stock' => 60,
                'min_purchase' => 10,
                'max_purchase' => null,
                'category' => $udangSeafoodCategory ?? $proteinCategory,
            ],
            [
                'name' => 'Cumi Segar',
                'description' => 'Cumi segar siap masak',
                'price' => 70000,
                'max_price' => 85000,
                'unit' => 'kg',
                'stock' => 50,
                'min_purchase' => 10,
                'max_purchase' => null,
                'category' => $udangSeafoodCategory ?? $proteinCategory,
            ],
            // Sayuran - Daun
            [
                'name' => 'Kangkung Segar',
                'description' => 'Kangkung segar dari petani lokal',
                'price' => 8000,
                'max_price' => 10000,
                'unit' => 'ikat',
                'stock' => 150,
                'min_purchase' => 20,
                'max_purchase' => null,
                'category' => $sayuranDaunCategory ?? $veggieCategory,
            ],
            [
                'name' => 'Bayam Segar',
                'description' => 'Bayam segar organik',
                'price' => 7000,
                'max_price' => 9000,
                'unit' => 'ikat',
                'stock' => 120,
                'min_purchase' => 20,
                'max_purchase' => null,
                'category' => $sayuranDaunCategory ?? $veggieCategory,
            ],
            [
                'name' => 'Sawi Hijau',
                'description' => 'Sawi hijau segar',
                'price' => 7500,
                'max_price' => 9500,
                'unit' => 'ikat',
                'stock' => 100,
                'min_purchase' => 20,
                'max_purchase' => null,
                'category' => $sayuranDaunCategory ?? $veggieCategory,
            ],
            // Sayuran - Umbi
            [
                'name' => 'Wortel Segar',
                'description' => 'Wortel segar organik',
                'price' => 18000,
                'max_price' => 22000,
                'unit' => 'kg',
                'stock' => 90,
                'min_purchase' => 15,
                'max_purchase' => null,
                'category' => $sayuranUmbiCategory ?? $veggieCategory,
            ],
            [
                'name' => 'Singkong',
                'description' => 'Singkong segar',
                'price' => 10000,
                'max_price' => 13000,
                'unit' => 'kg',
                'stock' => 150,
                'min_purchase' => 30,
                'max_purchase' => null,
                'category' => $sayuranUmbiCategory ?? $veggieCategory,
            ],
            // Sayuran - Buah
            [
                'name' => 'Tomat',
                'description' => 'Tomat segar',
                'price' => 15000,
                'max_price' => 18000,
                'unit' => 'kg',
                'stock' => 110,
                'min_purchase' => 20,
                'max_purchase' => null,
                'category' => $sayuranBuahCategory ?? $veggieCategory,
            ],
            [
                'name' => 'Cabai Merah',
                'description' => 'Cabai merah keriting',
                'price' => 45000,
                'max_price' => 55000,
                'unit' => 'kg',
                'stock' => 80,
                'min_purchase' => 10,
                'max_purchase' => null,
                'category' => $sayuranBuahCategory ?? $veggieCategory,
            ],
            [
                'name' => 'Terong',
                'description' => 'Terong ungu segar',
                'price' => 12000,
                'max_price' => 15000,
                'unit' => 'kg',
                'stock' => 100,
                'min_purchase' => 15,
                'max_purchase' => null,
                'category' => $sayuranBuahCategory ?? $veggieCategory,
            ],
            // Sayuran - Kacang
            [
                'name' => 'Kacang Panjang',
                'description' => 'Kacang panjang segar',
                'price' => 14000,
                'max_price' => 17000,
                'unit' => 'ikat',
                'stock' => 130,
                'min_purchase' => 20,
                'max_purchase' => null,
                'category' => $sayuranKacangCategory ?? $veggieCategory,
            ],
            [
                'name' => 'Buncis',
                'description' => 'Buncis segar',
                'price' => 16000,
                'max_price' => 20000,
                'unit' => 'kg',
                'stock' => 100,
                'min_purchase' => 15,
                'max_purchase' => null,
                'category' => $sayuranKacangCategory ?? $veggieCategory,
            ],
            // Buah - Tropis
            [
                'name' => 'Pisang Raja',
                'description' => 'Pisang Raja matang pohon',
                'price' => 15000,
                'max_price' => 18000,
                'unit' => 'sisir',
                'stock' => 80,
                'min_purchase' => 15,
                'max_purchase' => null,
                'category' => $buahTropisCategory ?? $fruitCategory,
            ],
            [
                'name' => 'Mangga Harumanis',
                'description' => 'Mangga harumanis matang pohon',
                'price' => 25000,
                'max_price' => 30000,
                'unit' => 'kg',
                'stock' => 100,
                'min_purchase' => 20,
                'max_purchase' => null,
                'category' => $buahTropisCategory ?? $fruitCategory,
            ],
            [
                'name' => 'Pepaya',
                'description' => 'Pepaya matang pohon',
                'price' => 12000,
                'max_price' => 15000,
                'unit' => 'kg',
                'stock' => 90,
                'min_purchase' => 15,
                'max_purchase' => null,
                'category' => $buahTropisCategory ?? $fruitCategory,
            ],
            // Buah - Import
            [
                'name' => 'Apel Fuji',
                'description' => 'Apel fuji import',
                'price' => 35000,
                'max_price' => 42000,
                'unit' => 'kg',
                'stock' => 120,
                'min_purchase' => 20,
                'max_purchase' => null,
                'category' => $buahImportCategory ?? $fruitCategory,
            ],
            [
                'name' => 'Jeruk Navel',
                'description' => 'Jeruk navel import',
                'price' => 30000,
                'max_price' => 36000,
                'unit' => 'kg',
                'stock' => 100,
                'min_purchase' => 20,
                'max_purchase' => null,
                'category' => $buahImportCategory ?? $fruitCategory,
            ],
            // Bumbu - Dasar
            [
                'name' => 'Gula Pasir',
                'description' => 'Gula pasir putih berkualitas baik',
                'price' => 15000,
                'max_price' => 18000,
                'unit' => 'kg',
                'stock' => 300,
                'min_purchase' => 50,
                'max_purchase' => null,
                'category' => $bumbuDasarCategory ?? $spiceCategory,
            ],
            [
                'name' => 'Garam Halus',
                'description' => 'Garam halus untuk masakan',
                'price' => 5000,
                'max_price' => 7000,
                'unit' => 'kg',
                'stock' => 200,
                'min_purchase' => 50,
                'max_purchase' => null,
                'category' => $bumbuDasarCategory ?? $spiceCategory,
            ],
            // Bumbu - Bawang
            [
                'name' => 'Bawang Merah',
                'description' => 'Bawang merah lokal berkualitas',
                'price' => 32000,
                'max_price' => 38000,
                'unit' => 'kg',
                'stock' => 180,
                'min_purchase' => 30,
                'max_purchase' => null,
                'category' => $bawangCategory ?? $spiceCategory,
            ],
            [
                'name' => 'Bawang Putih',
                'description' => 'Bawang putih lokal segar',
                'price' => 35000,
                'max_price' => 42000,
                'unit' => 'kg',
                'stock' => 150,
                'min_purchase' => 25,
                'max_purchase' => null,
                'category' => $bawangCategory ?? $spiceCategory,
            ],
            [
                'name' => 'Bawang Bombay',
                'description' => 'Bawang bombay import',
                'price' => 28000,
                'max_price' => 35000,
                'unit' => 'kg',
                'stock' => 120,
                'min_purchase' => 20,
                'max_purchase' => null,
                'category' => $bawangCategory ?? $spiceCategory,
            ],
            // Bumbu - Minyak & Lemak
            [
                'name' => 'Minyak Goreng',
                'description' => 'Minyak goreng kemasan premium',
                'price' => 18000,
                'max_price' => 22000,
                'unit' => 'liter',
                'stock' => 400,
                'min_purchase' => 60,
                'max_purchase' => null,
                'category' => $minyakLemakCategory ?? $spiceCategory,
            ],
            [
                'name' => 'Mentega',
                'description' => 'Mentega untuk masakan',
                'price' => 25000,
                'max_price' => 30000,
                'unit' => 'kg',
                'stock' => 150,
                'min_purchase' => 20,
                'max_purchase' => null,
                'category' => $minyakLemakCategory ?? $spiceCategory,
            ],
            // Bumbu - Rempah
            [
                'name' => 'Kunyit',
                'description' => 'Kunyit segar',
                'price' => 20000,
                'max_price' => 25000,
                'unit' => 'kg',
                'stock' => 80,
                'min_purchase' => 15,
                'max_purchase' => null,
                'category' => $rempahCategory ?? $spiceCategory,
            ],
            [
                'name' => 'Jahe',
                'description' => 'Jahe segar',
                'price' => 22000,
                'max_price' => 27000,
                'unit' => 'kg',
                'stock' => 90,
                'min_purchase' => 15,
                'max_purchase' => null,
                'category' => $rempahCategory ?? $spiceCategory,
            ],
            [
                'name' => 'Lengkuas',
                'description' => 'Lengkuas segar',
                'price' => 18000,
                'max_price' => 22000,
                'unit' => 'kg',
                'stock' => 70,
                'min_purchase' => 15,
                'max_purchase' => null,
                'category' => $rempahCategory ?? $spiceCategory,
            ],
        ];

        // Get all supplier emails
        $supplierEmails = [
            'supplier@fsms.com',
            'supplier1@fsms.com',
            'supplier2@fsms.com',
            'supplier3@fsms.com',
        ];

        // Assign 5 random products to each supplier
        $usedProducts = []; // Track used products to avoid duplicates across suppliers

        foreach ($supplierEmails as $email) {
            $supplier = User::where('email', $email)->first();

            if (!$supplier) {
                $this->command->warn("Supplier with email {$email} not found, skipping...");
                continue;
            }

            // Get available products (not yet assigned to any supplier)
            $availableProducts = array_filter($allProducts, function($product) use ($usedProducts) {
                return !in_array($product['name'], $usedProducts);
            });

            // If not enough products available, reset used products (allow duplicates)
            if (count($availableProducts) < 5) {
                $availableProducts = $allProducts;
                $usedProducts = [];
            }

            // Randomly select 5 products for this supplier
            $selectedProducts = array_rand(array_values($availableProducts), min(5, count($availableProducts)));

            // Ensure $selectedProducts is an array
            if (!is_array($selectedProducts)) {
                $selectedProducts = [$selectedProducts];
            }

            // Create ingredients for selected products
            $productKeys = array_keys($availableProducts);
            foreach ($selectedProducts as $index) {
                $productData = $availableProducts[$productKeys[$index]];

                FoodItem::create([
                    'supplier_id' => $supplier->id,
                    'food_category_id' => $productData['category']->id,
                    'name' => $productData['name'],
                    'description' => $productData['description'],
                    'price' => $productData['price'],
                    'max_price' => $productData['max_price'] ?? null, // Max price set by admin (via seeder)
                    'default_price_increment' => 500, // Default price increment
                    'price_increment_type' => 'fixed', // Default type: fixed (Rp)
                    'unit' => $productData['unit'],
                    'stock' => $productData['stock'],
                    'min_purchase' => $productData['min_purchase'],
                    'max_purchase' => $productData['max_purchase'] ?? null,
                    'is_active' => true,
                ]);

                // Mark this product as used
                $usedProducts[] = $productData['name'];
            }

            $this->command->info("Assigned 5 random products to supplier: {$email}");
        }

        $this->command->info('Ingredients created successfully for all suppliers!');
    }
}


