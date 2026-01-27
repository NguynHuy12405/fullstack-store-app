<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OrderStatus;

class OrderStatusSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['name' => 'Chờ xác nhận'],
            ['name' => 'Đã xác nhận'],
            ['name' => 'Hoàn thành'],
            ['name' => 'Đã hủy'],
        ];

        foreach ($data as $item) {
            OrderStatus::create($item);
        }
    }
}
