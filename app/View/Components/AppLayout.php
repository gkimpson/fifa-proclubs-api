<?php

namespace App\View\Components;
use Illuminate\View\Component;

class AppLayout extends Component
{
    public $user;

    public function __construct()
    {
        $this->user = auth()->user();
    }

    /**
     * Get the view / contents that represents the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('layouts.app');
    }
}
