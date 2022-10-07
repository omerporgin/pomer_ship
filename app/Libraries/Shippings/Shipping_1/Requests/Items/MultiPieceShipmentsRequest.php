<?php

use App\Libraries\Shippings\RequestInterface;
use App\Libraries\Shippings\Shipping_1\Requests\Request;

return new class($shipping, $order) extends Request implements RequestInterface {

    /**
     * @return array
     */
    public function __construct($shipping, $order)
    {
        if (is_null($order)) {
            $this->setError('order_id required');
        } else {
            $this->order = $order;
            parent::__construct($shipping);
        }
    }

    /**
     * @return array
     */
    public function set()
    {
        $location = $this->order->getLocation();

        $shipperDetails = $this->shipperAddress();
        unset($shipperDetails['countryName']);
        $this->data = [
            "customerDetails" => [
                "shipperDetails" => $shipperDetails,
                'receiverDetails' => [
                    "postalCode" => $this->order->post_code,
                    "cityName" => $location->state->name,
                    "countryCode" => $location->country->iso2,
                    "addressLine1" => $this->order->address,
                ]
            ],
            "payerCountryCode" => self::COUNTRY_CODE,
        ];

        $packages = [];
        foreach ($this->order->packages() as $package) {
            $packages[] =
                [
                    "typeCode" => "3BX", // "3BX","2BC","2BP","CE1","7BX","6BX","4BX","2BX","1CE","WB1","WB3","XPD","8BX","5BX","WB6","TBL","TBS","WB2"
                    "weight" => floatval($package->weight),
                    "dimensions" => [
                        "length" => floatval($package->length),
                        "width" => floatval($package->width),
                        "height" => floatval($package->height)
                    ]
                ];
        }
        $this->data["packages"] = $packages;

        $this->addAccounts();
    }

};
