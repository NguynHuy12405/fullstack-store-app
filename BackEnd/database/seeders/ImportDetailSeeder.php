<?php

namespace Database\Seeders;

use App\Models\ImportDetail;
use Illuminate\Database\Seeder;

class ImportDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['import_id' => 1, 'variant_id' => 1, 'quantity' => 50, 'price' => 200000],
            ['import_id' => 2, 'variant_id' => 2, 'quantity' => 30, 'price' => 250000],
            ['import_id' => 3, 'variant_id' => 3, 'quantity' => 40, 'price' => 220000],
            ['import_id' => 4, 'variant_id' => 4, 'quantity' => 20, 'price' => 270000],
        ];

        foreach ($data as $item) {
            ImportDetail::create($item);
        }

    }
}
