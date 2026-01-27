<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Slide;

class SlideSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'title' => 'Slide 1',
                'description' => 'Description for Slide 1',
                'image' => 'slide-1.jpg',
                'url' => '/products',
            ],
            [
                'title' => 'Slide 2',
                'description' => 'Description for Slide 2',
                'image' => 'slide-2.jpg',
                'url' => '/category/tee',
            ],
            [
                'title' => 'Slide 3',
                'description' => 'Description for Slide 3',
                'image' => 'slide-3.jpg',
                'url' => '/category/shoes',
            ],
            [
                'title' => 'Slide 4',
                'description' => 'Description for Slide 4',
                'image' => 'slide-4.jpg',
                'url' => '/category/stuff',
            ],
        ];

        foreach ($data as $item) {
            Slide::create($item);
        }
    }
}
