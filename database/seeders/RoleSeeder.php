<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use Carbon\Carbon;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['name'=>'user'],
            ['name'=>'admin-user'],
            ['name'=>'admin-product'],
            ['name'=>'admin-shipper'],
            ['name'=>'admin-head'],
        ];

        foreach ($data as $item) {
            Role::create($item);
        }
    }
}
