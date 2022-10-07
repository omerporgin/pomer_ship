<?php

namespace App\Entities\Eloquent;

use \App\Services\UserService as Service;
use \App\Repositories\Eloquent\LocationDistrictRepository;
use \App\Services\AppointmentUserWorkingTimeService;
use \App\Services\UserProductService;

class User extends abstractEntity
{
    use Traits\ImageTrait;

    /**
     * 
     */
    public $orderList = null;

    /**
     * 
     */
    public function __construct(int $id = null)
    {
        $this->setService(app()->make(Service::class));
        if (!is_null($id)) {
            $this->setItemById($id);
        }
        parent::__construct();
    }

    /**
     * Users order list 
     * 
     * @return null|object 
     */
    public function orders(): ?object
    {

        if (is_null($this->item) or is_null($this->item->id)) {
            return null;
        }

        $orders =  $this->item->order()->get();
        foreach ($orders as $order) {
            $address =  entity('Address', $order->address_id);
            $order->location  = $address->countryTree();

            $order->statusName = '?';
            if (!is_null($status = $order->orderStatus()->first())) {
                $order->statusName = $status->name;
            }
        }
        $this->orderList = $orders;
        return $orders;
    }

    /**
     * Can use apppointment or not
     * 
     * All admins (permissin_id > 13) can use
     * 
     * @return bool
     */
    public function canAppointment(): bool
    {
        if (!isset($this->permission_id)) {
            return false;
        }
        if ($this->permission_id >= 13) {
            return true;
        }
        return false;
    }

    /**
     * 
     */
    public function appointmentData()
    {
        if (is_null($this->item) or is_null($this->item->id)) {
            return null;
        }

        if (!$this->canAppointment()) {
            return null;
        }
        $service = app()->make(AppointmentUserWorkingTimeService::class);
        return  $service->getByUserID($this->item->id);
    }

    /**
     * 
     */
    public function serviceList()
    {
        if (is_null($this->item) or is_null($this->item->id)) {
            return null;
        }

        if (!$this->canAppointment()) {
            return null;
        }
        $service = app()->make(UserProductService::class);
        return  $service->getServicesByUserID($this->item->id);
    }

    /**
     * Returns list of users by their permission = team members
     * look at DB:user_permissions
     * 
     * @param int $permissionId
     * @return object
     */
    public static function getByPermission(int $permissionId): array
    {
        $service = (new static)->service;
        $return = [];
        foreach ($service->get()->where('permission_id', $permissionId)->get() as $item) {

            $self = new static;
            $self->setItem($item);
            $return[] = $self;
        }
        return $return;
    }
}
