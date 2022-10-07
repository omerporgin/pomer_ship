<?php

namespace App\Services;

use App\Models\Notifications as Item;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class NotificationService extends abstractService
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

        $columns = ['id', 'notification', 'is_read', 'data', 'date'];
        $filters = (object)$filters;

        $list = $this->item;

        if (isset($filters->user_id)) {
            $list = $list->where('user_id', $filters->user_id);
        }

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
     * @param int $limit
     * @return void
     */
    public function getLastNotificationsOfLoggedUser(int $limit = 10): Collection
    {
        $loggedUserId = Auth::id();
        return $this->item->where('user_id', $loggedUserId)->orderBy('id')->take($limit)->get();
    }
}
