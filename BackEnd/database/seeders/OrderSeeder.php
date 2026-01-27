<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\User;
use App\Models\OrderStatus;
use App\Models\ShipStatus;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'user_id' => 1,
                'name' => 'Nguyen Hoang Huy',
                'phone' => '0123456789',
                'address' => 'TP Hồ Chí Minh',
                'order_status_id' => 1,
                'ship_status_id' => 1,
                'total_price' => 499000
            ],
            [
                'user_id' => 1,
                'name' => 'Do Minh Nhat',
                'phone' => '0912345678',
                'address' => 'TP Hồ Chí Minh',
                'order_status_id' => 1,
                'ship_status_id' => 1,
                'total_price' => 998000
            ],
            [
                'user_id' => 3,
                'name' => 'Truong Manh Cuong',
                'phone' => '0912345678',
                'address' => 'TP Hồ Chí Minh',
                'order_status_id' => 1,
                'ship_status_id' => 1,
                'total_price' => 350000
            ],
            [
                'user_id' => 4,
                'name' => 'Nguyen Thanh Duong',
                'phone' => '0912345678',
                'address' => 'TP Hồ Chí Minh',
                'order_status_id' => 1,
                'ship_status_id' => 1,
                'total_price' => 200000
            ],
        ];

        foreach ($data as $item) {
            Order::create($item);
        }
    }
}
