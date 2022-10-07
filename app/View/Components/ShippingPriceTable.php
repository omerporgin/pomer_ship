<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ShippingPriceTable extends Component
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var int|null
     */
    protected $userGroupId;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(int $id, ?int $userGroupId)
    {
        $this->id = $id;
        $this->userGroupId = $userGroupId;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        set_time_limit(0);

        $id = $this->id;
        $userGroupId = $this->userGroupId;

        $cacheName = 'shipping_price.' . $id . '.' . $userGroupId;

        return \Cache::rememberForever($cacheName, function () use ($id, $userGroupId) {
            $service = service('shippingPrices', $id);
            $data = $service->getShipingPricesByUserGroup($userGroupId);
            $data['id'] = $id;
            $view = \View::make('components.shipping-price-table', $data);
            return $view->render();
        });
    }
}
