<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['name' => 'Gucci', 'slug' => 'gucci', 'image' => 'gucci.png'],
            ['name' => 'Chanel', 'slug' => 'chanel', 'image' => 'chanel.png'],
            ['name' => 'Canifa', 'slug' => 'canifa', 'image' => 'canifa.png'],
            ['name' => 'IVY Moda', 'slug' => 'ivy-moda', 'image' => 'ivy-moda.png'],
        ];

        foreach ($data as $item) {
            Brand::create($item);
        }
    }
}
