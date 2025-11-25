<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Header extends Component
{
    public function __construct()
    {
        //
    }

    /**
     * @return View
     */
    public function render(): View
    {
        /** @var view-string $viewPath */
        $viewPath = 'components.header';

        return view($viewPath);
    }
}
