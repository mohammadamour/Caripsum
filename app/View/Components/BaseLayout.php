<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class BaseLayout extends Component
{
    public ?string $cssClass;
    public ?string $title;
    public bool $showFooter;

    public function __construct(?string $cssClass = null, ?string $title = null, bool $showFooter = true)
    {
        $this->cssClass = $cssClass;
        $this->title = $title;
        $this->showFooter = $showFooter;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('layouts.base');
    }
}
