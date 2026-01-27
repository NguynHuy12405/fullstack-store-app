<?php

namespace Database\Seeders;

use App\Models\CartDetail;
use Illuminate\Database\Seeder;

class CartDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['user_id' => 1, 'variant_id' => 1, 'quantity' => 2],
            ['user_id' => 2, 'variant_id' => 2, 'quantity' => 1],
            ['user_id' => 3, 'variant_id' => 3, 'quantity' => 3],
            ['user_id' => 4, 'variant_id' => 4, 'quantity' => 1],
        ];

        foreach ($data as $item) {
            CartDetail::create($item);
        }
    }
}
