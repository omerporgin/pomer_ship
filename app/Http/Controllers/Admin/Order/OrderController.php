<?php

namespace App\Http\Controllers\Admin\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\DatatableRequest;
use App\Libraries\OrderLocation;
use App\Services\OrderStatusService;
use Illuminate\Http\Request;
use App\Services\OrderService;
use PHPUnit\Exception;

class OrderController extends Controller
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
