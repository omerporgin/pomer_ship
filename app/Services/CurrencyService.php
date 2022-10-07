<?php

namespace App\Services;

use App\Models\Currency as Item;

class CurrencyService extends abstractService
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
        $id = $this->currentIfNull($id);

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
        $id = $this->currentIfNull($id);

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

        $columns = ['id', 'country', 'active', 'currency', 'code', 'symbol'];
        $filters = (object)$filters;

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
                            ->orWhere('country', 'LIKE', '%' . $text . '%')
                            ->orWhere('currency', 'LIKE', '%' . $text . '%')
                            ->orWhere('code', 'LIKE', '%' . $text . '%')
                            ->orWhere('id', $text);
                    });
                }
            }
        }

        return [$list, $columns];
    }

    /**
     * @param string $code
     * @return mixed|null
     */
    public static function getCurrencyIdByCode(string $code)
    {
        if (!is_null($currency = Item::where('code', $code)->first())) {
            return $currency->id;
        }
        return null;
    }
}
