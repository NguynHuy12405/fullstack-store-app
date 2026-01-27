<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Carbon\Carbon;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['name' => 'Áo thun', 'slug' => 'tee'],
            ['name' => 'Quần', 'slug' => 'pants'],
            ['name' => 'Áo khoác', 'slug' => 'jacket'],
            ['name' => 'Giày', 'slug' => 'shoes'],
        ];

        foreach ($data as $item) {
            Category::create($item);
        }
    }
}
