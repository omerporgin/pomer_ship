<?php

namespace App\Services;

use App\Models\Types as Item;

class TypeService extends abstractService
{

    /**
     * @var int
     */
    protected $type = 14;
    /**
     * Repository constructor.
     */
    public function __construct(int $id = null)
    {
        $this->setItem(new Item);
        parent::__construct($id);
    }

    /**
     * Get type by Service name.
     *
     * @param string $serviceName
     */
    public function getByService(string $serviceName): Object
    {
        return $this->item->where('service', $serviceName)->first();
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
}
