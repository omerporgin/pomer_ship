<?php

namespace App\View\Components;

use Illuminate\View\Component;

class AdminMenu extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $orderStatusService = app()->make(\App\Services\OrderStatusService::class);
        $statusses = $orderStatusService->getAllFiltered((object)[
            'status_of' => 1,
            'show_on_menu' => 1,
        ])[0]->get();

        return view(adminTheme().'.components.menu',[
            'orderStatusess' => $statusses,
        ]);
    }
}
