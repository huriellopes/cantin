<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class MenuSiteComponent extends Component
{

    /**
     * @param object $params
     */
    public function __construct(
        public object $params,
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.menu-site-component');
    }
}
