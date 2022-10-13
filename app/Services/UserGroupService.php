<?php

namespace App\Services;

use App\Models\UserGroup as Item;
use App\Models\ShippingService;

class UserGroupService extends abstractService
{

    /**
     * Repository constructor.
     */
    public function __construct(int $id = null)
    {
        $this->setItem(new Item);
        parent::__construct($id);
    }

    /**
     * @return bool
     */
    public function deletable(int $id = null): bool
    {
        $this->deletableMsg = 'Items are not deletable';
        return false;
    }

    /**
     * @return bool
     */
    public function updatable(int $id = null): bool
    {
        $this->updatableMsg = 'Items are not updatable';
        return true;
    }

    /**
     * Create table data for item.
     *
     * @param object $filters
     * @return array
     */
    public function getAllFiltered(object $filters): array
    {

        $columns = ['id', 'country', 'active', 'currency', 'code', 'symbol'];

        $filters = $this->validateFilters($filters);

        $list = $this->item;

        // Search
        if (isset($filters->search)) {
            $value = $filters->search['value'];
            if ($value != '') {
                $search_text = explode(' ', $value);
                foreach ($search_text as $text) {
                    $list = $list->orWhere(function ($list) use ($text) {
                        $list
                            ->orWhere('name', 'LIKE', '%' . $text . '%');
                    });
                }
            }
        }

        return [$list, $columns];
    }

    /**
     * Returns sorted price list
     *
     * @param int|null $userGroupId
     * @return array
     */
    public function getPricesOfUserGroup(?int $userGroupId = null): array
    {
        if (is_null($userGroupId)) {
            $userGroupId = $this->getID();
        }
        $priceList = [];
        $prices = \App\Models\UserGroupPrice::where('user_group', $userGroupId)->get();
        foreach ($prices as $price) {

            $priceList[$price->min] = $price->price;
        };

        arsort($priceList);
        return $priceList;
    }

    /**
     * Returns sorted price list
     *
     * @param int $userGroupId
     * @return array
     */
    public function desiPrice(array $priceList, int $requestedDesi): float
    {
        $returnPrice = 0; // default value => %50
        foreach ($priceList as $desi => $price) {
            if ($desi > $requestedDesi) {
                break;
            }
            $returnPrice = $price;
        }
        return (100 + $returnPrice) / 100;
    }

    /**
     * @param int $shippingId
     * @param int $region
     * @param float $desi
     * @return float
     */
    public function getCalculatedPrice(int $region, float $desi): array
    {

        $priceList = $this->getPricesOfUserGroup();
        $increament = $this->desiPrice($priceList, $desi);

        $shippinhPriceService = service('ShippingPrices');
        $return = $shippinhPriceService
            ->get()
            ->where('region', $region)
            ->where('desi', $desi)
            ->whereIn('service', $this->serviceNameList())
            ->get();

        $calculatedPrices = [];
        foreach ($return as $item) {
            $itemArr = $item->toArray();
            $itemArr['price'] *= $increament;

            $today = date("Y-m-d H:i:s");
            $estimatedDelivery = match (strtolower($itemArr['service'])) {
                'medical express' => date('Y-m-d', strtotime($today . ' +1 day')),
                'express 12:00 nondoc' => date('Y-m-d', strtotime($today . ' +3 day')),
                'express worldwide nondoc' => date('Y-m-d', strtotime($today . ' +3 day')),
                'express easy nondoc' => date('Y-m-d', strtotime($today . ' +5 day')),
                default => date('Y-m-d H:i:s', strtotime($today . ' +7 day')),
            };
            $itemArr['estimatedDelivery'] = $estimatedDelivery;
            $itemArr['name'] = $itemArr['service'];
            $calculatedPrices[] = $itemArr;
        }

        return $calculatedPrices;
    }

    /**
     * @param int $countryId
     * @param int $region
     * @param float $desi
     * @return array
     */
    public function getCalculatedPriceByCountryId(int $countryId, float $desi): ?array
    {
        $country = service('LocationCountry', $countryId);
        if ($country->hasItem()) {
            $region = $country->cargo_dhl_id;
            return $this->getCalculatedPrice($region, $desi);
        }
        return null;
    }

    /**
     * @return array
     */
    public function serviceNameList(): array
    {
        return $this->item->userGroupPrice()->pluck('service_name')->toArray();
    }

    /**
     * @return mixed
     */
    public function serviceList()
    {
        $names = $this->serviceNameList();
        return ShippingService::whereIn('name', $names )->get();
    }
}
