<?php

namespace App\Services;

use App\Models\User as Item;
use App\Services\Traits\ImageTrait;
use \App\Models\Order;

class UserService extends abstractService
{
    use ImageTrait;

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

        $this->deletableMsg = 'Item not deletable';

        if (Order::where('vendor_id', $id)->count() > 0) {
            $this->deletableMsg = 'Has orders.';
            return false;
        }
        return true;

    }

    /**
     * @return bool
     */
    public function updatable(int $id = null): bool
    {
        $id = $this->currentIfNull($id);

        $this->updatableMsg = 'Item not updatable';

        if (!is_null($id)) {
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

        $columns = ['id', '', 'name', 'surname', 'email', 'permission_id', 'user_type', 'active'];

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
