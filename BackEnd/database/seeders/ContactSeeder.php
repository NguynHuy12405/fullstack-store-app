<?php

namespace Database\Seeders;

use App\Models\Contact;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Facebook',
                'url' => 'FashionShop.facebook.com',
                'status'=>true,
            ],
            [
                'name' => 'Zalo',
                'url' => '0123456789',
                'status'=>true,
            ],
            [
                'name' => 'Gmail',
                'url' => 'FashionShop@gmail.com',
                'status'=>true,
            ],
            [
                'name' => 'Youtube',
                'url' => 'FashionShop',
                'status'=>true,
            ],
            [
                'name'=>'Phone',
                'url'=>'0968999999',
                'status'=>true,
            ],
            [
                'name'=>'NameStore',
                'url'=>'Fashtion Shop',
                'status'=>true,
            ],
            [
                'name'=>'Address',
                'url'=>'65 Huỳnh thúc kháng, district 1',
                'status'=>true,
            ],
            [
            'name'=>'Link',
            'url'=>'fashtionShop.vn',
            'status'=>true,
            ]
        ];

        foreach ($data as $item) {
            Contact::create($item);
        }
    }
}
