<?php

namespace App\Libraries\Payments\Payment_1\Services;

use function App\Payments\Payment_1\Services\getOrderIdByMerchantOID;

/**
 * merchant Oid ye göre ödeme durumunu çekebileceğimiz bir servis olmadığı için
 * belli bir aralıkta sonuçları getirip sipariş no dan süzerek tek siparişe ulaşıyoruz.
 */
class GetTransaction extends AbstractServices
{

    /**
     * accuracy in minutes
     * @var int
     */
    protected $accuracy = 5;

    /**
     * @var
     */
    protected $startDate;

    /**
     * @var
     */
    protected $endDate;

    /**
     * @var
     */
    protected $orderID = null;

    /**
     * @return object|null
     */
    public function execute(): ?object
    {
        $service = new ProcessList();
        $service->setStartDate($this->startDate);
        $service->setEndDate($this->endDate);

        $result = $service->execute();

        if ($result->status == "error") {
            return $result;
        } else {

            // If we have result than filter
            if (!is_null($this->orderID)) {
                foreach ($result->list as $key => $item) {
                    $itemsOrderID = getOrderIdByMerchantOID($result->list[$key]['siparis_no']);

                    if ($itemsOrderID != $this->orderID) {
                        unset($result->list[$key]);
                    }
                }
            } else {
                return (object)[
                    "status" => "error",
                    "err_msg" => "Order ID is not set.",
                ];
            }
        }
        return $result;
    }

    /**
     * @param string $date
     */
    public function setDate(string $date): bool
    {

        $return = true;
        if ($start = strtotime($date . " -" . $this->accuracy . " minutes")) {
            $this->startDate = date("Y-m-d H:i:s", $start);
        } else {
            $return = false;
        }
        if ($end = strtotime($date . " +" . $this->accuracy . " minutes")) {
            $this->endDate = date("Y-m-d H:i:s", $end);
        } else {
            $return = false;
        }

        return $return;

    }

    /**
     * @param string $date
     */
    public function setOrderID(int $orderID)
    {
        $this->orderID = $orderID;
    }
}
