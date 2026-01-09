<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AppLayout extends Component
{
    /**
     * Controls visibility of the global header/navbar.
     */
    public bool $showHeader;

    /**
     * Optional page-specific <body> CSS class.
     */
    public ?string $bodyClass;

    /**
     * Create a new component instance.
     */
    public function __construct(bool $showHeader = true, ?string $bodyClass = null)
    {
        $this->showHeader = $showHeader;
        $this->bodyClass = $bodyClass;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('layouts.app');
    }
}
