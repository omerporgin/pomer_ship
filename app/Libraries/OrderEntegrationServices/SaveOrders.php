<?php

namespace App\Libraries\OrderEntegrationServices;

use App\NotificationService\NotificationService;
use App\Services\OrderPackageService;
use App\Services\OrderProductService;
use App\Services\OrderService;
use Illuminate\Support\Facades\Auth;
use function app;

class SaveOrders
{

    /**
     * @var array
     */
    protected $err = [];

    /**
     * Try to save given order list
     *
     * @param $orderList
     * @return int
     */
    public function saveAll($orderList): int
    {
        $sumSavedItems = 0;
        foreach ($orderList as $order) {

            if ($this->saveOne($order)) {
                $sumSavedItems++;
            }
        }
        return $sumSavedItems;
    }

    /**
     * Entegration service use this method to save orders
     *
     * @param $data
     * @return void
     */
    public function saveOne($data): bool
    {

        try {
            $vendorID = Auth::id();
            $isNew = true;

            $data->vendor_id = $vendorID;
            $currency = service('Currency');
            $data->currency = $currency::getCurrencyIdByCode($data->secondary_currency);
            $data->phone = $data->s_phone;

            // Save all data
            $data->data[date("Y-m-d H:i:s")] = (array)$data;

            $data->shipment_id = null;
            //$data->package_list = 'new';
            $orderService = app()->make(OrderService::class);

            if ($orderService->saveNew($data)) {

            } else {
                NotificationService::notify('Order', $order);
                return false;
            }
        } catch (\Exception $e) {
            $this->setError($e->getMessage());
            reportException($e, true);
            return false;
        }
        return false;
    }

    /**
     * @param string $errMsg
     * @return void
     */
    protected function setError(string $errMsg): void
    {
        $this->err[] = $errMsg;
    }

    /**
     * @return string
     */
    public function getErrorList(): array
    {
        return $this->err;
    }
}
