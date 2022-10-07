<?php

use App\Libraries\Shippings\RequestInterface;
use App\Libraries\Shippings\Shipping_1\Requests\Request;

return new class($shipping, $order) extends Request implements RequestInterface {

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
            'originCountryCode' => self::COUNTRY_CODE,
            'originCityName' => self::CITY_NAME,
            'unitOfMeasurement' => 'metric',
            'nextBusinessDay' => 'true', // Eklenmezse tatil gününe denk geldiğinde fiyat vermez.
            'isCustomsDeclarable' => 'true',
        ];
        $this->addAccountNumber();
    }

};
