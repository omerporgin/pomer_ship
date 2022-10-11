<?php

namespace App\Http\Controllers\Vendor\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\DatatableRequest;
use App\Libraries\OrderLocation;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderDataTableController extends Controller
{
    /**
     * @var OrderService
     */
    protected $service;

    /**
     * @param OrderService $service
     */
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
                'start' => $request->start,
                'length' => $request->length,
                'search' => $request->search,
                'withService' => true,
                'order' => $request->order
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

            $total = $list["total"];
            $data = [];

            foreach ($list["serviceList"] as $item) {
                $id = $item->getID();
                $itemArray = $item->asArray();
                $addData = $itemArray[$item->id];

                $status = service('OrderStatus', $item->real_status);

                // Package Shippings
                /*
                $user = $item->getUser();
                $userGroupService = service('UserGroup', $user->user_group_id);
                $packages = $userGroupService->getCalculatedPriceByCountryId($item->country_id, 5);
                */

                $packageService = service('OrderPackage');

                $packageList = $packageService::getOrdersPackages($id);

                $shipmentName = '-';
                $trackingNo = '-';
                $suitablePackageList = [];
                if (isset($packageList[0]->shipment_id)) {
                    $shipmentService = service('Shipping', $packageList[0]->shipment_id);
                    $shipmentName = $shipmentService->name;
                    $trackingNo = $packageList[0]->tracking_number;
                }

                $orderLocation = new OrderLocation($item);

                $data[] = array_merge($addData, [
                    'country' => $orderLocation->getFullName(),
                    'real_status_name' => $status->name,
                    'real_status_color' => $status->color,
                    'deletable' => $this->service->deletable($item->id),
                    'DestroyUrl' => ifExistRoute('vendor_orders.destroy', [
                        'vendor_order' => $id
                    ]),
                    'shipment_name' => $shipmentName,
                    'tracking_no' => $trackingNo,
                    'packages' => $packageList,
                    'is_labellable' => $item->isLabellable(),
                    'is_labellable_reason' => 'Has errors : <ol>' . implode('<li>', $item->getLabelErrors()) . '</ol>',
                ]);
            }

            return [
                'status' => 200,
                'total' => $total,
                'data' => $data,
                'draw' => $request->draw,
                'recordsTotal' => $total,
                'recordsFiltered' => $total,
            ];
        } catch (\Exception $e) {

            reportException($e, true);

            return [
                'status' => 500,
                'error' => $e->getMessage(),
                'debug' => __CLASS__,
            ];
        }
    }
}
