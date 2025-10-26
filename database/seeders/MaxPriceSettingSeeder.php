<?php

namespace Database\Seeders;

use App\Models\FoodCategory;
use App\Models\MaxPriceSetting;
use Illuminate\Database\Seeder;

class MaxPriceSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = FoodCategory::all();

        $maxPrices = [
            'karbohidrat' => [
                'max_price' => 15000,
                'unit' => 'kg',
                'notes' => 'Harga maksimal untuk bahan karbohidrat termasuk beras, gandum, kentang, dll',
            ],
            'protein' => [
                'max_price' => 45000,
                'unit' => 'kg',
                'notes' => 'Harga maksimal untuk bahan protein termasuk daging, ikan, telur, tahu, tempe, dll',
            ],
            'sayuran' => [
                'max_price' => 20000,
                'unit' => 'kg',
                'notes' => 'Harga maksimal untuk berbagai jenis sayuran segar',
            ],
            'buah-buahan' => [
                'max_price' => 25000,
                'unit' => 'kg',
                'notes' => 'Harga maksimal untuk buah-buahan segar',
            ],
            'bumbu-rempah' => [
                'max_price' => 35000,
                'unit' => 'kg',
                'notes' => 'Harga maksimal untuk bumbu dapur dan rempah-rempah',
            ],
        ];

        foreach ($categories as $category) {
            if (isset($maxPrices[$category->slug])) {
                MaxPriceSetting::updateOrCreate(
                    ['food_category_id' => $category->id],
                    [
                        'max_price' => $maxPrices[$category->slug]['max_price'],
                        'unit' => $maxPrices[$category->slug]['unit'],
                        'notes' => $maxPrices[$category->slug]['notes'],
                    ]
                );
            }
        }

        $this->command->info('Max price settings created successfully!');
    }
}

