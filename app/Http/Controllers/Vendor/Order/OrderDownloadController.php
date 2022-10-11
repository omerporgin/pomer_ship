<?php

namespace App\Http\Controllers\Vendor\Order;

use App\Http\Controllers\Controller;
use App\Libraries\OrderEntegrationServices\EntegrationService;

class OrderDownloadController extends Controller
{

    /**
     * Tarihe göre order listesini alıp kaydeder.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllOrders()
    {
        $service = new EntegrationService;
        $result = $service->getAllOrders();
        return response()->json($result);
    }
}
