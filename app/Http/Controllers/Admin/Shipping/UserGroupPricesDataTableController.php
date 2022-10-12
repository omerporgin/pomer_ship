<?php

namespace App\Http\Controllers\Admin\Shipping;

use App\Http\Controllers\Controller;
use App\Http\Requests\DatatableRequest;
use App\Services\UserGroupPriceService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserGroupPricesDataTableController extends Controller
{
    /**
     * @var service
     */
    protected $service;

    public function __construct(UserGroupPriceService $service)
    {
        $this->service = $service;
    }


    public function index(DatatableRequest $request)
    {
        $data = parseBase64data($request->data);
        try {
            $filters = [
                "start" => $request->start,
                "length" => $request->length,
                "search" => $request->search,
                "order" => $request->order,
                'user_group' => $data->id // Required
            ];

            $list = $this->service->getAll($filters);
            $items = $list["list"];
            $data = [];

            foreach ($items as $item) {
                $shipping = service('Shipping', $item->shipping_id);
                $data[] = array_merge($item->toArray($item), [
                    'name' => $shipping->name,
                    'serviceName' => $item->service_name,
                    'deletable' => $this->service->deletable($item->id),
                    'deletableMsg' => $this->service->deletableMsg,
                    'DestroyUrl' => route('admin_user_group_prices.destroy', $item->id),
                ]);
            }

            return [
                'status' => 200,
                "total" => $list["total"],
                "data" => $data,
                "draw" => $request->draw,
                "recordsTotal" => $list["total"],
                "recordsFiltered" => $list["total"],
            ];
        } catch (\Exception $e) {

            reportException($e, 1);

            return [
                'status' => 500,
                'error' => $e->getMessage(),
                'debug' => __CLASS__,
            ];
        }
    }
}
