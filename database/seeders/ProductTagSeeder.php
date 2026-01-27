<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::find(1)->tags()->sync([1]);
        Product::find(2)->tags()->sync([2]);
        Product::find(3)->tags()->sync([3]);
        Product::find(4)->tags()->sync([4]);
    }
}
