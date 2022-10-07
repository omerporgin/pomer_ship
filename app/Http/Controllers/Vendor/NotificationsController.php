<?php

namespace App\Http\Controllers\Vendor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NotificationsController extends Controller
{

    /**
     * @return \Illuminate\Http\Response
     */
    public function notifications()
    {
        return response()->view(vendorTheme() . '.notifications');
    }

}
