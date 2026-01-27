<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Variant;

class VariantSeeder extends Seeder
{
    public function run(): void
    {

        $data = [
            ['product_id' => 1, 'color_id' => 1, 'size_id' => 1, 'price' => 550000, 'sale_price' => 499000, 'quantity' => 100],
            ['product_id' => 1, 'color_id' => 1, 'size_id' => 2, 'price' => 550000, 'sale_price' => 499000, 'quantity' => 80],
            ['product_id' => 2, 'color_id' => 2, 'size_id' => 1, 'price' => 350000, 'sale_price' => null, 'quantity' => 50],
            ['product_id' => 3, 'color_id' => 3, 'size_id' => 3, 'price' => 250000, 'sale_price' => 200000, 'quantity' => 30],
        ];

        foreach ($data as $item) {
            Variant::create($item);
        }
    }
}
