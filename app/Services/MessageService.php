<?php

namespace App\Services;

use App\Models\Messages as Item;

class MessageService extends abstractService
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

        if(!is_null($id)){
            $this->getById($id, true);
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

        $columns = ['id', 'email', 'name', 'active'];

        $filters = (object)$filters;

        $list = $this->item;

        // Admin or custom user
        if (isset($filters->type)) {
            switch ($filters->type) {
                case "admins":
                    $list = $list->where('permission_id', '>', 12);
                    break;
                case "vendors":
                    $list = $list->where('permission_id', '<', 13);
                    break;
                case "all":
                    break;
                default:
                    break;
            }
        }

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
