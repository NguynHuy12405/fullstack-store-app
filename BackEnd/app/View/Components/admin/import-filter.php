<?php

namespace App\View\Components\admin;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class import-filter extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.admin.import-filter');
    }
}
