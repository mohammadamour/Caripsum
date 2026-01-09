<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AuthLayout extends Component
{
    /**
     * The page title.
     */
    public ?string $title;

    /**
     * The body class for styling.
     */
    public ?string $bodyClass;

    /**
     * Controls visibility of the global header/navbar.
     */
    public bool $showHeader;

    /**
     * Create a new component instance.
     */
    public function __construct(?string $title = null, ?string $bodyClass = null, bool $showHeader = false)
    {
        $this->title = $title;
        $this->bodyClass = $bodyClass;
        $this->showHeader = $showHeader;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('layouts.auth');
    }
}
