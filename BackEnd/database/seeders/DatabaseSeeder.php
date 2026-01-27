<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            TagSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            CategorySeeder::class,
            BrandSeeder::class,
            ColorSeeder::class,
            SizeSeeder::class,
            ProductSeeder::class,
            VariantSeeder::class,
            CartDetailSeeder::class,
            ImportSeeder::class,
            ImportDetailSeeder::class,
            ShipStatusSeeder::class,
            OrderStatusSeeder::class,
            OrderSeeder::class,
            OrderDetailSeeder::class,
            ProductTagSeeder::class,
            ReviewSeeder::class,
            SlideSeeder::class,
            ContactSeeder::class,
        ]);
    }
}
