<?php

namespace App\Services;

use App\Models\PaymentAccount as Item;
use Illuminate\Support\Facades\Auth;

class PaymentAccountService extends abstractService
{
    /**
     * @var string[]
     */
    protected $statusses = [
        0 => 'waiting',
        1 => 'paid',
        2 => 'cancelled',
    ];

    /**
     * Repository constructor.
     */
    public function __construct(int $id = null)
    {
        $this->setItem(new Item);
        parent::__construct($id);
    }

    /**
     * @param $status
     * @return string
     */
    public function getStatus($status)
    {
        if (isset($this->statusses[$status])) {
            return $this->statusses[$status];
        }
        return '';
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

        if(isset($filters->user_id)){
            $list = $list->where('user_id', $filters->user_id);
        }
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

    /**
     * @return float
     */
    public function getLoggedUsersAccount(): float
    {
        $userID = Auth::id();
        return $this->getUsersAccount($userID);
    }

    /**
     * @param int $userID
     * @return float
     */
    public function getUsersAccount(int $userID): float
    {
        $sum = $this->item->where('user_id', $userID)->where('status', 1)->sum('total');
        return $sum;
    }
}
