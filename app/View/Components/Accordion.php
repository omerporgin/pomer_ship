<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Accordion extends Component
{

    protected $list;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($list)
    {
        $this->list = $list;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.accordion', [
            'list' => $this->list
        ]);
    }
}
