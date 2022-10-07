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
        $shipperDetails = $this->shipperAddress();
        unset($shipperDetails['countryName']);

        $receiverDetails = $this->orderAddress();
        unset($receiverDetails['countryName']);
        $this->data = [
            "customerDetails" => [
                "shipperDetails" => $shipperDetails,
                "receiverDetails" => $receiverDetails
            ],
            "productCode" => "P",
            "localProductCode" => "P",
            "unitOfMeasurement" => "metric",
            "currencyCode" => "CZK",
            "isCustomsDeclarable" => true,
            "isDTPRequested" => true,
            "isInsuranceRequested" => false,
            "getCostBreakdown" => true,
            "charges" => [
                [
                    "typeCode" => "insurance",
                    "amount" => 1250,
                    "currencyCode" => "CZK"
                ]
            ],
            "shipmentPurpose" => "personal",
            "transportationMode" => "air",
            "merchantSelectedCarrierName" => "DHL",
            "packages" => [
                [
                    "typeCode" => "3BX",
                    "weight" => 10.5,
                    "dimensions" => [
                        "length" => 25,
                        "width" => 35,
                        "height" => 15
                    ]
                ]
            ],
            "items" => [
                [
                    "number" => 1,
                    "name" => "KNITWEAR COTTON",
                    "description" => "KNITWEAR 100% COTTON REDUCTION PRICE FALL COLLECTION",
                    "manufacturerCountry" => "CN",
                    "partNumber" => "12345555",
                    "quantity" => 2,
                    "quantityType" => "prt",
                    "unitPrice" => 120,
                    "unitPriceCurrencyCode" => "EUR",
                    "customsValue" => 120,
                    "customsValueCurrencyCode" => "EUR",
                    "commodityCode" => "6110129090",
                    "weight" => 5,
                    "weightUnitOfMeasurement" => "metric",
                    "category" => "204",
                    "brand" => "SHOE 1",
                    "goodsCharacteristics" => [
                        [
                            "typeCode" => "IMPORTER",
                            "value" => "Registered"
                        ]
                    ],
                    "additionalQuantityDefinitions" => [
                        [
                            "typeCode" => "DPR",
                            "amount" => 2
                        ]
                    ],
                    "estimatedTariffRateType" => "default_rate"
                ]
            ],
            "getTariffFormula" => true,
            "getQuotationID" => true
        ];

        $packages = [];
        $lineItems = [];

        foreach ($this->order->packages() as $package) {

            $currentPackage = [
                "weight" => floatval($package->weight),
                "dimensions" => [
                    "length" => floatval($package->length),
                    "width" => floatval($package->width),
                    "height" => floatval($package->height)
                ],
            ];
            if (strlen($package->description) > 0) {
                $currentPackage["description"] = $package->description;
            }

            $packages[] = $currentPackage;
            $count = 1;
            foreach ($this->order->packageProducts($package->id) as $product) {
                $lineItems[] = [
                    "number" => $count,
                    "description" => $product->name,
                    "price" => floatval($product->unit_price),
                    "quantity" => [
                        "value" => $product->declared_quantity,
                        "unitOfMeasurement" => "BOX"
                    ],
                    "commodityCodes" => [
                        [
                            "typeCode" => "outbound",
                            "value" => "HS" . str_replace(".", "", $product->gtip_code) // Gtip kodu burası olmalı
                        ]
                    ],
                    "exportReasonType" => "permanent",
                    "manufacturerCountry" => "TR",
                    "exportControlClassificationNumber" => "US123456789",
                    "weight" => [
                        "netValue" => 10,
                        "grossValue" => 10
                    ],
                    "isTaxesPaid" => true,
                    "additionalInformation" => [
                        "string"
                    ],
                ];
                $count++;
            }
        }

        $this->data['packages'] = $packages;
        $this->addAccounts();
    }
};
