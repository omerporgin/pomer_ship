<?php

namespace App\Services;

use App\Models\OrderProducts as Item;

class OrderProductService extends abstractService
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
        $this->deletableMsg = 'Item not deletable';
        // Üürn kontrolü gerekli.
        return true;
    }

    /**
     * @return bool
     */
    public function updatable(int $id = null): bool
    {
        $this->updatableMsg = 'Item not updatable';
        return true;
    }

    /**
     * @return bool
     */
    public function getByOrderID($orderID): ?\Illuminate\Database\Eloquent\Collection
    {
        return $this->item->where('order_id', $orderID)->orderBy('sort')->get();
    }

    /**
     * @return bool
     */
    public function getByUniqueId($orderID, $uniqueID): ?Item
    {
        return $this->item->where('order_id', $orderID)->where('unique_id', $uniqueID)->first();
    }

}
