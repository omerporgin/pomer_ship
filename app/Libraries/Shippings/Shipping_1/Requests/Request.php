<?php

namespace App\Libraries\Shippings\Shipping_1\Requests;

use App\Libraries\Shippings\ShippingRequests;
use Illuminate\Support\Facades\Auth;

class Request extends ShippingRequests
{
    public $shipping;

    public $order;

    /**
     *
     */
    public function __construct($shipping, $order = null)
    {
        $this->shipping = $shipping;
        $this->order = $order;
        $this->user = Auth::user();
        $this->set();
    }

    /**
     * @param $data
     * @return mixed
     */
    public function addAccounts()
    {
        $this->data['accounts'] = [
            [
                "typeCode" => "shipper",
                "number" => $this->shipping->account_number,
            ]
        ];
    }

    /**
     * @return void
     */
    public function addAccountNumber()
    {
        $this->data['accountNumber'] = $this->shipping->account_number;
    }

    /**
     * @return string[]
     */
    public function shipperAddress(): array
    {

        $return = [
            "postalCode" => $this->user->warehouse_postal_code,
            "cityName" => $this->user->state_name,
            "countryCode" => self::COUNTRY_CODE,
            "provinceCode" => self::COUNTRY_CODE,
            "countyName" => $this->user->city_name,
            "countryName" => "Turkey" // Always Turkey
        ];


        $address = $this->user->warehouse_address;
        if (strlen($address) < 45) {
            $return["addressLine1"] = $address; // max:45
        } else {
            $address = explode(' ', $address);
            $line = '';
            $count = 1;
            foreach ($address as $word) {
                if (strlen($line . ' ' . $word) > 45) {
                    $return["addressLine" . $count] = $line; // max:45
                    $count++;
                    if ($count == 4) {
                        break;
                    }
                    $line = $word;
                }
                $line .= ' ' . $word;
            }
        }

        return $return;
    }

    /**
     * @return string[]
     */
    public function pickupAddress(): array
    {
        $return = [
            "postalCode" => $this->order->pickup_post_code,
            "cityName" => $this->order->pickup_state_name,
            "countryCode" => self::COUNTRY_CODE,
            "provinceCode" => self::COUNTRY_CODE,
            "countyName" => $this->order->pickup_city_name,
            "countryName" => "Turkey" // Always Turkey
        ];

        $address = $this->order->pickup_address;
        if (strlen($address) < 45) {
            $return["addressLine1"] = $address; // max:45
        } else {
            $address = explode(' ', $address);
            $line = '';
            $count = 1;
            foreach ($address as $word) {
                if (strlen($line . ' ' . $word) > 45) {
                    $return["addressLine" . $count] = $line; // max:45
                    $count++;
                    if ($count == 4) {
                        break;
                    }
                    $line = $word;
                }
                $line .= ' ' . $word;
            }
        }
        return $return;
    }

    /**
     * @return array
     */
    public function orderAddress(): array
    {
        $location = $this->order->getLocation();
        $countryCode = $location->country->iso2;
        $address = [
            "postalCode" => $this->order->post_code,
            "cityName" => $location->state->name,
            "countryCode" => $countryCode,
            "provinceCode" => $countryCode,
            "countryName" => $location->country->name
        ];

        if (!is_null($location->city->name) and !empty($location->city->name)) {
            $address["countyName"] = $location->city->name;
        }
        return $address;
    }

    /**
     * Our address details.
     *
     * @return string[]
     */
    public function shipperDetails(): array
    {
        $typeCode = "business";
        if ($this->user->user_type == 0) {
            $typeCode = "private"; // Kişisel şahıs şirketi.
        }
        $return = [
            "postalAddress" => $this->shipperAddress(),
            "contactInformation" => [
                "email" => $this->user->email,
                "phone" => strval($this->user->warehouse_phone),
                "mobilePhone" => strval($this->user->warehouse_phone),
                "companyName" => $this->user->real_company_name,
                "fullName" => $this->user->real_owner_name,
            ],
            "typeCode" => $typeCode // "business","direct_consumer","government","other","private","reseller"
        ];

        return $return;
    }
}
