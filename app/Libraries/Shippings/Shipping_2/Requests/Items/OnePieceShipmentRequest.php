<?php

use App\Libraries\Shippings\RequestInterface;
use App\Libraries\Shippings\Shipping_2\Requests\Request;

return new class($shipping, $order) extends Request implements RequestInterface {

    /**
     * @return array
     */
    public function __construct($shipping)
    {
        parent::__construct($shipping);
    }

    /**
     * @return array
     */
    public function set()
    {
        $this->data = [
            "PickupCreationRequest"=> [
                "RatePickupIndicator"=> "N",
                "PickupDateInfo"=> [
                    "CloseTime"=> "1400",
                    "ReadyTime"=> "0500",
                    "PickupDate"=> "20190131"
                ],
                "PickupAddress"=> $this->shipperAddress(),
                "AlternateAddressIndicator"=> "Y",
                "PickupPiece"=> [
                    [
                        "ServiceCode"=> "001",
                        "Quantity"=> "27",
                        "DestinationCountryCode"=> "US",
                        "ContainerCode"=> "01"
                    ],
                    [
                        "ServiceCode"=> "012",
                        "Quantity"=> "4",
                        "DestinationCountryCode"=> "US",
                        "ContainerCode"=> "01"
                    ]
                ],
                "TotalWeight"=> [
                    "Weight"=> "5.5",
                    "UnitOfMeasurement"=> "LBS"
                ],
                "OverweightIndicator"=> "N",
                "PaymentMethod"=> "01",
                "SpecialInstruction"=> "Jias Test ",
                "ReferenceNumber"=> "CreatePickupRefJia",
                "Notification"=> [
                    "ConfirmationEmailAddress"=> "vholloway@ups.com",
                    "UndeliverableEmailAddress"=> "vholloway@ups.com"
                ],
                "CSR"=> [
                    "ProfileId"=> "1 Q83 122",
                    "ProfileCountryCode"=> "US"
                ]
            ]
        ];
        $this->addAccountNumber();
    }

};
