<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Help;
class HelpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data=[
            ['name'=>'Cuong',
            'email'=>'cuong@gmail.com',
            'phone'=>'0968929848',
            'message'=>'đặt hàng như thế nào?'
            ],
            ['name'=>'huy',
            'email'=>'huy@gmail.com',
            'phone'=>'0968929888',
            'message'=>'đặt hàng bao  lâu có hàng?'],
        ];
        foreach ($data as $item) {
            Help::create($item);
        };
    }
}
