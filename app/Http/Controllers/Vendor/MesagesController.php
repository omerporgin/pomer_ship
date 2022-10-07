<?php

namespace App\Http\Controllers\Vendor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MesagesController extends Controller
{

    /**
     * @return \Illuminate\Http\Response
     */
    public function messages()
    {
        return response()->view(vendorTheme() . '.messages');
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function alerts()
    {
        return response()->view(vendorTheme() . '.alerts');
    }

}
