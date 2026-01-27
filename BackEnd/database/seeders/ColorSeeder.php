<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Color;
use Carbon\Carbon;

class ColorSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['name' => 'Đen', 'hex_code' => '#000000'],
            ['name' => 'Trắng', 'hex_code' => '#ffffff'],
            ['name' => 'Đỏ', 'hex_code' => '#ff0000'],
            ['name' => 'Xanh dương', 'hex_code' => '#0000ff'],
        ];

        foreach ($data as $item) {
            Color::create($item);
        }
    }
}
