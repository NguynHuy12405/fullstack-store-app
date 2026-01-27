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
            ['name' => 'Áo thun', 'slug' => 'ao-thun'],
            ['name' => 'Quần', 'slug' => 'quan'],
            ['name' => 'Giày', 'slug' => 'giay'],
            ['name' => 'Phụ kiện', 'slug' => 'phu-kien'],
        ];

        foreach ($data as $item) {
            Category::create($item);
        }
    }
}
