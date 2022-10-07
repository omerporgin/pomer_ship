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
            parent::__construct($shipping, $order);
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
            "plannedShippingDateAndTime" => $this->dateToGmt($this->order->shipped_at),

            /**
             * Burası önemli. Servis burada belirtiliyor :
             *
             * K TDK Express 9.00
             * T TDT Express 12.00
             * Y TDY Express 12.00
             * E TDE Express 9.00
             * P WPX Express Wordwilde
             * U ECX Express Wordwilde (EU)
             * D DOX Wordwilde
             * N DOM Domestic Express
             * H ESI Economy select
             * W ESU Economy select (EU)
             */

            "productCode" => "P",
            "localProductCode" => "P",
            "getRateEstimates" => false,
            "customerDetails" => [
                "shipperDetails" => $this->shipperDetails(),
            ],
            "content" => $this->content(),
            "getTransliteratedResponse" => false,

            "outputImageProperties" => [
                "encodingFormat" => "pdf",
                "imageOptions" => [[
                    "invoiceType" => "commercial",
                    "templateName" => "COMMERCIAL_INVOICE_P_10",
                    "isRequested" => true,
                    "typeCode" => "invoice",
                ], [
                    "hideAccountNumber" => false,
                    "isRequested" => true,
                    "typeCode" => "waybillDoc"
                ], [
                    "templateName" => "ECOM26_84_A4_001",
                    "typeCode" => "label"
                ]]
            ],
            "valueAddedServices" => [[
                "serviceCode" => "WY"
            ]],
        ];

        $this->addDocuments();
        $this->pickup();
        $this->addAccounts();
    }

    protected function addDocuments()
    {

        $path = $this->order->document_invoice;
        $type = pathinfo($path, PATHINFO_EXTENSION);

        $data = file_get_contents($path);
        // $base64Invoice = 'data:image/' . $type . ';base64,' . base64_encode($data);
        $base64Invoice = base64_encode($data);

        $path = $this->order->document_custom;
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        // $base64CommericalInvoice = 'data:image/' . $type . ';base64,' . base64_encode($data);
        $base64CommericalInvoice = base64_encode($data);


        $this->data['documentImages'] = [[
            "typeCode" => "CIN", // "INV","PNV","COO","CIN","DCL","AWB","NAF"
            "imageFormat" => "PDF",
            "content" => $base64Invoice, // Base64 encoded image
        ], [
            "typeCode" => "NAF", // "INV","PNV","COO","CIN","DCL","AWB","NAF"
            "imageFormat" => "PDF",
            "content" => $base64CommericalInvoice, // Base64 encoded image
        ]];
    }

    /**
     *
     * Kargo merkezi olmalı.
     *
     * @return void
     */
    protected function pickup(): void
    {
        $pickupData = [
            "isRequested" => ($this->order->has_pickup == 0 ? false : true)
        ];
        if ($this->order->has_pickup == 1) {
            if ($this->order->has_diffrent_pickup_address == 1) {
                $pickupData['pickupDetails'] = $this->pickupAddressDetails();
            } else {
                $pickupData['pickupDetails'] = $this->shipperDetails();
                $pickupData['closeTime'] = $this->user->warehouse_closed_at;
                $pickupData['location'] = $this->user->warehouse_location;
            }
        }
        $this->data["pickup"] = $pickupData;
    }

    protected function pickupAddressDetails(): array
    {
        $typeCode = "business";

        if ($this->user->user_type == 0) {
            $typeCode = "private"; // Kişisel şahıs şirketi.
        }

        $return = [
            "postalAddress" => $this->pickupAddress(),
            "contactInformation" => [
                "email" => $this->user->email,
                "phone" => strval($this->user->warehouse_phone),
                "mobilePhone" => strval($this->user->warehouse_phone),
                "companyName" => $this->user->real_company_name,
                "fullName" => $this->user->real_owner_name,
            ],
            "typeCode" => $typeCode // "business","direct_consumer","government","other","private","reseller"
        ];

        return $return;
    }

    /**
     * @param $address
     * @return array
     */
    private function parseAddress($address): array
    {
        $addressLines = [
            1 => '',
            2 => '',
            3 => ''
        ];
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

        $return = [
            "postalAddress" => $this->orderAddress(),
            "contactInformation" => [
                "email" => $this->order->email,
                "phone" => strval($this->order->phone),
                "mobilePhone" => strval($this->order->phone),
                "fullName" => $this->order->firstname . " " . $this->order->lastname,
                "companyName" => $this->order->firstname . " " . $this->order->lastname, // Must not be required, but it is
            ],
            "typeCode" => "direct_consumer" // "business","direct_consumer","government","other","private","reseller"
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
                "languageCountryCode" => 'EN',
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
            "declaredValue" => $this->order->declared_value,
            "declaredValueCurrency" => "EUR",

            "exportDeclaration" => [
                "invoice" => [
                    "number" => $this->order->invoice_no,
                    "date" => date("Y-m-d", strtotime($this->order->order_date)),
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
            ],
            "incoterm" => "DAP", // Adrese teslim
            "unitOfMeasurement" => "metric",
        ];

        if (!is_null($this->order->description)) {
            $return["description"] = $this->order->description;
        }
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
                $desi = $product->desi > 0 ? floatval($product->desi) : 0.002;
                $lineItems[] = [
                    "number" => $count,
                    "description" => $product->name,
                    "price" => floatval($product->unit_price * $product->declared_quantity),
                    "quantity" => [
                        "value" => $product->declared_quantity,
                        "unitOfMeasurement" => "BOX"
                    ],
                    "commodityCodes" => [
                        [
                            "typeCode" => "outbound", // "outbound","inbound"
                            "value" => "HS" . str_replace(".", "", $product->gtip_code) // Gtip kodu burası olmalı
                        ]
                    ],
                    /**
                     * Bu alan mikroihracat ise bir kez tüm shipment için belirtiliyor.
                     *
                     * "permanent", "temporary", "return", "used_exhibition_goods_to_origin", "intercompany_use",
                     * "commercial_purpose_or_sale", "personal_belongings_or_personal_use", "sample", "gift",
                     * "return_to_origin", "warranty_replacement", "diplomatic_goods", "defence_material"
                     *
                     * "exportReasonType" => "permanent",
                     */

                    "manufacturerCountry" => "TR",
                    "weight" => [
                        "netValue" => $desi,
                        "grossValue" => $desi,
                    ],
                    "isTaxesPaid" => true,
                ];
                $count++;
            }

        }

        $return = $this->additionalCharges($return);

        $return['packages'] = $packages;
        $return['exportDeclaration']['lineItems'] = $lineItems;

        return $return;
    }

    /**
     * "admin", "delivery", "documentation", "expedite", "export", "freight", "fuel_surcharge", "logistic","other",
     * "packaging", "pickup", "handling", "vat", "insurance", "reverse_charge"
     *
     * @param $return
     * @return array
     */
    private function additionalCharges($return): array
    {
        $additionalCharges = [];

        foreach ($this->order->orderProducts()->where('type', 1)->get() as $shipment) {
            $additionalCharges[] = [
                'value' => floatval($shipment->total_price),
                'typeCode' => 'delivery',
                'caption' => $shipment->name,
            ];
        }

        foreach ($this->order->orderProducts()->where('type', 2)->get() as $payment) {
            $additionalCharges[] = [
                'value' => floatval($payment->total_price),
                'typeCode' => 'other',
                'caption' => $payment->name,
            ];
        }

        if(!empty($additionalCharges)){
            $return['exportDeclaration']['additionalCharges'] = $additionalCharges;
        }

        return $return;
    }
};
