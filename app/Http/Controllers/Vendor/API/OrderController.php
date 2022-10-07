<?php

namespace App\Http\Controllers\Vendor\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\DatatableRequest;
use App\Libraries\OrderLocation;
use App\Services\OrderStatusService;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Request;
use App\Services\OrderService;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Exception;
use App\Http\Requests\OrderRequest;
use Carbon\Carbon;

class OrderController extends Controller
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

        return response()->view(vendorTheme() . '.orders', [
            'order_status' => $orderStatusList,
            'selectedOrderStatus' => $id,
            'shippings' => $shippings,
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

    /**
     * Store a newly created resource in storage.
     *
     * @param OrderRequest $request
     * @return Response
     */
    public function store(OrderRequest $request)
    {

        $request->request->add([
            'vendor_id' => Auth::id(),
        ]);

        $data = $this->service->filteredRequest($request);

        try {

            $response = $this->service->save($data);
            return response()->json($response, 200);

        } catch (\Exception $e) {

            return response()->json($e->getMessage(), 500);

        }
    }

    /**
     * Created a new resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function create(Request $request)
    {
        $item = service('Order');
        $shippingService = service('Shipping');
        $all = $shippingService->getAll();

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

        $stateService = service('LocationState');
        $list = $stateService->getAll((object)[
            'country_id' => 225,
            'order' => [[
                'column' => 2,
                'dir' => 'asc'
            ]]
        ]);

        return response()->view(vendorTheme('forms.order'), [
            'productList' => [[]],
            'packageList' => [],
            'langsAll' => langsAll(),
            'log' => null,
            'countLog' => 0,
            'data' => null,
            'countData' => 0,
            'orderSelectedShipmentId' => '',
            'labels' => $item->dhlLabels(),
            'order_status' => $orderStatusList,
            'shippings' => $all['list'],
            'location' => null,
            'barcodes' => [],
            'item' => $item,
            'isNew' => true,
            'updatable' => $item->updatable(),
            'stateList' => $list['list'],
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

        $dateList = [];
        $after = 1;
        while ($after < 11) {
            $dateList[] = Carbon::now()->addDays($after);
            $after++;
        }

        $item = service('Order', $id);

        $productList = [];
        $packageList = [];
        $data = null;
        $log = null;
        $countLog = 0;
        $countData = 0;
        $orderSelectedShipmentId = '';

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

        $stateService = service('LocationState');
        $list = $stateService->getAll((object)[
            'country_id' => 225,
            'order' => [[
                'column' => 2,
                'dir' => 'asc'
            ]]
        ]);

        return response()->view(vendorTheme('forms.order'), [
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
            'location' => (new OrderLocation($item))?->getFullName(),
            'barcodes' => $this->barcodes($item->id, count($packageList)),
            'isNew' => false,
            'item' => $item,
            'updatable' => $item->updatable(),
            'pdfFiles' => $pdfFileList,
            'stateList' => $list['list'],
            'dateList' => $dateList,
        ]);
    }

    /**
     * @param $string
     * @return array
     */
    protected function barcodes($string, $count): array
    {
        $dataUri = [];
        $no = 1;
        while ($no < $count + 1) {

            $barcodeText = $string . '.' . $no;

            $result = Builder::create()
                ->writer(new PngWriter())
                ->writerOptions([])
                ->data($barcodeText)
                ->encoding(new Encoding('UTF-8'))
                ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
                ->size(400)
                ->margin(10)
                ->roundBlockSizeMode(new RoundBlockSizeModeMargin())
                //->logoPath('../public/img/admin_icon.png')
                ->labelText('ExporginShipment | ' . $barcodeText)
                ->labelFont(new NotoSans(20))
                ->labelAlignment(new LabelAlignmentCenter())
                ->build();

            // Save it to a file
            $result->saveToFile('../storage/app/public/ex_barcodes/qrcode_' . $string . '.' . $no . '.png');

            // Generate a data URI to include image data inline (i.e. inside an <img> tag)
            $dataUri[] = $result->getDataUri();
            $no++;
        }
        return $dataUri;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param OrderRequest $request
     * @return Response
     */
    public function update(OrderRequest $request, $id)
    {
        $request->request->add([
            'id' => $id,
        ]);
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
