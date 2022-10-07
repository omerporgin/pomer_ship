<?php

namespace App\Services;

use App\Models\Shipping as Item;
use App\Services\Traits\ImageTrait;

class ShippingService extends abstractService
{
    use ImageTrait;

    public static ?int $type = 14;

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

        $columns = ['id', '', 'account_number', 'name', 'is_active'];

        $list = $this->item;

        // Search
        if (isset($filters->search)) {
            $value = $filters->search['value'];
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
