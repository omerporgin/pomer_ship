<?php

use App\Libraries\Shippings\RequestInterface;
use App\Libraries\Shippings\Shipping_1\Requests\Request;

return new class($shipping, $order) extends Request implements RequestInterface {

    /**
     * @return array
     */
    public function __construct($shipping, $order)
    {
        if (is_null($order)) {
            $this->setError('order_id required');
        } else {
            $this->order = $order;
            parent::__construct($shipping);
        }
    }

    /**
     * @return array
     */
    public function set()
    {

    }

};
