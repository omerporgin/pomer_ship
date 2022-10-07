<?php

use App\Libraries\Shippings\RequestInterface;
use App\Libraries\Shippings\Shipping_2\Requests\Request;

return new class($shipping) extends Request implements RequestInterface {

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
            "Shipment" => [
                "ShipmentRatingOptions" => [
                    "UserLevelDiscountIndicator" => "TRUE"
                ],
                "ShipTo" => [
                    "Name" => "Any Name",
                    "Address" => [
                        "CountryCode" => "US"
                    ]
                ],
                "Service" => [
                    "Code" => "08",
                    "Description" => "Standard"
                ],
                "ShipmentTotalWeight" => [
                    "UnitOfMeasurement" => [
                        "Code" => "KGS",
                        "Description" => "Pounds"
                    ],
                    "Weight" => "8"
                ],
                "Package" => [
                    "PackagingType" => [
                        "Code" => "02",
                        "Description" => "Package"
                    ],
                    "Dimensions" => [
                        "UnitOfMeasurement" => [
                            "Code" => "CM"
                        ],
                        "Length" => "10",
                        "Width" => "7",
                        "Height" => "5"
                    ],
                    "PackageWeight" => [
                        "UnitOfMeasurement" => [
                            "Code" => "KGS"
                        ],
                        "Weight" => "8"
                    ]
                ],
                'Shipper' => $this->getShipper()
            ]
        ];

        $this->data['Request'] = [
            "SubVersion" => "1703",
        ];
        $this->data['Shipment']['ShipFrom'] = [
            "Name" => "Billy Blanks",
            "Address" => [
                "AddressLine" => "366 Robin LN SE",
                "City" => "Marietta",
                "StateProvinceCode" => "GA",
                "PostalCode" => "30067",
                "CountryCode" => "TR"
            ]
        ];
    }

    /**
     * @return array
     */
    public function getShipper(): array
    {
        return [
            "Name" => "Billy Blanks",
            "ShipperNumber" => " ",
            "Address" => [
                "AddressLine" => "366 Robin LN SE",
                "City" => "Marietta",
                "StateProvinceCode" => "GA",
                "PostalCode" => "30067",
                "CountryCode" => "TR"
            ]
        ];
    }

};
