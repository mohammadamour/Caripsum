<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AppLayout extends Component
{
    public bool $showHeader;
    public ?string $bodyClass;
    public ?string $title;

    public function __construct(bool $showHeader = true, ?string $bodyClass = null, ?string $title = null)
    {
        $this->showHeader = $showHeader;
        $this->bodyClass = $bodyClass;
        $this->title = $title;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('layouts.app');
    }
}
