<?php

namespace App\Services;

use App\Models\Payment as Item;

class PaymentService extends abstractService
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

        $columns = ['id', 'name'];

        $list = $this->item;

        $value = $filters->search['value'];

        // Search
        if (isset($filters->search)) {
            if ($value != '') {
                $search_text = explode(' ', $value);
                foreach ($search_text as $text) {
                    $list = $list->where(function ($list) use ($text) {
                        $list
                            ->orWhere('id', $text)
                            ->orWhere('name', 'LIKE', '%' . $text . '%')
                            ->orWhere('email', 'LIKE', '%' . $text . '%');
                    });
                }
            }
        }

        return [$list, $columns];
    }
}
