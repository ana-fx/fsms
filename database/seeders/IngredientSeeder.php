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
        // Get sub-categories
        $berasCategory = FoodCategory::where('slug', 'beras')->first();
        $mieCategory = FoodCategory::where('slug', 'mie')->first();
        $jagungCategory = FoodCategory::where('slug', 'jagung')->first();
        $kentangCategory = FoodCategory::where('slug', 'kentang')->first();
        $tepungCategory = FoodCategory::where('slug', 'tepung')->first();
        $dagingCategory = FoodCategory::where('slug', 'daging')->first();
        $ikanCategory = FoodCategory::where('slug', 'ikan')->first();
        $telurCategory = FoodCategory::where('slug', 'telur')->first();
        $tahuTempeCategory = FoodCategory::where('slug', 'tahu-tempe')->first();
        $udangSeafoodCategory = FoodCategory::where('slug', 'udang-seafood')->first();
        $sayuranDaunCategory = FoodCategory::where('slug', 'sayuran-daun')->first();
        $sayuranUmbiCategory = FoodCategory::where('slug', 'sayuran-umbi')->first();
        $sayuranBuahCategory = FoodCategory::where('slug', 'sayuran-buah')->first();
        $sayuranKacangCategory = FoodCategory::where('slug', 'sayuran-kacang')->first();
        $buahTropisCategory = FoodCategory::where('slug', 'buah-tropis')->first();
        $buahImportCategory = FoodCategory::where('slug', 'buah-import')->first();
        $bumbuDasarCategory = FoodCategory::where('slug', 'bumbu-dasar')->first();
        $bawangCategory = FoodCategory::where('slug', 'bawang')->first();
        $rempahCategory = FoodCategory::where('slug', 'rempah-rempah')->first();
        $minyakLemakCategory = FoodCategory::where('slug', 'minyak-lemak')->first();

        // Define all available products
        // Format: [sub_name, category, description, price, unit, stock, min_purchase, max_purchase]
        // Note: max_price is now set at category level, not ingredient level
        $allProducts = [
            // Karbohidrat - Beras
            ['Premium', $berasCategory, 'Beras berkualitas tinggi untuk konsumsi sehari-hari', 12500, 'kg', 500, 50, 500],
            ['Medium', $berasCategory, 'Beras medium grade untuk konsumsi', 11000, 'kg', 400, 50, 400],
            ['Standard', $berasCategory, 'Beras standard harga terjangkau', 9500, 'kg', 600, 50, 600],

            // Karbohidrat - Mie
            ['Instan', $mieCategory, 'Mie instan berbagai varian rasa', 3500, 'bungkus', 1000, 100, 1000],
            ['Kering', $mieCategory, 'Mie kering untuk masakan', 12000, 'kg', 300, 50, null],

            // Karbohidrat - Jagung
            ['Pipil', $jagungCategory, 'Jagung pipil siap masak', 12000, 'kg', 200, 40, null],

            // Karbohidrat - Kentang
            ['Segar', $kentangCategory, 'Kentang segar untuk masakan', 15000, 'kg', 180, 30, null],

            // Karbohidrat - Tepung
            ['Terigu', $tepungCategory, 'Tepung terigu protein tinggi', 14000, 'kg', 250, 50, null],
            ['Beras', $tepungCategory, 'Tepung beras halus', 12000, 'kg', 200, 40, null],

            // Protein - Daging
            ['Ayam Potong Segar', $dagingCategory, 'Ayam potong segar tanpa kepala dan ceker', 35000, 'kg', 200, 30, null],
            ['Sapi', $dagingCategory, 'Daging sapi segar pilihan', 120000, 'kg', 100, 10, null],
            ['Kambing', $dagingCategory, 'Daging kambing segar', 130000, 'kg', 80, 10, null],

            // Protein - Ikan
            ['Tongkol', $ikanCategory, 'Ikan tongkol segar siap masak', 25000, 'kg', 150, 25, null],
            ['Tenggiri', $ikanCategory, 'Ikan tenggiri segar', 35000, 'kg', 100, 20, null],
            ['Bandeng', $ikanCategory, 'Ikan bandeng segar', 28000, 'kg', 120, 25, null],

            // Protein - Telur
            ['Ayam', $telurCategory, 'Telur ayam segar grade A', 28000, 'kg', 250, 40, null],
            ['Bebek', $telurCategory, 'Telur bebek segar', 35000, 'kg', 150, 30, null],

            // Protein - Tahu & Tempe
            ['Tahu Putih', $tahuTempeCategory, 'Tahu putih segar dari produsen lokal', 12000, 'kg', 120, 20, null],
            ['Tempe', $tahuTempeCategory, 'Tempe segar dari produsen lokal', 14000, 'kg', 100, 20, null],

            // Protein - Udang & Seafood
            ['Udang Segar', $udangSeafoodCategory, 'Udang segar ukuran sedang', 80000, 'kg', 60, 10, null],
            ['Cumi Segar', $udangSeafoodCategory, 'Cumi segar siap masak', 70000, 'kg', 50, 10, null],

            // Sayuran - Daun
            ['Kangkung', $sayuranDaunCategory, 'Kangkung segar dari petani lokal', 8000, 'ikat', 150, 20, null],
            ['Bayam', $sayuranDaunCategory, 'Bayam segar organik', 7000, 'ikat', 120, 20, null],
            ['Sawi Hijau', $sayuranDaunCategory, 'Sawi hijau segar', 7500, 'ikat', 100, 20, null],

            // Sayuran - Umbi
            ['Wortel', $sayuranUmbiCategory, 'Wortel segar organik', 18000, 'kg', 90, 15, null],
            ['Singkong', $sayuranUmbiCategory, 'Singkong segar', 10000, 'kg', 150, 30, null],

            // Sayuran - Buah
            ['Tomat', $sayuranBuahCategory, 'Tomat segar', 15000, 'kg', 110, 20, null],
            ['Cabai Merah', $sayuranBuahCategory, 'Cabai merah keriting', 45000, 'kg', 80, 10, null],
            ['Terong', $sayuranBuahCategory, 'Terong ungu segar', 12000, 'kg', 100, 15, null],

            // Sayuran - Kacang
            ['Kacang Panjang', $sayuranKacangCategory, 'Kacang panjang segar', 14000, 'ikat', 130, 20, null],
            ['Buncis', $sayuranKacangCategory, 'Buncis segar', 16000, 'kg', 100, 15, null],

            // Buah - Tropis
            ['Pisang Raja', $buahTropisCategory, 'Pisang Raja matang pohon', 15000, 'sisir', 80, 15, null],
            ['Mangga Harumanis', $buahTropisCategory, 'Mangga harumanis matang pohon', 25000, 'kg', 100, 20, null],
            ['Pepaya', $buahTropisCategory, 'Pepaya matang pohon', 12000, 'kg', 90, 15, null],

            // Buah - Import
            ['Apel Fuji', $buahImportCategory, 'Apel fuji import', 35000, 'kg', 120, 20, null],
            ['Jeruk Navel', $buahImportCategory, 'Jeruk navel import', 30000, 'kg', 100, 20, null],

            // Bumbu - Dasar
            ['Gula Pasir', $bumbuDasarCategory, 'Gula pasir putih berkualitas baik', 15000, 'kg', 300, 50, null],
            ['Garam Halus', $bumbuDasarCategory, 'Garam halus untuk masakan', 5000, 'kg', 200, 50, null],

            // Bumbu - Bawang
            ['Bawang Merah', $bawangCategory, 'Bawang merah lokal berkualitas', 32000, 'kg', 180, 30, null],
            ['Bawang Putih', $bawangCategory, 'Bawang putih lokal segar', 35000, 'kg', 150, 25, null],
            ['Bombay', $bawangCategory, 'Bawang bombay import', 28000, 'kg', 120, 20, null],

            // Bumbu - Minyak & Lemak
            ['Minyak Goreng', $minyakLemakCategory, 'Minyak goreng kemasan premium', 18000, 'liter', 400, 60, null],
            ['Mentega', $minyakLemakCategory, 'Mentega untuk masakan', 25000, 'kg', 150, 20, null],

            // Bumbu - Rempah
            ['Kunyit', $rempahCategory, 'Kunyit segar', 20000, 'kg', 80, 15, null],
            ['Jahe', $rempahCategory, 'Jahe segar', 22000, 'kg', 90, 15, null],
            ['Lengkuas', $rempahCategory, 'Lengkuas segar', 18000, 'kg', 70, 15, null],
        ];

        // Get all supplier emails
        $supplierEmails = [
            'supplier@fsms.com',
            'supplier1@fsms.com',
            'supplier2@fsms.com',
            'supplier3@fsms.com',
        ];

        // Assign 5 random products to each supplier
        foreach ($supplierEmails as $email) {
            $supplier = User::where('email', $email)->first();

            if (!$supplier) {
                $this->command->warn("Supplier with email {$email} not found, skipping...");
                continue;
            }

            // Randomly select 5 products
            $selectedIndices = array_rand($allProducts, min(5, count($allProducts)));
            if (!is_array($selectedIndices)) {
                $selectedIndices = [$selectedIndices];
            }

            // Create ingredients for selected products
            foreach ($selectedIndices as $index) {
                [$subName, $category, $description, $price, $unit, $stock, $minPurchase, $maxPurchase] = $allProducts[$index];

                // Skip if category is null
                if (!$category) {
                    continue;
                }

                // Combine sub_name with category name to create final name
                $finalName = $subName ? $subName . ' ' . $category->name : $category->name;

                FoodItem::create([
                    'supplier_id' => $supplier->id,
                    'food_category_id' => $category->id,
                    'name' => $finalName,
                    'sub_name' => $subName,
                    'description' => $description,
                    'price' => $price,
                    // max_price is now set at category level, not ingredient level
                    'default_price_increment' => 500,
                    'price_increment_type' => 'fixed',
                    'unit' => $unit,
                    'stock' => $stock,
                    'min_purchase' => $minPurchase,
                    'max_purchase' => $maxPurchase,
                    'is_active' => true,
                ]);
            }

            $this->command->info("Assigned 5 random products to supplier: {$email}");
        }

        $this->command->info('Ingredients created successfully for all suppliers!');
    }
}
