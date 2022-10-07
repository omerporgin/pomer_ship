<?php

namespace App\Libraries\Shippings\Shipping_2;

use App\Events\ShippingPriceUpdated;
use App\Libraries\Shippings\AbstractShipping;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Libraries\Shippings\Factory;

return new class extends AbstractShipping {

    /**
     * v1707 -> version
     * possible values : v1707, v1607
     * @var string
     */
    protected string $testUrl = 'https://wwwcie.ups.com/';
    protected string $url = 'https://wwwcie.ups.com/';

    const API_VERSION = 'v1707';

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
        if ($this->isTest) {
            $baseUrl = $this->testUrl;
        } else {
            $baseUrl = $this->url;
        }
        return $baseUrl . $url;
    }

    /**
     * @param $response
     * @return bool
     */
    protected function returnResponse($response): bool
    {
        $response = json_decode($response->body());

        if (isset($response->response->errors)) {
            $this->setError($response->response->errors[0]->message);
            return false;
        };
        /*
            UPS de aşağıdakine benze sounuçlar geliyor. Buradaki "RateResponse" key'i her responsta
        değişiyor. Bundan kurtulmamız gerekiyor.
            "RateResponse": {
                "Response": {}
            }
         */
        $this->response = array_values((array)$response)[0];

        if ($this->response->Response->ResponseStatus->Code == 0) {
            $this->setErrorList([

            ]);

            return false;
        } else {

            return true;
        }

        return false;

    }

    /**
     * @return object
     */
    public function response(): object
    {
        return $this->response;
    }

    /**
     * @param int $desi
     * @param int $zone
     * @return float
     */
    public static function getEstimatedPrice(int $desi, int $zone): float
    {
        return 0.0;
    }

    /**
     * Dhlye göre desi
     *
     * @param float $width cm
     * @param float $height cm
     * @param float $length cm
     * @return float
     */
    public function calculateDesi(object $request): float
    {
        $desi = $request->width * $request->height * $request->length / 5000;
        return $desi;
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

                if (!$results = $this->rates([
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
     * Create required header data for request
     *
     * @param array|null $newData
     * @return array
     */
    protected function headerData(?array $newData = null): array
    {
        $requiredData = [
            'AccessLicenseNumber' => $this->shipping->api_secret,
            'Username' => $this->shipping->user,
            'Password' => $this->shipping->api_key,
        ];
        if (!is_null($newData)) {
            $requiredData = array_merge($requiredData, $newData);
        }
        return $requiredData;
    }

    /******************************************************************************************************************
     * SERVICES START HERE
     ******************************************************************************************************************/

    /**
     * Sadece Amerika Birleşik Devletleri ve Porto Riko da geçerli olduğu yazılı. (https://www.ups.com/upsdeveloperkit/downloadresource?loc=tr_TR)
     *
     * [
     * 'AccessRequest' => [
     * 'AccessLicenseNumber' => '1DBD1F9C908D6140',
     * 'UserId' => 'serdar_gulum13',
     * 'Password' => 'iZpub8rz*-Rtf3J',
     * ],
     * 'AddressValidationRequest' =>
     * [
     * "Request" => [
     * "TransactionReference" => [
     * "CustomerContext" => "Your Customer Context",
     * ],
     * "RequestAction" => 'AV'
     * ],
     * "Address" => [
     * "City" => "ADALAR",
     * "StateProvinceCode" => "",
     * "PostalCode" => "34000"
     * ]
     * ]
     * ]
     * @param array $data
     * @return bool
     */
    public function addrressValidation(array $data): bool
    {

        try {
            $response = (object)Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($this->getUrl('rest/AV'), $data);

            return $this->returnResponse($response);

        } catch (\Exception $e) {
            $this->setError($e->getMessage());
            return false;
        }
    }

    /**
     *  UPS gönderi şubelerini aramak için pratik ve esnek bir yol sağlar.
     *
     * (109 ülke veya bölge) ABD Virgin Adaları, Almanya, Amerika Birleşik Devletleri, Angola, Arjantin, Avustralya,
     * Avusturya, Azerbaycan, Bahamalar, Bahreyn, Bangladeş, Belçika, Bermuda, Birleşik Arap Emirlikleri, Birleşik Krallık,
     * Bolivya, Brezilya, Bulgaristan, Burundi, Cayman Adaları, Cezayir, Cibuti, Çek Cumhuriyeti, Anakara Çin, Danimarka,
     * Dominik Cumhuriyeti, Ekvador, El Salvador, Endonezya, Estonya, Etiyopya, Fas, Fildişi Sahili, Filipinler, Finlandiya,
     * Fransa, Gana, Guatemala, Guernsey, Güney Kore, Güney Afrika, Hırvatistan, Hindistan, Hollanda, Honduras,
     * Hong Kong Özel Yönetim Bölgesi - Çin, İrlanda, İspanya, İsrail, İsveç, İsviçre, İtalya, Japonya, Jersey, Kamerun,
     * Kanada, Katar, Kenya, Kolombiya, Kongo Demokratik Cumhuriyeti, Kosta Rika, Kuveyt, Letonya, Litvanya, Lüksemburg,
     * Macaristan, Makau Özel Yönetim Bölgesi - Çin, Malawi, Malezya, Meksika, Mısır, Morityus, Mozambik, Nijerya, Nikaragua,
     * Norveç, Pakistan, Panama, Paraguay, Peru, Polonya, Portekiz, Porto Riko, Romanya, Ruanda, Rusya, Sırbistan, Singapur,
     * Slovakya, Slovenya, Sri Lanka, Suudi Arabistan, Şili, Tanzanya, Tayland, Tayvan - Çin, Tunus, Türkiye, Uganda, Ukrayna,
     * Umman, Uruguay, Ürdün, Venezuela, Vietnam, Yeni Zelanda, Yunanistan, Zambiya ve Zimbabve  geçerli olduğu yazılı.
     * (https://www.ups.com/upsdeveloperkit/downloadresource?loc=tr_TR)
     *
     * @param array $data
     * @return bool
     */
    public function locator(array $data): bool
    {
        return false;
    }

    /**
     *
     * @param array $data
     * @return bool
     */
    public function ratesOfOrder(): bool
    {

        if ($this->createRequest) {
            $this->buildRequest(__FUNCTION__);
        }

        $url = $this->getUrl('ship/v1801/rating/Rate');

        try {
            $response = (object)Http::withHeaders($this->headerData([
                'transId' => time(),
                'transactionSrc' => time() . 'sad',
                'Content-Type' => 'application/json',
            ]))->post($url, ["RateRequest" => $this->data]);

            return $this->returnResponse($response);

        } catch (\Exception $e) {
            $this->setError($e->getMessage());
            return false;
        }
        return false;
    }

    /**
     *
     * @param array $data
     * @return bool
     */
    public function rates(array $data): bool
    {
        $rateRequest = Factory::request('Rates', $this);

        $rateRequest->setData($data);
        $url = $this->getUrl('ship/v1801/rating/Rate');
        dd($rateRequest->build());
        dd(12);
        try {
            $response = (object)Http::withHeaders($this->headerData([
                'transId' => time(),
                'transactionSrc' => time() . 'sad',
                'Content-Type' => 'application/json',
            ]))->post($url, [
                "RateRequest" => $this->data
            ]);

            return $this->returnResponse($response);

        } catch (\Exception $e) {

            $this->setError($e->getMessage());
            return false;
        }
        return false;
    }

    /**
     * Create pickup
     *
     * @param array $data
     * @return bool
     */
    public function onePieceShipment(): bool
    {

        if ($this->createRequest) {
            $this->buildRequest(__FUNCTION__);
        }

        $url = $this->getUrl('ship/' . self::API_VERSION . '/pickups');
        try {
            $response = (object)Http::withHeaders($this->headerData())->post($url, $this->data);

            return $this->returnResponse($response);

        } catch (\Exception $e) {
            $this->setError($e->getMessage());
            return false;
        }
        return false;
    }

    /**
     * UPS multipiece desteklemiyor.
     *
     * @param array $data
     * @return bool
     */
    public function multiPieceShipment(): bool
    {
        return $this->onePieceShipment();
    }
};
