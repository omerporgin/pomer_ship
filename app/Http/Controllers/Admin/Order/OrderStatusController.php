<?php

namespace App\Http\Controllers\Admin\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderStatusController extends Controller
{

    /**
     * @return void
     */
    public function index()
    {
        return response()->view(adminTheme() . '.order_status');
    }
}
