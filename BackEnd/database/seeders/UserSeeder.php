<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Nguyen Hoang Huy',
                'email' => 'huy@gmail.com',
                'password' => Hash::make('12345678'),
                'role_id' => 1,
                'phone' => '0123666789',
                'gender' => 1,
                'address' => 'TP Hồ Chí Minh',
                'review' => 0,
            ],
            [
                'name' => 'Do Minh Nhat',
                'email' => 'nhat@gmail.com',
                'password' => Hash::make('12345678'),
                'role_id' => 5,
                'phone' => '0912345678',
                'gender' => 1,
                'address' => 'TP Hồ Chí Minh',
                'review' => 1,
            ],
            [
                'name' => 'Truong Manh Cuong',
                'email' => 'cuong@gmail.com',
                'password' => Hash::make('12345678'),
                'role_id' => 1,
                'phone' => '0123456789',
                'gender' => 1,
                'address' => 'TP Hồ Chí Minh',
                'review' => 1,
            ],
            [
                'name' => 'Nguyen Thanh Duong',
                'email' => 'duong@gmail.com',
                'password' => Hash::make('12345678'),
                'role_id' => 1,
                'phone' => '0987654321',
                'gender' => 1,
                'address' => 'TP Hồ Chí Minh',
                'review' => 1,
            ],
        ];

        foreach ($users as $item) {
            User::create($item);
        }
    }
}
