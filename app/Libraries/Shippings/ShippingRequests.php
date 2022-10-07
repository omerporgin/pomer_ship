<?php

namespace App\Libraries\Shippings;

use App\Traits\ErrorTrait;

class ShippingRequests
{
    use ErrorTrait;

    /**
     * required
     *
     * 2019-08-04T14:00:31GMT+01:00
     *
     * @var string
     */
    public string $plannedShippingDateAndTime = '2022-08-04T14:00:31GMT+03:00';

    public $data = [];

    /**
     * @var
     */
    public $shipping;

    public const COUNTRY_CODE = 'TR';
    public const POSTAL_CODE = '34880';
    public const CITY_NAME = "ISTANBUL";

    /**
     *
     */
    public function __construct()
    {
    }

    /**
     * @return array
     */
    public function build(?array $newData = null): array
    {
        if (!is_null($newData)) {
            $this->data = array_merge($this->data, $newData);
        }
        return $this->data;
    }

    /**
     * @param string $plannedShippingDateAndTime
     * @return string
     */
    public function dateToGmt(string $plannedShippingDateAndTime): string
    {
        $gmDate = str_replace(" ", "T", date("Y-m-d H:i:s", strtotime($plannedShippingDateAndTime)));
        return $gmDate . 'GMT+03:00';
    }

    /**
     * @return string
     */
    public function daysAfter($days = 1)
    {
        $date = date('Y-m-d H:i:s', strtotime(date("Y-m-d H:i:s") . ' +' . $days . ' day'));
        $gmDate = str_replace(" ", "T", date("Y-m-d H:i:s", strtotime($date))) . 'GMT+03:00';

        return $gmDate;
    }

}
