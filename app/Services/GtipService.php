<?php

namespace App\Services;

use App\Models\Gtip as Item;
use Illuminate\Database\Eloquent\Collection;

class GtipService extends abstractService
{
    /**
     * Service constructor.
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

        if(!is_null($id)){
            $this->getById($id, true);
        }

        if(is_null($this->item->gtip)){
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    public function updatable(int $id = null): bool
    {
        $this->updatableMsg = 'Item not updatable';

        if(!is_null($id)){
            $this->getById($id, true);
        }

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

        $columns = ['id', 'gtip', 'description', 'unit'];
        $filters = (object)$filters;

        $list = $this->item;

        // Search
        if (isset($filters->search)) {
            $value = $filters->search['value'];
            if ($value != '') {
                $search_text = explode(' ', $value);
                foreach ($search_text as $text) {
                    $list = $list->orWhere(function ($list) use ($text) {
                        $list
                            ->orWhere('description', 'LIKE', '%' . $text . '%')
                            ->orWhere('search', 'LIKE', '%' . $text . '%')
                            ->orWhere('gtip', $text)
                            ->orWhere('id', $text);
                    });
                }
            }
        }

        return [$list, $columns];
    }

    /**
     * @param string $searchText
     * @return Collection
     */
    public function selectGtip(string $searchText): Collection
    {
        $list = $this->item->where('is_selectable', 1)->where(function ($list) use ($searchText) {
            $list
                ->orwhere('description', 'LIKE', '%' . $searchText . '%')
                ->orwhere('search', 'LIKE', '%' . $searchText . '%');
        })->get();

        return $list;
    }

}
