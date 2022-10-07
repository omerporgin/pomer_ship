<?php

namespace App\Services;

use App\Models\Permission as Item;
use \App\Models\User;

class PermissionService extends abstractService
{

    protected static $permissions = [
        'admin_see' => 1, // can enter admin page
        'content_see' => 1,
        'content_save' => 1,
        'order_see' => 1,
        'order_save' => 1,
        'setting_save' => 1,
        'setting_see' => 1,
        'user_see' => 1,
        'user_save' => 1,
        'package_see' => 1,
        'package_save' => 1,
    ];

    /**
     * Constructor.
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

        if ($this->getPermissions()->has('user', 'save')) {
            $item = $this->getById($id);

            if ($item->static == 0) {

                if(User::where('permission_id', $id)->count() > 0 ) {
                    $this->deletableMsg = 'In use.';
                    return false;
                }

                return true;
            } else {
                $this->deletableMsg = 'Static permissions are not deletable.';
                return false;
            }
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

        if ($this->getPermissions()->has('user', 'save')) {
            $item = $this->get();
            if ($item->static == 0) {
                return true;
            } else {
                $this->updatableMsg = 'Static permissions are not updatable.';
                return false;
            }
        } else {
            $this->updatableMsg = 'You dont have permission';
        }
        return false;
    }

    /**
     * @return object
     */
    public static function getPermissionArray(): object
    {
        return (object)self::$permissions;
    }

    /**
     * @return object
     */
    public static function getUserPermissions(): object
    {
        return (object)self::$permissions;
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
        $filters = (object)$filters;

        $this->validateFilters($filters, $columns);

        $list = $this->item;

        // Search
        if (isset($filters->search)) {
            $value = $filters->search['value'];
            if ($value != '') {
                $search_text = explode(' ', $value);
                foreach ($search_text as $text) {
                    $list = $list->where(function ($list) use ($text) {
                        $list
                            ->orWhere('name', 'LIKE', '%' . $text . '%')
                            ->orWhere('id', $text);
                    });
                }
            }
        }

        return [$list, $columns];
    }

    /**
     * Get payment by Service name.
     *
     * @param string $serviceName
     */
    public function getByService(string $serviceName): object
    {
        return $this->item->where('service', $serviceName)->first();
    }

    /**
     * Save product
     *
     * @param $data
     */
    public function save($data): array
    {
        if (isset($data['id']) and !is_null($data['id'])) {
            $this->item = $this->item::find($data['id']);
        }
        $item = $this->item;
        $item->name = $data['name'];

        unset($data['name']);
        unset($data['id']);
        $item->permission = json_encode($data);

        $result = false;
        if ($item->save()) {
            $result = true;
        }

        return [
            'result' => $result,
            'item' => $item->fresh()
        ];
    }
}
