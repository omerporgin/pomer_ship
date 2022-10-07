<?php

use App\Libraries\Shippings\RequestInterface;
use App\Libraries\Shippings\Shipping_1\Requests\Request;

return new class($shipping, $order) extends Request implements RequestInterface {

    /**
     * @var array
     */
    protected $documents = [];

    /**
     * @return array
     */
    public function __construct($shipping, $order)
    {
        parent::__construct($shipping, $order);
    }

    /**
     * @return array
     */
    public function set()
    {
        $package = $this->order->packages()->first();

        $this->data = [
            "shipmentTrackingNumber" => $package?->tracking_number,
            "originalPlannedShippingDate" =>  $this->order->shipped_at, //"2020-04-20",
            "productCode" => "P",
            "documentImages" => $this->documents
        ];

        $this->addAccounts();
    }

    /**
     * INV, Invoice
     * PNV, Proforma
     * COO, Certificate of Origin
     * NAF, Nafta Certificate of Origin
     * CIN, Commercial Invoice
     * DCL, Custom Declaration
     * AWB, Air Waybill and Waybill Document
     *
     * @param string $typeCode
     * @param string $binaryContent
     * @param string $imageFormat
     * @return void
     */
    public function addDocument(string $typeCode, string $binaryContent, string $imageFormat = 'PDF')
    {
        $this->documents[] = [
            "typeCode" => $typeCode,
            "imageFormat" => $imageFormat,
            "content" =>  chunk_split(base64_encode($binaryContent)),
        ];
    }
};
