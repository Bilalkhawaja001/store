<?php

namespace App\View\Components\Partials;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FormCheckbox extends Component
{
    public function __construct()
    {
    }

    public function render(): View|Closure|string
    {
        return view('components.partials.form-checkbox');
    }
}
