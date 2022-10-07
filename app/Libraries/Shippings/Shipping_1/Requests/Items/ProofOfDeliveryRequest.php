<?php

use App\Libraries\Shippings\RequestInterface;
use App\Libraries\Shippings\Shipping_1\Requests\Request;

return new class($shipping) extends Request implements RequestInterface {

    /**
     * @return array
     */
    public function __construct($shipping)
    {
        parent::__construct($shipping);
    }

    /**
     * @return array
     */
    public function set()
    {
        $this->data = [

        ];
        $this->addAccountNumber();
    }
};
