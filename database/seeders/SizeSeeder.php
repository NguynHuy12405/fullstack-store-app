<?php

namespace Database\Seeders;

use App\Models\Size;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['name' => 'S', 'category_id' => 1],
            ['name' => 'M', 'category_id' => 2],
            ['name' => 'L', 'category_id' => 3],
            ['name' => 'XL', 'category_id' => 4],
        ];

        foreach ($data as $item) {
            Size::create($item);
        }
    }
}
