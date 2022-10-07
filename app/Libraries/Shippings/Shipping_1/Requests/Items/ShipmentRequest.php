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
        $this->setData();
        $this->addAccounts();
        $this->addReceiverDetails();
    }

    /**
     * @return array
     */
    public function setData()
    {
        $this->data = [
            "plannedShippingDateAndTime" => $this->dateToGmt($this->daysAfter(1)),
            "pickup" => [
                "isRequested" => false,
                "closeTime" => "18:00",
                "location" => "reception",
                "pickupDetails" => $this->shipperDetails()
            ],
            "productCode" => "P",
            "localProductCode" => "P",
            "getRateEstimates" => false,
            "customerDetails" => [
                "shipperDetails" => $this->shipperDetails(),
            ],
            "content" => $this->content(),
            "getTransliteratedResponse" => false,
        ];

        $this->addAccountNumber();
    }

    /**
     * @param $address
     * @return array
     */
    private function parseAddress($address): array
    {
        $addressLines = [1 => '', 2 => '', 3 => ''];
        $key = 1;
        $address = explode(" ", $address);
        foreach ($address as $line) {
            if (strlen($addressLines[$key] . ' ' . $line) > 45) {
                $key += 1;
            }
            $addressLines[$key] .= ' ' . $line;
        }
        return $addressLines;
    }

    /**
     * @param $order
     */
    public function addReceiverDetails()
    {
        $location = $this->order->getLocation();
        $countryCode = $location->country->iso2;

        $return = [
            "postalAddress" => [
                "postalCode" => $this->order->post_code,
                "cityName" => $location->state->name,
                "countryCode" => $countryCode,
                "provinceCode" => $countryCode,
                "countyName" => $location->city->name,
                "countryName" => $location->country->name
            ],
            "contactInformation" => [
                "email" => $this->order->email,
                "phone" => $this->order->phone,
                "mobilePhone" => $this->order->phone,
                "fullName" => $this->order->firstname . " " . $this->order->lastname,
                "companyName" => "Company Name", // Must not be required, but it is
            ],
            "typeCode" => "direct_consumer"
        ];

        foreach ($this->parseAddress($this->order->address) as $key => $line) {
            if (!empty($line)) {
                $return['postalAddress']['addressLine' . $key] = $line;
            }
        }
        $this->data["customerDetails"]["receiverDetails"] = $return;
        $this->data["shipmentNotification"] = [
            [
                "typeCode" => "email",
                "receiverId" => $this->order->email,
                "languageCode" => "eng",
                "languageCountryCode" => $countryCode,
                "bespokeMessage" => "Your packeage is shipped."
            ]
        ];
    }

    /**
     * Ürünler ve ürünler adına kesilen tek fatura.
     *
     * @return array
     */
    protected function content()
    {

        $return = [
            "isCustomsDeclarable" => true, // Vergiye tabi mi?
            "declaredValue" => 150,
            "declaredValueCurrency" => "EUR",
            "exportDeclaration" => [
                "invoice" => [
                    "number" => "12345-ABC",
                    "date" => "2020-03-18",
                    "signatureName" => "Brewer",
                    "signatureTitle" => "Mr.",
                    "instructions" => [
                        "string"
                    ],
                    "customerDataTextEntries" => [
                        "string"
                    ],
                    "totalNetWeight" => 9,
                    "totalGrossWeight" => 9,
                    "customerReferences" => [
                        [
                            "typeCode" => "CU",
                            "value" => "custref112"
                        ]
                    ],
                    "termsOfPayment" => "100 days"
                ],
                "remarks" => [
                    [
                        "value" => "declaration remark"
                    ]
                ],
                "additionalCharges" => [

                ],
                "payerVATNumber" => "12345ED",
                "recipientReference" => "recipient reference",
                "exporter" => [
                    "id" => "123",
                    "code" => "EXPCZ"
                ],
                "packageMarks" => "marks",
                "declarationNotes" => [
                    [
                        "value" => "up to three declaration notes"
                    ]
                ],
                "exportReference" => "export reference",
                "exportReason" => "export reason",
                "exportReasonType" => "permanent",
                "licenses" => [
                    [
                        "typeCode" => "export",
                        "value" => "license"
                    ]
                ],
                "shipmentType" => "personal",
                "customsDocuments" => [
                    [
                        "typeCode" => "972",
                        "value" => "custdoc445"
                    ]
                ]
            ],
            "description" => "shipment description",
            "USFilingTypeValue" => "12345",
            "incoterm" => "DAP", // Adrese teslim
            "unitOfMeasurement" => "metric"
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

        $return['packages'] = $packages;
        $return['exportDeclaration']['lineItems'] = $lineItems;

        return $return;
    }

    /**
     * Our address details.
     *
     * @return string[]
     */
    public function shipperDetails(): array
    {
        return
            [
                "postalAddress" => [
                    "postalCode" => "34880",
                    "cityName" => "ISTANBUL",
                    "countryCode" => "TR",
                    "provinceCode" => "TR",
                    "addressLine1" => "Yakacık D-100 Kuzey Yanyol No:49 D:1", // max:45
                    "countyName" => "Kartal",
                    "countryName" => "Turkey"
                ],
                "contactInformation" => [
                    "email" => "destek@exporgin.com",
                    "phone" => "+9008508851070",
                    "mobilePhone" => "+9008508851070",
                    "companyName" => "exporgin",
                    "fullName" => "ALİ YETKİN ŞEKEROĞLU"
                ],
                "typeCode" => "business"
            ];
    }
};
