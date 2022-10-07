<?php

namespace App\Libraries\Shippings;

class Factory
{

    /**
     * @param string $requestName
     * @param $shipping
     * @param $order
     * @return false|mixed
     */
    public static function request(string $requestName, $shipping, $order = null)
    {

        $requestName = str_replace('Request', '', $requestName);
        $requestName = ucfirst($requestName);
        $fileName = 'Shipping_' . $shipping->id . '/Requests/Items/' . $requestName . 'Request.php';

        $obj = include $fileName;

        if ($obj->hasError()) {
            return false;
        }
        return $obj;
    }

    /**
     * @param int $shippingId
     * @param string $dir
     * @return mixed|null
     */
    public static function shipping(int $shippingId)
    {
        $fileName = "Shipping_" . $shippingId . "/Shipping.php";
        $obj = include $fileName;

        if ($obj->hasError()) {
            return false;
        }
        $service = service('Shipping', $shippingId);
        $obj->setShipping($service->get());
        return $obj;
    }
}
