<?php

namespace App\View\Components;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class VendorUserMenu extends Component
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
        $user = Auth::user();

        $paymentAccountService = service('PaymentAccount');
        return view(vendorTheme() . '.components.user_menu', [
            'user' => $user,
            'total' => $paymentAccountService->getLoggedUsersAccount(),
        ]);
    }
}
