<?php

namespace App\Http\Controllers\Vendor\Order;

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
        $dateList = [];
        $after = 1;
        while ($after < 11) {
            $dateList[] = Carbon::now()->addDays($after);
            $after++;
        }

        $item = service('Order');

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

        $currencyService = service('Currency');

        $userGroupService = service('UserGroup', Auth::user()->user_group_id);

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
            'location' => null,
            'barcodes' => [],
            'item' => $item,
            'isNew' => true,
            'updatable' => $item->updatable(),
            'stateList' => $list['list'],
            'dateList' => $dateList,
            'currencies' => $currencyService->getActiveItems(),
            'serviceNames' => $userGroupService->serviceList(),
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

        $stateService = service('LocationState');
        $list = $stateService->getAll((object)[
            'country_id' => 225,
            'order' => [[
                'column' => 2,
                'dir' => 'asc'
            ]]
        ]);

        $currencyService = service('Currency');

        $userGroupService = service('UserGroup', Auth::user()->user_group_id);

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
            'location' => (new OrderLocation($item))?->getFullName(),
            'barcodes' => $this->barcodes($item->id, count($packageList)),
            'isNew' => false,
            'item' => $item,
            'updatable' => $item->updatable(),
            'pdfFiles' => $pdfFileList,
            'stateList' => $list['list'],
            'dateList' => $dateList,
            'currencies' => $currencyService->getActiveItems(),
            'serviceNames' => $userGroupService->serviceList(),
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
