<?php

namespace App\Services;

use App\Models\OrderPackages as Item;
use App\Models\OrderProducts;
use Illuminate\Database\Eloquent\Collection;

class OrderPackageService extends abstractService
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
        if ($this->getPermissions()->has('setting', 'save')) {
            return true;
        } else {
            $this->deletableMsg = 'You dont have permission';
        }
        return false;
    }

    /**
     * @return bool
     */
    public function updatable(int $id = null): bool
    {
        if ($this->getPermissions()->has('setting', 'save')) {
            return true;
        } else {
            $this->updatableMsg = 'You dont have permission';
        }
        return false;
    }

    /**
     * Create table data for item.
     *
     * @param object $filters
     * @return array
     */
    public function getAllFiltered(object $filters): array
    {

        $columns = ['id', 'order_id', 'weight'];

        $list = $this->item;
        //$list = $list->where('_id', Auth()->id());

        if (isset($filters->type)) {
            switch ($filters->type) {
                case 'confirmed':
                    $list = $list->whereNotNull('tracking_number');
                    break;
                case 'waiting':
                    $list = $list->whereNull('tracking_number');
                    break;
                default:
                    break;

            }

        }

        if (isset($filters->vendorList) and !empty($filters->vendorList)) {
            $list = $list->whereIn('vendor_id', $filters->vendorList);
        }

        // Search
        if (isset($filters->search)) {
            $value = $filters->search['value'];
            if ($value != '') {
                $search_text = explode(' ', $value);
                foreach ($search_text as $text) {
                    $list = $list->where(function ($list) use ($text) {
                        $list
                            ->orWhere('id', $text);
                    });
                }
            }
        }

        return [$list, $columns];
    }

    /**
     * @param int $orderID
     * @return Collection
     */
    public static function getOrdersPackages(int $orderID): Collection
    {
        $idList = OrderProducts::select('package_id')
            ->where('order_id', $orderID)
            ->distinct('package_id')
            ->get()
            ->pluck('package_id')
            ->toArray();

        return Item::whereIn('id', $idList)->get();
    }
}
