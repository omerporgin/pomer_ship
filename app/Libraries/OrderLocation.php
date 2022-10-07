<?php

namespace App\Libraries;

use App\Services\OrderService;
use App\Services\LocationStateService;
use App\Services\LocationCityService;
use App\Services\LocationCountryService;
use App\Traits\ErrorTrait;

class OrderLocation
{
    use ErrorTrait;

    /**
     * For ErrorTrait
     *
     * @var array
     */
    public array $err = [];

    /**
     * @var LocationStateService
     */
    public LocationStateService $state;

    /**
     * @var LocationCityService
     */
    public LocationCityService $city;

    /**
     * @var LocationCountryService
     */
    public LocationCountryService $country;

    /**
     * @var OrderService
     */
    protected OrderService $order;

    /**
     * @param int $orderID
     */
    public function __construct(OrderService $order)
    {
        $this->order = $order;

        $this->set();
    }

    /**
     * Sets location services
     *
     * @return bool
     */
    public function set(): bool
    {

        // Set services
        $this->city = service('LocationCity', $this->order->city_id);
        $this->state = service('LocationState', $this->order->state_id);
        $this->country = service('LocationCountry', $this->order->country_id);

        // Check services
        if (!$this->country->hasItem() or !$this->country->hasItem() or !$this->country->hasItem()) {
            $this->setError('location services can not be null');
            return false;
        }

        // Check ids
        if (is_null($this->order->city_id) or is_null($this->order->state_id) or is_null($this->order->country_id)) {
            $this->setError('location items can not be null');
            return false;
        }

        return true;
    }

    /**
     * Shows "Country > State > City" by using city_id
     *
     * @param bool $isHtml
     * @return string
     */
    public function getFullName(bool $isHtml = true): string
    {
        $countryName = '.....';
        $stateName = '.....';
        $cityName = '.....';
        if ($this->country->hasItem()) {
            $countryName = $this->country->name;
        }
        if ($this->state->hasItem()) {
            $stateName = $this->state->name;
        }
        if ($this->city->hasItem()) {
            $cityName = $this->city->name;
        }
        if ($isHtml) {
            return '
                <div class="text-nowrap" style="font-size:0.8em">
                    ' . $stateName . " > " . $cityName . '
                </div>
                <div class="text-primary">
                    <b>
                        ' . $countryName . '
                    </b>
                </div>';
        } else {
            return $countryName . " > " . $stateName . " > " . $cityName;
        }
    }

}
