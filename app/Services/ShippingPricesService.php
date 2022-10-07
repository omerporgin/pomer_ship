<?php

namespace App\Services;

use App\Models\ShippingPrices as Item;

class ShippingPricesService extends abstractService
{

    /**
     * Repository constructor.
     * $shippingId required for price service too
     *
     * @param int|null $id
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
        return false;
    }

    /**
     * @param int|null $user
     * @return array
     */
    public function getShipingPricesByUserGroup(?int $userGroupId, $shippingServiceName = null): array
    {
        // Get prices
        $userGroupService = service('UserGroup');
        $priceList = [];
        if (!is_null($userGroupId)) {
            $priceList = $userGroupService->getPricesOfUserGroup($userGroupId);
        }

        $shippingId = $this->getID();
        $return = [];
        $regionList = $this->item->where('shipping_id', $shippingId)->select('region')->distinct()->pluck('region')
            ->toArray();
        sort($regionList);

        $serviceList = [$shippingServiceName];
        if (is_null($shippingServiceName)) {
            $serviceList = $this->item->where('shipping_id', $shippingId)->select('service')->distinct()->pluck('service')
                ->toArray();
            sort($serviceList);
        }

        $desiList = $this->item->where('shipping_id', $shippingId)->select('desi')->distinct()->pluck('desi')
            ->toArray();
        sort($desiList);

        foreach ($serviceList as $service) {
            foreach ($desiList as $desi) {
                foreach ($regionList as $region) {
                    $price = [
                        'price' => '-',
                        'currency' => ''
                    ];
                    if (!is_null($row = $this->item->where('service', $service)->where('shipping_id', $shippingId)
                        ->where('region', $region)->where('desi', $desi)->first())) {

                        $adjustedPrice = $row->price * $userGroupService->desiPrice($priceList, $desi);

                        $price = [
                            'price' => $adjustedPrice,
                            'currency' => $row->currency
                        ];
                    }
                    $return[$service][$desi][$region] = $price;

                }

            }
        }

        $returnArr = [
            'list' => $return,
            'regionList' => $regionList,
            'desiList' => $desiList,
        ];
        if (is_null($shippingServiceName)) {
            $returnArr['serviceList'] = $serviceList;
        }
        return $returnArr;
    }
}
