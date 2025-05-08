<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AdminAppLayout extends Component
{
    /**
     * Create a new component instance.
     */
    public $title;
    
    public function __construct($title = null)
    {
        $this->title = $title ?? 'Default Title';
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('layouts.admin.app');
    }
}
