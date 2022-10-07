<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class PagesController extends Controller
{

    /**
     * @return Response
     */
    public function index()
    {
        $paymentAccountService = app()->make(\App\Services\PaymentAccountService::class);

        // Get order statusses
        $orderStatusService = app()->make(\App\Services\OrderStatusService::class);
        $filters = (object)[
            'status_of' => 1,
            'show_on_menu' => 1,
        ];
        list($statusses, $count) = $orderStatusService->getAllFiltered($filters);
        $newStatusess = collect();
        foreach ($statusses->get() as $item) {
            // Remove errors
            if ($item->id != 25) {
                $newStatusess->add($item);
            }
        };
        return response()->view(vendorTheme('dashboard_vendor'), [
            'total' => $paymentAccountService->getLoggedUsersAccount(),
            'orderStatusess' => $newStatusess,
        ]);
    }

    /**
     * @return void
     */
    public function orderStatusses()
    {
        return response()->view(vendorTheme() . '.order_status');
    }

    public function entegrations(){
        $service = service('Entegration');

        return response()->view(vendorTheme() . ".entegration_list", [
            'list' => $service->getAll()
        ]);
    }
}
