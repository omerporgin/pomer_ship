<?php

namespace App\Libraries\Shippings;

use App\Traits\ErrorTrait;
use App\Models\Shipping;

abstract class AbstractShipping
{

    use ErrorTrait;

    /**
     * @var array
     */
    protected array $err = [];

    /**
     * @var Int;
     */
    protected $shippingId;


    protected $shipping;

    /**
     * @var App\Services\CountryService;
     */
    protected $countryService;

    /**
     * @var array
     */
    protected array $data = [];

    /**
     * Raw response
     *
     * @var
     */
    protected $response;

    protected $order;

    protected bool $createRequest = false;
    protected array $requestData = [];
    protected $requestOrderData = null;

    abstract public function getUrl($url): string;

    abstract protected function returnResponse($response): bool|object;

    abstract public function response(): object;

    abstract public static function getEstimatedPrice(int $desi, int $zone): float;

    abstract public function calculateDesi(object $request): float;

    /**
     *
     */
    public function __construct()
    {
    }

    /**
     * Returns current entities properties.
     * (Dynamic properties will be deprecated in php 9.0 )
     *
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        if (isset($this->shipping->{$name})) {
            return $this->shipping->{$name};
        } else {
            //throw new Exception("$name don't exists");
        }
        return null;
    }

    /**
     * @return void
     */
    public function setShipping(Shipping $shipping)
    {
        $this->shipping = $shipping;
    }

    /**
     * @return Shipping
     */
    public function getShipping()
    {
        return $this->shipping;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function getAccountNumber()
    {
        return $this->shipping->account_number;
    }

    /**
     * @param array|null $data
     * @return $this
     */
    public function withOrder($order)
    {
        $this->order = $order;
        return $this;
    }

    /**
     * @param array|null $data
     * @return $this
     */
    public function withData(array $data = null)
    {
        if (!is_null($data)) {
            $this->data = $data;
        }
        return $this;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function withRequest(array $requestData, $order = null)
    {
        $this->createRequest = true;
        $this->requestData = $requestData;
        $this->requestOrderData = $order;
        return $this;
    }

    /**
     * @param string $requestClassName
     * @return array
     */
    public function buildRequest(string $requestClassName): array
    {

        $r = $this->requestFactory($requestClassName, $this->requestOrderData);
        if (!$r) {
            die("Request file : " . $requestClassName . ' or $order required');
        }

        $this->data = $r->build($this->requestData);
        return $this->data;
    }

    /**
     * @param $requestClassName
     * @param $order
     * @return false|mixed
     */
    public function requestFactory($requestClassName, $order = null)
    {
        return Factory::request($requestClassName, $this->shipping, $this->requestOrderData);
    }

    /**
     * @param $countryService
     * @return void
     */
    public function setCountryService($countryService)
    {
        $this->countryService = $countryService;
    }

    /**
     * @param int $shippingId
     * @param int $region
     * @return App\Services\CountryService
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function getCountryService(int $shippingId, int $region)
    {

        if (is_null($this->countryService)) {

            $countryService = service('LocationCountry');
            try {
                $dbRegionField = match ($shippingId) {
                    2 => 'cargo_ups_id',
                    1 => 'cargo_dhl_id',
                };
            } catch (\Exception $e) {
                throw new \Exception($e->getMessage());
            }

            // whereNotIn id =1 afganistan
            if (is_null($countryRow = $countryService->get()->where($dbRegionField, $region)->whereNotIn('id',[1])
                ->first()
            )) {
                throw new \Exception('Contry not found ' . $dbRegionField . ' regioon : ' . $region);
            }

            $countryService->setItem($countryRow);
            $this->setCountryService($countryService);
        }

        return $this->countryService;
    }

    /**
     * @param int $days
     * @return string
     */
    public function getShippingDate(int $days = 3)
    {
        return date('Y-m-d', strtotime(date("Y-m-d") . ' +' . $days . ' day'));
    }
}
