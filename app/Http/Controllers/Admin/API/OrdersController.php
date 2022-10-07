<?php

namespace App\Http\Controllers\Admin\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\DatatableRequest;
use App\Libraries\OrderLocation;
use App\Services\OrderStatusService;
use Illuminate\Http\Request;
use App\Services\OrderService;
use PHPUnit\Exception;

class OrdersController extends Controller
{
    /**
     * @var service
     */
    protected $service;

    /**
     * @var imageService
     */
    protected $imageService;

    public function __construct(OrderService $service)
    {
        $this->service = $service;
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function index($id = null)
    {
        // Get order status list
        $orderStatus = (new OrderStatusService)->getAllFiltered((object)[
            'status_of' => 1,
            'search' => [
                'value' => ''
            ]
        ]);
        $orderStatusList = $orderStatus[0]->get();

        $shippingService = service('Shipping');
        $shippings = $shippingService->getAll();

        return response()->view(adminTheme() . '.orders', [
            'order_status' => $orderStatusList,
            'selectedOrderStatus' => $id,
            'shippings' => $shippings,
        ]);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function indexAdmin($id = null)
    {
        // Get order status list
        $orderStatus = (new OrderStatusService)->getAllFiltered((object)[
            'status_of' => 1,
            'search' => ['value' => '']
        ]);
        $orderStatusList = $orderStatus[0]->get();

        return response()->view(adminTheme() . '.orders', [
            'order_status' => $orderStatusList,
            'selectedOrderStatus' => $id
        ]);
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
    public function indexAjax(DatatableRequest $request)
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

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show(int $id, Request $request)
    {
        $item = service('Order', $id);

        $productList = [];
        $packageList = [];
        $data = null;
        $log = null;
        $countLog = 0;
        $countData = 0;
        $orderSelectedShipmentId = '';

        $orderLocation = new OrderLocation($this->service);
        $location = $orderLocation->getFullName();

        $products = $item->products();
        foreach ($products as $product) {
            $productList[$product['package_id']][] = $product;
            $packageService = service('OrderPackage', $product['package_id']);
            $orderSelectedShipmentId = $packageService->shipment_id;
            $packageList[$product['package_id']] = $packageService->get();
        }

        // Pdf files
        $pdfFileList = [];
        foreach ($packageList as $packageId => $notRequired) {
            foreach (glob('../storage/app/public/barcodes/' . $packageId . '_*') as $file) {
                $pdfFileList[] = basename($file);
            }
        }

        try {
            $data = json_decode($item->data, true);
            if (!is_null($data)) {
                $countData = count($data);
            }

            $log = json_decode($item->log, true);
            if (!is_null($log)) {
                $countLog = count($log);
            }
        } catch (Exception $e) {
            //
        }

        /**
         * array_reverse() kullanılırsa package_id kaybolur!
         */
        $orderStatus = (new OrderStatusService)->getAllFiltered((object)[
            'status_of' => 1,
            'search' => [
                'value' => ''
            ]
        ]);
        $orderStatusList = $orderStatus[0]->get();

        $shippingService = service('Shipping');
        $all = $shippingService->getAll();

        return response()->view(adminTheme('forms.order'), [
            'productList' => $productList,
            'packageList' => $packageList,
            'langsAll' => langsAll(),
            'log' => $log,
            'countLog' => $countLog,
            'data' => $data,
            'countData' => $countData,
            'orderSelectedShipmentId' => $orderSelectedShipmentId,
            'labels' => $item->dhlLabels(),
            'order_status' => $orderStatusList,
            'shippings' => $all['list'],
            'location' => $location,
            'barcodes' => [],
            'isNew' => false,
            'item' => $item,
            'updatable' => $item->updatable(),
            'pdfFiles' => $pdfFileList,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $requiredFields = $this->service::requiredFields();
        $data = $request->only($requiredFields);

        try {
            $response = $this->service->save($data);
        } catch (\Exception $e) {

            $errorList = $e->getMessage();
            if (!is_array($errorList)) {
                $errorList = [$errorList];
            }

            return response()->json($errorList, 500);
        }
        return response()->json($response, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->id = $id;
        $request->request->add(['id' => $id]);
        return $this->store($request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
