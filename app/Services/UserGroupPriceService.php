<?php

namespace App\Services;

use App\Models\UserGroupPrice as Item;

class UserGroupPriceService extends abstractService
{

    /**
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
        return true;
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
     * Create table data for item.
     *
     * @param object $filters
     * @return array
     */
    public function getAllFiltered(object $filters): array
    {

        $columns = ['id', 'shipping_id', 'is_default', 'min', 'max', 'price', 'discount'];

        $user_group = $filters->user_group;

        $filters = $this->validateFilters($filters);

        $list = $this->item->where('user_group', $user_group);

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
}
