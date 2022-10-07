<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Services\NotificationService;

class VendorNotifications extends Component
{

    /**
     * @var NotificationService
     */
    protected $service;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(NotificationService $service)
    {
        $this->service = $service;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $notifications = $this->service->getLastNotificationsOfLoggedUser();
        $list = [];
        foreach($notifications as $item){
            $list[] = $item->toArray();
        }

        return view(vendorTheme().'.components.vendor-notifications',[
            'jsonNotifications' =>str_replace('"', "'", json_encode($list)),
        ]);
    }
}
