<?php

namespace App\Services;

use App\Events\OrderUpdated;
use App\Models\Order as Item;
use App\Models\OrderProducts;
use App\Libraries\OrderLocation;
use App\NotificationService\NotificationService;
use App\Libraries\Shippings\Factory;

class OrderService extends abstractService
{

    /**
     * @var ?OrderLocation
     */
    protected $location;

    /**
     * @var array
     */
    protected $labelErrors = [];

    /**
     * Repository constructor.
     */
    public function __construct(int $id = null)
    {
        $this->setItem(new Item);
        parent::__construct($id);
    }

    public function orderProducts()
    {
        return $this->item->orderProducts();
    }

    /**
     * @return bool
     */
    public function deletable(int $id = null): bool
    {
        $this->deletableMsg = 'Item not deletable';
        return false;
    }

    /**
     * @return bool
     */
    public function updatable(int $id = null): bool
    {
        $this->updatableMsg = 'Item not updatable';
        return true;
    }

    /**
     * Create table data for item.
     *
     * @param object $filters
     * @return array
     */
    public function getAllFiltered(object $filters): array
    {

        $columns = ['id', 'real_status', 'entegration_id', 'email', 'firstname', '', ''];

        $list = $this->item;
        if (isset($filters->userType) and $filters->userType == 'vendors') {
            $list = $list->where('vendor_id', Auth()->id());
        } else {
            // If admin is calling
        }

        if (isset($filters->order_statuses)) {
            $list = $list->whereIn('real_status', $filters->order_statuses);
        }

        if (isset($filters->vendorList) and !empty($filters->vendorList)) {
            $list = $list->whereIn('vendor_id', $filters->vendorList);
        }

        $value = $filters->search['value'];

        // Search
        if ($value != '') {
            $search_text = explode(' ', $value);
            foreach ($search_text as $text) {
                $list = $list->where(function ($list) use ($text) {
                    $list
                        ->orWhere('id', $text);
                });
            }
        }

        return [$list, $columns];
    }

    /**
     * Entegrasyon ile gelen yeni siparişlerin kaydı.
     *
     * @param object $data
     * @return array
     */
    public function saveNew(object $data)
    {

        if (is_null(Item::where('order_id', $data->order_id)->first())) {
            return $this->save($data);
        } else {
            $this->setError('Order already exist : ' . $data->order_id);
            return [
                'result' => false,
                'item' => $this->item,
                'id' => $this->id,
            ];
        }
    }

    /**
     * Save order
     *
     * @param array|object $data
     * @return array
     */
    public function save($data): array
    {

        if (is_array($data)) {
            $data = (object)$data;
        }

        if (!isset($data->id)) {
            $data->order_date = date("Y-m-d H:i:s");
        }

        // Both country_id, state_id will be null
        $locationCityService = service('LocationCity', $data->city_id);

        if (is_null($data->state_id)) {
            $data->state_id = $locationCityService->state_id;
        }
        if (is_null($data->country_id)) {
            $data->country_id = $locationCityService->country_id;
        }
        $data->post_code = substr(trim($data->post_code), 0, 10); // Must not be grater than 10
        $data->phone = preg_replace('/\D/', '', $data->phone); // Only numbers

        // On this occasions we must update
        if (!is_null($order = Item::where('vendor_id', $data->vendor_id)
            ->where('entegration_id', $data->entegration_id)
            ->where('order_id', $data->order_id)
            ->first())) {
            if (isset($data->id)) {
                NotificationService::notify('Order id overwritten (' . $data->id . ')');
            }
            $data->id = $order->id;

            // Combine old-new Data
            if (!empty($data->data)) {
                $newData = json_decode($order->data, true);
                $newData = array_merge($newData, $data->data);
                $newData = json_encode($newData);
                if (strlen($newData) < 65535) {
                    $data->data = $newData;
                } else {
                    $data->data = $order->data;
                    NotificationService::notify('Order Id:', $data->id . ' için order datası çok uzun. Data yazılamıyor.');
                }
            }
        } else {
            if (isset($data->data)) {
                $newData = json_encode($data->data);
                if (strlen($newData) < 65535) {
                    $data->data = $newData;
                } else {
                    NotificationService::notify('Order Id:', $data->id . ' için order datası çok uzun. Data yazılamıyor.');
                }
            }
        }

        $return = parent::save($data);

        event(new OrderUpdated($return));

        $orderID = $return['id'];

        // Products save or updates
        $productIdList = [];
        $packages = [];
        foreach ($data->package_id as $key => $package_id) {
            if ($package_id == 0) {
                $packageService = new OrderPackageService();
            } else {
                $packageService = new OrderPackageService($package_id);
            }

            $package = $packageService->get();
            if (!is_null($packageService->getID())) {
                $packageService->setID($package_id);
            }

            $package->id = $package_id;
            $package->order_id = $orderID;
            $package->width = $data->package_width[$key];
            $package->height = $data->package_height[$key];
            $package->length = $data->package_length[$key];
            $package->weight = $data->package_weight[$key];
            if (isset($data->shipment_id)) {
                $package->shipment_id = $data->shipment_id;
            }
            $package->save();
            $packages[] = $package->id;
        }

        $packageList = explode(',', $data->package_list);


        $currentPackage = 0;
        $countProducts = 1;

        foreach ($data->product_id as $key => $val) {
            $orderProduct = new OrderProductService($val);
            $orderProduct = $orderProduct->get();
            $orderProduct->order_id = $orderID;
            $orderProduct->name = $data->name[$key];
            $orderProduct->type = $data->type[$key];
            $orderProduct->quantity = $data->quantity[$key];
            $orderProduct->declared_quantity = $data->quantity[$key];
            $orderProduct->unit_price = $data->unit_price[$key];
            $orderProduct->total_price = $data->unit_price[$key] * $data->quantity[$key];
            $orderProduct->gtip_code = $data->gtip_code[$key];
            $orderProduct->sku = $data->sku[$key];

            $orderProduct->unique_id = isset($data->unique_id[$key]) ? $data->unique_id[$key] : null;
            $orderProduct->width = isset($data->product_width[$key]) ? $data->product_width[$key] : null;
            $orderProduct->height = isset($data->product_height[$key]) ? $data->product_height[$key] : null;
            $orderProduct->length = isset($data->product_length[$key]) ? $data->product_length[$key] : null;
            $orderProduct->desi = isset($data->product_desi[$key]) ? $data->product_desi[$key] : null;
            $countProducts++;

            $orderProduct->sort = $countProducts;
            $orderProduct->package_id = $packages[$currentPackage];

            $orderProduct->save();
            $productIdList[] = $orderProduct->id;

            // Sort and package_id are related to each other
            if ($countProducts > $packageList[$currentPackage]) {
                $countProducts = 1;
                $currentPackage += 1;
            }
        }

        // Delete other products of not in list
        $orderProduct = new OrderProductService($val);
        $orderProduct = $orderProduct->get();
        $orderProduct->whereNotIn('id', $productIdList)->where('order_id', $orderID)->delete();


        return $return;
    }

    /**
     * @return void
     */
    public static function addLog($oldLog, $newLog): string
    {
        $currentLog = [];
        if (!is_null($oldLog)) {
            try {
                $currentLog = json_decode($oldLog, true);
            } catch (\Exception $e) {

            }
        }
        $currentLog[] = $newLog;
        return json_encode($currentLog);
    }

    /**
     * @param $log
     * @return bool
     */
    public function newLog($log): bool
    {
        if ($this->hasItem()) {
            $this->item->log = self::addLog($this->item->log, $log);
            return $this->item->save();
        }
        return false;
    }

    /**
     * @return OrderLocation
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function getLocation(): OrderLocation
    {
        if (is_null($this->location)) {
            $this->location = app()->make(OrderLocation::class, [
                'order' => $this
            ]);
        }
        return $this->location;
    }

    /**
     * @return mixed
     */
    public function packages()
    {
        return $this->item->orderPackages()->get();
    }

    /**
     * @return mixed
     */
    public function packageProducts(int $packageID)
    {
        return OrderProducts::where('package_id', $packageID)->where('type', 0)->get(); // Only products
    }

    /**
     * @param bool $withApi -> if true checks api for the destination
     * @return bool
     */
    public function ifLocationIsEnough(bool $withApi = false): bool
    {
        if (!$this->hasItem()) {
            return false;
        }

        if (is_null($this->country_id)) {
            $this->labelErrors[] = 'country_id required';
            return false;
        }

        if (is_null($this->post_code)) {
            $this->labelErrors[] = 'post_code required';
            return false;
        } else {

            if ($withApi) {
                // Check if dhl has delivery
                if (!is_null($item = Factory::shipping(1))) {
                    $country = service('LocationCountry', $this->country_id);
                    if (!$country->hasItem()) {
                        return response()->json([
                            'err' => 'Select country',
                        ], 500);
                    }

                    if ($item->validateAddress([
                        'type' => 'delivery',
                        'countryCode' => $country->iso2,
                        'postalCode' => $this->post_code,
                        //'cityName'=>'BALIKESIR',
                        'strictValidation' => 'true',
                    ])) {
                        $response = $item->response();
                    } else {
                        foreach ($item->getErrorList() as $err) {
                            $this->labelErrors[] = $err;
                        }
                        return false;
                    }
                } else {
                    $this->labelErrors[] = 'Cant check if dhl has delivery.';
                    return false;
                }
            }
        }

        if (is_null($this->state_id)) {
            $this->labelErrors[] = 'state_id required';
            return false;
        }

        /*
        if (is_null($this->city_id)) {
            $this->labelErrors[] = 'city_id required';
            return false;
        }
        */

        return true;
    }

    /**
     * @return mixed
     */
    public function isLabellable(): bool
    {
        if (!$this->hasItem()) {
            return false;
        }

        if ($this->item->real_status != 13) {
            $this->labelErrors[] = 'Order status must be initialized.';
            return false;
        }

        if ($this->ifLocationIsEnough()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Return label errors
     *
     * @return array
     */
    public function getLabelErrors()
    {
        return $this->labelErrors;
    }

    /**
     * Return label errors
     *
     * @param int $newStatus
     * @return bool
     */
    public function changeStatus(int $newStatus): bool
    {
        $this->item->real_status = $newStatus;
        return $this->item->save();
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->item->user()->first();
    }

    /**
     * @param $packageList
     * @return array
     */
    public function dhlLabels($packageList = null)
    {
        if (is_null($packageList)) {
            $packageList = $this->packages();
        }
        $labels = [];
        foreach ($packageList as $package) {
            $pdfRealFile = '../storage/app/public/barcodes/' . $package->id . '_0.pdf';
            $pdfFile = './storage/barcodes/' . $package->id . '_0.pdf';
            if (file_exists($pdfRealFile)) {
                $labels[] = $pdfFile;
            }
        }

        return $labels;
    }

    public function products()
    {
        $productService = service('OrderProduct');
        $products = $productService->getByOrderID($this->id);
        $products = $products->toArray();
        return $products;
    }
}
