<?php

use App\Libraries\Shippings\RequestInterface;
use App\Libraries\Shippings\Shipping_1\Requests\Request;

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
            "shipmentTrackingNumber" => "123456790",
            "plannedShipDate" => "2020-04-20",
            "accounts" => [
                [
                    "typeCode" => "shipper",
                    "number" => "123456789"
                ]
            ],
            "content" => [
                "exportDeclaration" => [
                    [
                        "lineItems" => [
                            [
                                "number" => 1,
                                "description" => "line item description",
                                "price" => 150,
                                "quantity" => [
                                    "value" => 1,
                                    "unitOfMeasurement" => "BOX"
                                ],
                                "commodityCodes" => [
                                    [
                                        "typeCode" => "outbound",
                                        "value" => "HS1234567890"
                                    ]
                                ],
                                "exportReasonType" => "permanent",
                                "manufacturerCountry" => "CZ",
                                "weight" => [
                                    "netValue" => 10,
                                    "grossValue" => 10
                                ],
                                "isTaxesPaid" => true,
                                "customerReferences" => [
                                    [
                                        "typeCode" => "AFE",
                                        "value" => "customerref1"
                                    ]
                                ],
                                "customsDocuments" => [
                                    [
                                        "typeCode" => "972",
                                        "value" => "custdoc456"
                                    ]
                                ]
                            ]
                        ],
                        "invoice" => [
                            "number" => "12345-ABC",
                            "date" => "2021-03-18",
                            "function" => "import",
                            "customerReferences" => [
                                [
                                    "typeCode" => "CU",
                                    "value" => "custref112"
                                ]
                            ]
                        ],
                        "remarks" => [
                            [
                                "value" => "declaration remark"
                            ]
                        ],
                        "additionalCharges" => [
                        ],
                        "placeOfIncoterm" => "port of departure or destination details",
                        "recipientReference" => "recipient reference",
                        "exporter" => [
                            "id" => "123",
                            "code" => "EXPCZ"
                        ],
                        "exportReasonType" => "permanent",
                        "shipmentType" => "personal",
                        "customsDocuments" => [
                            [
                                "typeCode" => "972",
                                "value" => "custdoc445"
                            ]
                        ],
                        "incoterm" => "DAP"
                    ]
                ],
                "currency" => "EUR",
                "unitOfMeasurement" => "metric"
            ],
            "outputImageProperties" => [
                "imageOptions" => [
                    [
                        "typeCode" => "invoice",
                        "templateName" => "COMMERCIAL_INVOICE_P_10",
                        "isRequested" => true
                    ]
                ]
            ],
            "customerDetails" => [
                "sellerDetails" => [
                    "postalAddress" => [
                        "postalCode" => "14800",
                        "cityName" => "Prague",
                        "countryCode" => "CZ",
                        "provinceCode" => "CZ",
                        "addressLine1" => "V Parku 2308/10",
                        "addressLine2" => "addres2",
                        "addressLine3" => "addres3",
                        "countyName" => "Central Bohemia"
                    ],
                    "contactInformation" => [
                        "email" => "that@before.de",
                        "phone" => "+1123456789",
                        "mobilePhone" => "+60112345678",
                        "companyName" => "Company Name",
                        "fullName" => "John Brew"
                    ],
                    "typeCode" => "business",
                    "registrationNumbers" => [
                        [
                            "typeCode" => "VAT",
                            "number" => "CZ123456789",
                            "issuerCountryCode" => "CZ"
                        ]
                    ]
                ],
                "buyerDetails" => [
                    "postalAddress" => [
                        "postalCode" => "14800",
                        "cityName" => "Prague",
                        "countryCode" => "CZ",
                        "provinceCode" => "CZ",
                        "addressLine1" => "V Parku 2308/10",
                        "addressLine2" => "addres2",
                        "addressLine3" => "addres3",
                        "countyName" => "Central Bohemia"
                    ],
                    "contactInformation" => [
                        "email" => "that@before.de",
                        "phone" => "+1123456789",
                        "mobilePhone" => "+60112345678",
                        "companyName" => "Company Name",
                        "fullName" => "John Brew"
                    ],
                    "registrationNumbers" => [
                        [
                            "typeCode" => "VAT",
                            "number" => "CZ123456789",
                            "issuerCountryCode" => "CZ"
                        ]
                    ],
                    "typeCode" => "business"
                ],
                "importerDetails" => [
                    "postalAddress" => [
                        "postalCode" => "14800",
                        "cityName" => "Prague",
                        "countryCode" => "CZ",
                        "provinceCode" => "CZ",
                        "addressLine1" => "V Parku 2308/10",
                        "addressLine2" => "addres2",
                        "addressLine3" => "addres3",
                        "countyName" => "Central Bohemia"
                    ],
                    "contactInformation" => [
                        "email" => "that@before.de",
                        "phone" => "+1123456789",
                        "mobilePhone" => "+60112345678",
                        "companyName" => "Company Name",
                        "fullName" => "John Brew"
                    ],
                    "registrationNumbers" => [
                        [
                            "typeCode" => "VAT",
                            "number" => "CZ123456789",
                            "issuerCountryCode" => "CZ"
                        ]
                    ],
                    "typeCode" => "business"
                ],
                "exporterDetails" => [
                    "postalAddress" => [
                        "postalCode" => "14800",
                        "cityName" => "Prague",
                        "countryCode" => "CZ",
                        "provinceCode" => "CZ",
                        "addressLine1" => "V Parku 2308/10",
                        "addressLine2" => "addres2",
                        "addressLine3" => "addres3",
                        "countyName" => "Central Bohemia"
                    ],
                    "contactInformation" => [
                        "email" => "that@before.de",
                        "phone" => "+1123456789",
                        "mobilePhone" => "+60112345678",
                        "companyName" => "Company Name",
                        "fullName" => "John Brew"
                    ],
                    "registrationNumbers" => [
                        [
                            "typeCode" => "VAT",
                            "number" => "CZ123456789",
                            "issuerCountryCode" => "CZ"
                        ]
                    ],
                    "typeCode" => "business"
                ]
            ]
        ];
        $this->addAccounts();
    }
};
