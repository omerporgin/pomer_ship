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
                    "Address" => [
                        "PostalCode" => "75000",
                        "CountryCode" => "US"
                    ]
                ],
                "Service" => [
                    "Code" => "07"
                ],
                "ShipmentTotalWeight" => [
                    "UnitOfMeasurement" => [
                        "Code" => "KGS", // 00= KG (Metric Unit of Measurements) or KGS 01 = LB or LBS
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
                            "Code" => "CM" // IN, CM
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
                'Shipper' => [
                    "Address" => [
                        "CountryCode" => self::COUNTRY_CODE,
                        "PostalCode" => self::POSTAL_CODE,
                    ]
                ],
                'ShipFrom' => [
                    "Address" => [
                        "CountryCode" => self::COUNTRY_CODE,
                        "PostalCode" => self::POSTAL_CODE,
                    ]
                ]
            ]
        ];

        $this->data['Request'] = [
            "SubVersion" => "1703",
        ];
    }

    public function setData($data){

    }

    /**
     * Returns multiple results for each
     *
     *  [
     *      ['Code' => '07', 'Description' => 'UPS Worldwide Express'],
     *      ['Code' => '08', 'Description' => 'UPS Worldwide Expedited'],
     *      ['Code' => '65', 'Description' => 'UPS Worldwide Saver'],
     * ]
     *
     * @param array|null $newData
     * @return array
     */
    public function build(?array $newData = null): array
    {
        $this->data['Shipment']['ShipTo']['Address']['CountryCode'] = $newData['destinationCountryCode'] ?? '';
        $this->data['Shipment']['ShipTo']['Address']['PostalCode'] = $newData['destinationPostalCode'] ?? '';
        $this->data['Shipment']['Package']['PackageWeight']['Weight'] = strVal($newData['weight']);
        $this->data['Shipment']['Package']['Dimensions']['Length'] = strVal($newData['length']);
        $this->data['Shipment']['Package']['Dimensions']['Width'] = strVal($newData['width']);
        $this->data['Shipment']['Package']['Dimensions']['Height'] = strVal($newData['height']);

        $this->data['Shipment']['Service'] = $newData['service'];
        $this->data['Shipment']['ShipmentTotalWeight']['Weight'] =  strVal($newData['weight']);

        return $this->data;
    }

};
