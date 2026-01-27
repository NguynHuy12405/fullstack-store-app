<?php

namespace Database\Seeders;

use App\Models\Import;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ImportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $data = [
            ['user_id' => 1, 'total_price' => 15000000],
            ['user_id' => 2, 'total_price' => 12000000],
            ['user_id' => 3, 'total_price' => 18000000],
            ['user_id' => 4, 'total_price' => 10000000],
        ];

        foreach ($data as $item) {
            Import::create($item);
        }
    }
}
