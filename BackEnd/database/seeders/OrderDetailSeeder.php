<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OrderDetail;

class OrderDetailSeeder extends Seeder
{
    public function run(): void
    {
       $data = [
            ['order_id' => 1, 'variant_id' => 1, 'quantity' => 1, 'price' => 499000],
            ['order_id' => 2, 'variant_id' => 1, 'quantity' => 2, 'price' => 499000],
            ['order_id' => 3, 'variant_id' => 3, 'quantity' => 1, 'price' => 350000],
            ['order_id' => 4, 'variant_id' => 4, 'quantity' => 1, 'price' => 200000],
        ];

        foreach ($data as $item) {
            OrderDetail::create($item);
        }
    }
}
