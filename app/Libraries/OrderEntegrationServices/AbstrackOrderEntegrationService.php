<?php

namespace App\Libraries\OrderEntegrationServices;

use App\Models\EntegrationData;
use App\Services\EntegrationService;
use App\Traits\ErrorTrait;
use \App\Models\LocationCountry;
use \App\Models\LocationState;
use \App\Models\LocationCity;

abstract class AbstrackOrderEntegrationService
{
    use ErrorTrait;

    /**
     * @var array
     */
    public array $err = [];

    /**
     * @var string
     */
    public $entegration = null;

    /**
     * @var array
     */
    public $orderList = [];

    /**
     * @var SaveOrders
     */
    public $saveOrderService;

    abstract public function getOrderList(): bool;

    abstract protected function isDownloadable(object $order): bool;

    /**
     *
     */
    public function __construct()
    {
        $this->saveOrderService = app()->make(SaveOrders::class);
    }

    /**
     * Deprecated method -> excetute()
     * @return int
     */
    public function getOrders(): int
    {
        if ($this->getOrderList()) {

            $total = $this->saveOrderService->saveAll($this->orderList);

            $this->mergeErrors($this->saveOrderService->getErrorList());
            return $total;
        }
        return 0;

    }

    /**
     * @return void
     */
    public function addOrder($order)
    {
        $order->vendorID = $this->entegration->user_id;
        $order->entegrationID = $this->entegration->id;
        return $this->orderList[] = $order;
    }

    /**
     * @return int
     */
    public function sumItems(): int
    {
        return count($this->orderList);
    }

    /**
     * @return void
     */
    public function setEntegration(EntegrationData $entegration)
    {
        $this->entegration = $entegration;
    }

    /**
     * Updates entegration last_date
     *
     * @return bool
     */
    public function updateLastDate(): bool
    {
        return true;
        $lastDate = null;
        if (!is_null($this->entegration)) {
            $lastDate = $this->getLastDate();
            $this->entegration->last_date = $lastDate;
            $this->entegration->save();
            return true;
        }
        return false;
    }

    /**
     * Returns last date
     *
     * @return string|null
     */
    public function getLastDate(): ?string
    {

        $lastDate = null;
        if (!is_null($this->entegration)) {
            $lastDate = date("Y-m-d", strtotime($this->entegration->last_date . " +" . $this->entegration->days .
                " days"));
        }

        // Last date can not be greater than now
        if ($lastDate > date("Y-m-d")) {
            $lastDate = date("Y-m-d");
        }

        return $lastDate.' 23:59:59';
    }

    /**
     * Returns last date
     *
     * @return string|null
     */
    public function getFirstDate(): ?string
    {
        $updated_at_from = date( "Y-m-d" , strtotime($this->entegration->last_date)).' 00:00:01';
        return $updated_at_from;
    }

    /**
     * Returns entegration id by using class name
     *
     * @return void
     */
    public function entegrationID(): int
    {
        $name = basename(get_class($this));
        $entegrationID = str_replace(['Entegration', '.php'], '', $name);
        return intVal($entegrationID);
    }

    /**
     * Converts entegration order status to shipExporgin status
     *
     * @param $status
     * @return int
     */
    public function convertStatus($status): int
    {

        $newStatus = 12; // 12 is reserved undefined status
        if (!is_null($this->entegration)) {
            $count = 1;
            $entegration = new EntegrationService($this->entegration->entegration_id);
            $entegration = $entegration->get();
            while ($count < 12) {
                if (isset($entegration->{'status_' . $count}) and $entegration->{'status_' . $count} == $status) {
                    $newStatus = $count;
                    break;
                }
                $count++;
            }
        }
        return $newStatus;
    }

    /**
     * @param array $parameters
     *      [
     *          'cityName' => '',
     *          'stateName' => '',
     *          'countryIso' => '',
     *          'countryName' => '',
     *      ]
     * @return null
     */
    protected function convertLocation(array $parameters): array
    {
        $cityID = null;
        $stateID = null;
        $countryID = null;

        $cityName = trim($parameters['cityName'] ?? '');
        $stateName = trim($parameters['stateName'] ?? '');
        $countryIso = strtoupper(trim($parameters['countryIso'] ?? ''));
        $countryName = trim($parameters['countryName'] ?? '');

        // Try to get countryID
        if (!empty($countryIso)) {
            if (!is_null($countryEntity = LocationCountry::where('iso2', $countryIso)->first())) {
                $countryID = $countryEntity->id;
            } elseif (!is_null($countryEntity = LocationCountry::where('name', $countryName)->first())) {
                $countryID = $countryEntity->id;
            } else {
                // Look at translations
                foreach (LocationCountry::get() as $country) {
                    $translations = json_encode($country->translations);
                    foreach ($translations as $key => $translatedCountryName) {
                        if ($translatedCountryName == $countryName) {
                            $countryID = $country->id;
                            break;
                        }
                    }
                }

                // $countryID may still be null
            }
        }

        // Try to get state
        if (!empty($stateName)) {
            $stateEntity = LocationState::where('name', $stateName);
            if (!is_null($countryID)) {
                $stateEntity = $stateEntity->where('country_id', $countryID);
            }

            if ($stateEntity->count() == 1) {
                $stateID = $stateEntity->first()->id;
            } else {
                // $stateID will be null
            }
        }

        // Try to get city
        if (!empty($cityName)) {
            $cityEntity = LocationCity::where('name', $cityName);
            if (!is_null($countryID)) {
                $cityEntity = $cityEntity->where('country_id', $countryID);
            }
            if (!is_null($stateID)) {
                $cityEntity = $cityEntity->where('state_id', $stateID);
            }
            if ($cityEntity->count() == 1) {
                $cityID = $cityEntity->first()->id;
            } else {
                // $cityID will be null
            }
        }

        return [$cityID, $stateID, $countryID];
    }

    /**
     * @return array
     */
    protected function downloadableStatusses(): array
    {
        $statusees = [];
        if (!is_null($this->entegration)) {
            $statusees = explode(",", $this->entegration->statuses);
        }
        return $statusees;
    }

}
