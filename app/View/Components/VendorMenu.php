<?php

namespace App\View\Components;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;
use App\Services\EntegrationDataService;

class VendorMenu extends Component
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

        // Totals
        foreach ($statusses as $status) {

            $status->total = \App\Models\Order::where('vendor_id', Auth::id())->where('real_status', $status->id)
                ->count();
        }

        return view(vendorTheme() . '.components.menu', [
            'orderServices' => $this->getServices(),
            'orderStatusess' => $statusses,
        ]);
    }

    /**
     * @return array
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    private function getServices(): array
    {
        return EntegrationDataService::getUserData(Auth::id());
    }
}
