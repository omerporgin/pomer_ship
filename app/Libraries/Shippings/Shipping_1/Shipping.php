<?php

namespace App\Libraries\Shippings\Shipping_1;

use App\Libraries\Shippings\AbstractShipping;
use App\Events\ShippingPriceUpdated;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

return new class extends AbstractShipping {

    /**
     * @var string
     */
    protected string $testUrl = 'https://express.api.dhl.com/mydhlapi/test/';
    protected string $url = 'https://express.api.dhl.com/mydhlapi/';

    protected array $desiList = [0.5, 1, 1.5, 2, 2.5, 3, 3.5, 4, 4.5, 5, 5.5, 6, 6.5, 7, 7.5, 8, 8.5, 9, 9.5, 10, 11,
        12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 40, 50, 60, 70];

    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     *
     */
    public function getUrl($url): string
    {
        if ($this->shipping->is_test) {
            $baseUrl = $this->testUrl;
        } else {
            $baseUrl = $this->url;
        }
        return $baseUrl . $url;
    }

    /**
     * @return array
     */
    public function getDesiList(): array
    {
        return $this->desiList;
    }

    public function getStandartDesi(float $desi): float
    {
        foreach ($this->desiList as $currentDesi) {
            if ($desi < $currentDesi) {
                return $currentDesi;
            }
        }
        return $desi;
    }

    /**
     * @param $response
     * @return object
     */
    protected function returnResponse($response): bool|object
    {

        $this->response = $response;

        if (isset($response->status)) {
            $status = $response->status;
        } else {
            $status = $response->getStatusCode();
        }

        // Set errror
        $responseJson = $response->json();

        if (is_array($responseJson)) {
            $responseJson = (object)$responseJson;
        }
        if (isset($responseJson->additionalDetails)) {
            $this->setErrorList($responseJson->additionalDetails);
        }

        if (isset($responseJson->detail)) {
            $this->setError('Err : ' . $responseJson->detail);
        }

        if (!in_array($status, [200, 201])) {
            $msg = match ($status) {
                200 => 'true',
                201 => 'true',
                400 => 'Wrong input parameters',
                401 => 'Invalid Credentials',
                404 => 'Not found',
                422 => 'Wrong input parameters (schema validation error)',
                500 => 'Process errors',
                default => 'Undefined status code',
            };
            $this->setError($msg);
            return false;
        }

        return $responseJson;
    }

    /**
     * @return object
     */
    public function response(): object
    {
        $response = $this->response->json();
        if (is_array($response)) {
            $response = (object)$response;
        }
        return $response;
    }

    /**
     * @param int $desi
     * @param int $zone
     * @return float
     */
    public static function getEstimatedPrice(int $desi, int $zone): float
    {
        $field = 'zone_' . $zone;
        $row = \App\Models\ShippingDHLPrices::where('desi', $desi)->first([$field]);
        return $row->{$field};
    }

    /******************************************************************************************************************
     * SERVICES START HERE
     ******************************************************************************************************************/

    /**
     * DHL's product capabilities and prices (where applicable) based on the input data
     *
     * @param array $data
     * @return bool
     */
    public function onePieceShipment(): bool|object
    {

        if (!is_null($this->order) and $this->order->packages()->count() != 1) {
            $this->setError('Birden fazla paket var.');
            return false;
        }

        if ($this->createRequest) {
            $this->buildRequest(__FUNCTION__);
        }

        try {
            $response = (object)Http::withBasicAuth($this->shipping->api_key, $this->shipping->api_secret)
                ->withHeaders([
                    'content-type' => 'application/json',
                ])
                ->get($this->getUrl('rates'), $this->data);

            return $this->returnResponse($response);

        } catch (\Exception $e) {
            $this->setError($e->getMessage());
            return false;
        }
    }

    /**
     * Retrieve Rates for Multi-piece Shipments
     *
     * @param array $data
     * @return void
     */
    public function multiPieceShipments(): bool
    {
        if (!is_null($this->order) and $this->order->packages()->count() == 1) {
            $this->setError('Birden fazla paket var. onePieceShipment kullanın.');
            return false;
        }

        if ($this->createRequest) {
            $this->buildRequest(__FUNCTION__);
        }

        try {
            $response = (object)Http::withBasicAuth($this->shipping->api_key, $this->shipping->api_secret)
                ->withHeaders([
                    'content-type' => 'application/json',
                ])
                ->post($this->getUrl('rates'), $this->data);

            return $this->returnResponse($response);

        } catch (\Exception $e) {

            $this->setError($e->getMessage());
            return false;
        }
    }

    /**
     * The Landed Cost section allows further information around products being sold to be provided. In return the duty,
     * tax and shipping charges are calculated in real time and provides transparency about any extra costs the buyer
     * may have to pay before they reach them.
     *
     * extra costs the buyer may have to pay before they reach them.
     *
     *
     * @param array $data
     * @return bool
     */
    public function landedCost(): bool
    {
        if ($this->createRequest) {
            $this->buildRequest(__FUNCTION__);
        }

        try {
            $response = (object)Http::withBasicAuth($this->shipping->api_key, $this->shipping->api_secret)
                ->withHeaders([
                    'content-type' => 'application/json',
                ])
                ->post($this->getUrl('landed-cost'), $this->data);

            return $this->returnResponse($response);

        } catch (\Exception $e) {
            $this->setError($e->getMessage());
            return false;
        }
    }

    /**
     *  DHL's product capabilities for a certain set of input data...
     *
     * @param array $data
     * @return bool
     */
    public function products(): bool
    {
        if ($this->createRequest) {
            $this->buildRequest(__FUNCTION__);
        }

        try {
            $response = (object)Http::withBasicAuth($this->shipping->api_key, $this->shipping->api_secret)
                ->withHeaders([
                    'content-type' => 'application/json',
                ])
                ->get($this->getUrl('products'), $this->data);

            return $this->returnResponse($response);

        } catch (\Exception $e) {
            $this->setError($e->getMessage());
            return false;
        }
    }

    /**
     * The electronic proof of delivery service can be used to retrieve proof of delivery for certain delivered DHL Express shipments
     *
     * @param array $data
     * @param string $tractkingNumber
     * @return bool
     */
    public function proofOfDelivery(string $tractkingNumber): bool
    {

        if ($this->createRequest) {
            $this->buildRequest(__FUNCTION__);
        }

        try {
            dd($this->shipping->api_key, $this->shipping->api_secret);
            $url = $this->getUrl('shipments/' . $tractkingNumber . '/proof-of-delivery');
            $response = Http::withBasicAuth($this->shipping->api_key, $this->shipping->api_secret)
                ->withHeaders([
                    'content-type' => 'application/json',
                ])->get($url);

            return $this->returnResponse($response);

        } catch (\Exception $e) {
            $this->setError($e->getMessage());
            return false;
        }
    }

    /**
     * @param array $data
     * @param string $tractkingNumber
     * @return bool
     */
    public function uploadImage(): bool
    {
        if ($this->createRequest) {
            $this->buildRequest(__FUNCTION__);
        }

        try {
            $response = Http::withBasicAuth($this->shipping->api_key, $this->shipping->api_secret)
                ->withHeaders([
                    'content-type' => 'application/json',
                ])
                ->patch($this->getUrl($this->data['shipmentTrackingNumber'] . '/upload-image'), $this->data);

            return $this->returnResponse($response);

        } catch (\Exception $e) {
            $this->setError($e->getMessage());
            return false;
        }
    }

    /**
     *
     * @param array $data
     * @return bool|object
     */
    public function createShipment(): bool|object
    {

        if ($this->createRequest) {
            $this->buildRequest(__FUNCTION__);
        }

        try {
            $url = $this->getUrl('shipments');
            $response = Http::withBasicAuth($this->shipping->api_key, $this->shipping->api_secret)
                ->withHeaders([
                    'content-type' => 'application/json',
                ])->post($url, $this->data);

            return $this->returnResponse($response);

        } catch (\Exception $e) {
            $err = $e->getMessage();
            $this->setError($err);
            return false;
        }
    }

    /**
     * @param array $data
     * @param string $tractkingNumber
     * @return bool
     */
    public function uploadInvoiceData(string $tractkingNumber): bool
    {
        if ($this->createRequest) {
            $this->buildRequest(__FUNCTION__);
        }

        try {
            $response = Http::withBasicAuth($this->shipping->api_key, $this->shipping->api_secret)
                ->withHeaders([
                    'content-type' => 'application/json',
                ])
                ->patch($this->getUrl($tractkingNumber . '/upload-invoice-data'), $this->data);

            return $this->returnResponse($response);

        } catch (\Exception $e) {
            $this->setError($e->getMessage());
            return false;
        }
    }

    /**
     * @param array $data
     * @param string $tractkingNumber
     * @return bool
     */
    public function getImage(string $tractkingNumber): bool
    {
        if ($this->createRequest) {
            $this->buildRequest(__FUNCTION__);
        }

        try {
            $response = Http::withBasicAuth($this->shipping->api_key, $this->shipping->api_secret)
                ->withHeaders([
                    'content-type' => 'application/json',
                ])
                ->patch($this->getUrl($tractkingNumber . '/get-image'), $this->data);

            return $this->returnResponse($response);

        } catch (\Exception $e) {
            $this->setError($e->getMessage());
            return false;
        }
    }

    /**
     * @param array $data
     * @param string $tractkingNumber
     * @return bool
     */
    public function tracking(string $tractkingNumber): bool
    {
        if ($this->createRequest) {
            $this->buildRequest(__FUNCTION__);
        }

        try {
            $response = (object)Http::withBasicAuth($this->shipping->api_key, $this->shipping->api_secret)
                ->withHeaders([
                    'content-type' => 'application/json',
                ])
                ->get($this->getUrl($tractkingNumber . '/tracking'), $this->data);

            return $this->returnResponse($response);

        } catch (\Exception $e) {
            $this->setError($e->getMessage());
            return false;
        }
    }

    /**
     * @param array $data
     * @param array $tractkingNumberList
     * @return bool
     */
    public function trackingList(array $list): bool
    {
        if ($this->createRequest) {
            $this->buildRequest(__FUNCTION__);
        }

        $data['shipmentTrackingNumber'] = $list;
        try {
            $response = (object)Http::withBasicAuth($this->shipping->api_key, $this->shipping->api_secret)
                ->withHeaders([
                    'content-type' => 'application/json',
                ])
                ->get($this->getUrl('tracking'), $this->data);

            return $this->returnResponse($response);

        } catch (\Exception $e) {
            $this->setError($e->getMessage());
            return false;
        }
    }

    /**
     * @param array $data
     * @return bool -> dispatchConfirmationNumbers
     */
    public function pickUp(): bool
    {
        if ($this->createRequest) {
            $this->buildRequest(__FUNCTION__);
        }

        try {
            $response = Http::withBasicAuth($this->shipping->api_key, $this->shipping->api_secret)
                ->withHeaders([
                    'content-type' => 'application/json',
                ])
                ->post($this->getUrl('pickups'), $this->data);

            return $this->returnResponse($response);

        } catch (\Exception $e) {
            $this->setError($e->getMessage());
            return false;
        }
    }

    /**
     * @param array $data
     * @param string $dispatchConfirmationNumber
     * @return bool
     */
    public function pickUpUpdate(string $dispatchConfirmationNumber): bool
    {
        if ($this->createRequest) {
            $this->buildRequest(__FUNCTION__);
        }

        try {
            $response = Http::withBasicAuth($this->shipping->api_key, $this->shipping->api_secret)
                ->withHeaders([
                    'content-type' => 'application/json',
                ])
                ->patch($this->getUrl('pickups/' . $dispatchConfirmationNumber), $this->data);

            return $this->returnResponse($response);

        } catch (\Exception $e) {
            $this->setError($e->getMessage());
            return false;
        }
    }

    /**
     * @param array $data
     * @param string $dispatchConfirmationNumber
     * @return bool
     */
    public function pickUpCancel(string $dispatchConfirmationNumber): bool
    {
        if ($this->createRequest) {
            $this->buildRequest(__FUNCTION__);
        }

        try {
            $response = Http::withBasicAuth($this->shipping->api_key, $this->shipping->api_secret)
                ->withHeaders([
                    'content-type' => 'application/json',
                ])
                ->delete($this->getUrl('pickups/' . $dispatchConfirmationNumber) . "?" . http_build_query($this->data));
            return $this->returnResponse($response);

        } catch (\Exception $e) {
            $this->setError($e->getMessage());
            return false;
        }
    }

    /**
     * @param array $data
     * @return bool
     */
    public function identifiers(): bool
    {
        if ($this->createRequest) {
            $this->buildRequest(__FUNCTION__);
        }

        try {
            $response = (object)Http::withBasicAuth($this->shipping->api_key, $this->shipping->api_secret)
                ->withHeaders([
                    'content-type' => 'application/json',
                ])
                ->get($this->getUrl('identifiers'), $this->data);

            return $this->returnResponse($response);

        } catch (\Exception $e) {
            $this->setError($e->getMessage());
            return false;
        }
    }

    /**
     * cityName
     *
     *
     *
     * @param array $data
     * @return bool
     */
    public function validateAddress(): bool
    {
        if ($this->createRequest) {
            $this->buildRequest(__FUNCTION__);
        }

        try {
            $response = (object)Http::withBasicAuth($this->shipping->api_key, $this->shipping->api_secret)
                ->withHeaders([
                    'content-type' => 'application/json',
                ])
                ->get($this->getUrl('address-validate'), $this->data);

            return $this->returnResponse($response);

        } catch (\Exception $e) {
            $this->setError($e->getMessage());
            return false;
        }
    }

    /**
     * @param array $data
     * @return bool
     */
    public function uploadCommericalInvoiceData(): bool
    {
        if ($this->createRequest) {
            $this->buildRequest(__FUNCTION__);
        }

        try {
            $response = (object)Http::withBasicAuth($this->shipping->api_key, $this->shipping->api_secret)
                ->withHeaders([
                    'content-type' => 'application/json',
                ])
                ->post($this->getUrl('invoices/upload-invoice-data'), $this->data);

            return $this->returnResponse($response);

        } catch (\Exception $e) {
            $this->setError($e->getMessage());
            return false;
        }
    }

    /**
     * Updates prices
     *
     * @param int $region
     * @param int $maxDesi -> Max value for dhl = 70
     * @return void
     */
    public function updateShippingPrices(int $region, int $maxDesi = 70)
    {
        set_time_limit(0);

        $desi = 1;
        while ($desi <= $maxDesi) {

            try {

                if (!$results = $this->getPrice([
                    'region' => $region,
                    'desi' => $desi,
                ])) {
                    throw new \Exception('Empty Results!');
                }

                $priceService = service('shippingPrices');

                // Delete old data
                $priceService->get()->where('shipping_id', $this->id)->where('desi', $desi)->where('region', $region)->delete();

                foreach ($results as $result) {

                    $saveResult = $priceService->save([
                        'shipping_id' => $this->id,
                        'service' => $result['name'],
                        'desi' => $desi,
                        'price' => $result['price'],
                        'currency' => $result['currency'],
                        'region' => $region,
                        'data' => $result['data'],
                    ]);

                    if ($saveResult['result']) {
                        $priceService->reset();
                    }

                }
            } catch (\Exception $e) {
                throw new \Exception($e->getMessage());
            }

            // Increase desi
            if ($desi < 10) {
                $increament = 0.5;
            } else {
                $increament = 1;
            }
            Log::debug($desi);

            $desi += $increament;
        }

        event(new ShippingPriceUpdated($this));
    }

    /**
     * @param array $data
     * @return array|void
     */
    protected function processPriceData(array $data): array
    {

        try {
            $this->getCountryService($this->id, $data['region']);
        } catch (\Exception $e) {
            return [];
        }

        // Servis ise desi size a göre değil kiloya göre belirleniyor.
        $data['width'] = 10;
        $data['height'] = 10;
        $data['length'] = 10;
        $data['weight'] = $data['desi'];
        $data['destinationCountryCode'] = $this->countryService->iso2;
        $data['destinationCityName'] = $this->countryService->capital;//$stateName
        $data['plannedShippingDate'] = $this->getShippingDate();
        unset($data['desi']);
        unset($data['shipping_id']);
        return $data;
    }

    /**
     * Get price from shipping service
     *
     * @param array $data
     * @return array
     */
    public function getPrice(array $data): array
    {
        $data = $this->processPriceData($data);

        if (empty($data)) {
            throw new \Exception('Bu regionda kayıtlı ülke yok.');
        }

        $result = [];

        if ($this->withRequest($data)->onePieceShipment()) {

            $returnResult = $this->response();

            foreach ($returnResult->products as $item) {
                $estimatedDelivery = date("Y-m-d H:i", strtotime($item['deliveryCapabilities']['estimatedDeliveryDateAndTime']));
                $result[] = [
                    'name' => $item['productName'],
                    'usdPrice' => $item['totalPrice'][0]['price'],
                    'price' => $item['totalPrice'][0]['price'],
                    'currency' => $item['totalPrice'][0]['priceCurrency'],
                    'estimatedDelivery' => $estimatedDelivery,
                    'data' => json_encode($returnResult),
                ];
            }

        } else {
            $this->setErrorList($this->getErrorList());
        }

        return $result;
    }

    /**
     * DHL için iki kural var.
     *      1 - 10 desiye kadar 0.5 desi var.
     *      2 - 30 dan sonra 10 ar 10 ar artıyor.
     *
     * @param object $request
     * @return float
     */
    public function calculateDesi(object $request): float
    {
        $boundaryDesiArr = $this->getDesiList();
        if ($request->weight > 70) {
            throw new \Exception(_('Weight must be lower than 70kg'));
        }

        if ($request->width > 70 or $request->height > 70 or $request->length > 70) {
            throw new \Exception(_('Dimensions must be lower than 70cm'));
        }

        // Calculate desi
        $desiBySize = ($request->length * $request->width * $request->height) / 5000;
        $desiByWeight = $request->weight;
        $desi = max($desiByWeight, $desiBySize);

        $returnDesi = 0.5;
        foreach ($boundaryDesiArr as $boundary) {
            $returnDesi = $boundary;
            if ($desi <= $boundary) {
                break;
            }
        }

        return $returnDesi;
    }

};
