<?php

namespace App\Libraries\Shippings\Shipping_2\Requests;

use App\Libraries\Shippings\ShippingRequests;
use App\Traits\ErrorTrait;

class Request extends ShippingRequests
{
    /**
     *
     */
    public function __construct($shipping)
    {
        $this->shipping = $shipping;
        $this->set();
    }

    /**
     * @return array
     */
    public function build(?array $newData = null): array
    {
        $this->data = array_merge($this->data, $newData);
        return $this->data;
    }

    /**
     * @return void
     */
    public function addAccountNumber(): void
    {
        $this->data['PickupCreationRequest']['Shipper'] = [
            "Account" => [
                "AccountNumber" => $this->shipping->account_number,
                "AccountCountryCode" => self::COUNTRY_CODE
            ]
        ];
    }

    public function shipperAddress(): array
    {
        return [
            "CompanyName" => "Pickup Proxy",
            "ContactName" => "Pickup Manager",
            "AddressLine" => "315 Saddle Bridge Drive",
            "Room" => "R01",
            "Floor" => "2",
            "City" => self::CITY_NAME,
            "StateProvince" => "NJ",
            "Urbanization" => "",
            "PostalCode" => "07401",
            "CountryCode" => self::COUNTRY_CODE,
            "ResidentialIndicator" => "Y",
            "PickupPoint" => "Lobby",
            "Phone" => [
                "Number" => "6785851399",
                "Extension" => "911"
            ]
        ];
    }

}
