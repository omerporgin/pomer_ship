<?php

namespace App\Http\Controllers\Admin\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\DatatableRequest;
use App\Services\OrderService;
use App\Services\OrderStatusService;
use Illuminate\Http\Request;

class OrderDataTableController extends Controller
{
    /**
     * @var service
     */
    protected $service;

    public function __construct(OrderService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     *  For both vendors and admin
     * dataTableJson
     *
     * @param DatatableRequest $request
     * @return Response
     */
    public function index(DatatableRequest $request)
    {

        try {
            $filters = [
                "start" => $request->start,
                "length" => $request->length,
                "search" => $request->search,
            ];

            $requestData = json_decode(base64_decode($request->data));
            $requestData->order_statuses = array_filter($requestData->order_statuses);
            if (isset($requestData->order_statuses) and !empty($requestData->order_statuses)) {
                $filters["order_statuses"] = $requestData->order_statuses;
            }
            if (isset($requestData->template)) {
                $filters["userType"] = $requestData->template;
            }
            if (isset($requestData->vendors)) {
                $filters["vendorList"] = $requestData->vendors;
            }

            $list = $this->service->getAll($filters);
            $items = $list["list"];
            $total = $list["total"];
            $data = [];

            foreach ($items as $item) {

                $addData = $item->toArray();
                $status = app()->make(OrderStatusService::class, [
                    'id' => $item->real_status
                ]);
                $statusItem = $status->get();
                $addData['real_status_name'] = $statusItem->name;
                $addData['real_status_color'] = $statusItem->color;
                $addData['deletable'] = $this->service->deletable($item->id);
                $addData['DestroyUrl'] = route("vendor_orders.destroy", $item->id);

                $packageService = service('OrderPackage');
                $addData['packages'] = $packageService::getOrdersPackages($item->id);
                $data[] = $addData;
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

            reportException($e);

            return [
                'status' => 500,
                'error' => $e->getMessage(),
                'debug' => __CLASS__,
            ];
        }
    }
}
