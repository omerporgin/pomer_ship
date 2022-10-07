<?php

namespace App\Services;

use App\Models\OrderStatuses as Item;

class OrderStatusService extends abstractService
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
        return false;
    }

    /**
     * @return bool
     */
    public function updatable(int $id = null): bool
    {
        $this->updatableMsg = 'Item not updatable';
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

        $columns = ['id', 'name', 'id', 'id'];

        $filters = (object)$filters;

        $list = $this->item;

        if (isset($filters->status_of)) {
            $list = $list->where('status_of', $filters->status_of);
        }

        if (isset($filters->show_on_menu)) {
            $list = $list->where('show_on_menu', $filters->show_on_menu);
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
}
