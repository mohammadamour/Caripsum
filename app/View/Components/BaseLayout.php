<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class BaseLayout extends Component
{
    /**
     * Optional CSS class applied to the <body> element.
     */
    public ?string $cssClass = null;

    /**
     * Create a new component instance.
     */
    public function __construct(?string $cssClass = null)
    {
        $this->cssClass = $cssClass;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('layouts.base');
    }
}
