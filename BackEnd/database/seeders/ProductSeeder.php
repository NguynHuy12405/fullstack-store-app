<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Áo thun nam basic',
                'slug' => 'ao-thun-nam-basic',
                'description' => 'Áo thun cotton 100%, thoáng mát, dễ phối đồ.',
                'rating' => 4.5,
                'view' => 120,
                'category_id' => 1,
                'brand_id' => 1,
            ],
            [
                'name' => 'Quần jean slimfit',
                'slug' => 'quan-jean-slimfit',
                'description' => 'Quần jean dáng slimfit, co giãn nhẹ.',
                'rating' => 4.3,
                'view' => 90,
                'category_id' => 2,
                'brand_id' => 2,
            ],
            [
                'name' => 'Áo khoác hoodie',
                'slug' => 'ao-khoac-hoodie',
                'description' => 'Áo khoác hoodie trẻ trung, giữ ấm tốt.',
                'rating' => 4.7,
                'view' => 150,
                'category_id' => 3,
                'brand_id' => 3,
            ],
            [
                'name' => 'Giày sneaker thể thao',
                'slug' => 'giay-sneaker-the-thao',
                'description' => 'Giày sneaker năng động, phù hợp đi học và đi chơi.',
                'rating' => 4.6,
                'view' => 200,
                'category_id' => 4,
                'brand_id' => 4,
            ],
        ];

        foreach ($data as $item) {
            Product::create($item);
        }
    }
}
