<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Category;

class Navigation extends Component
{
    public $categories;

    public function __construct()
    {
        $this->categories = Category::where('status', 1)
            ->whereHas('products', function ($q) {
                $q->where('status', 1);
            })
            ->with(['brands' => function ($q) {
                $q->whereHas('products', function ($p) {
                    $p->where('status', 1);
                });
            }])
            ->orderBy('name')
            ->get();
    }

    public function render()
    {
        return view('components.navigation');
    }
}
