<?php
/**
 * CS CART ENTEGRATION
 */

namespace App\Libraries\OrderEntegrationServices\Items;

use App\Libraries\OrderEntegrationServices\AbstrackOrderEntegrationService;
use App\NotificationService\NotificationService;
use Illuminate\Support\Facades\Http;

class Entegration2 extends AbstrackOrderEntegrationService
{

    /**
     * @var string
     */
    protected $token = '';

    /**
     * @var string
     */
    protected $url = '';

    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * STEP 1 - Get order list
     *
     * CsCart user token sadece o tokena tanımlanan vendorlara ait siparişleri indirebilir.
     *
     * @return bool
     */
    public function getOrderList(): bool
    {
        //dd('Checking beetween ' .$this->getLastDate().' to '.$this->getFirstDate());

        $parameters = [
            'items_per_page' => 100,
            'updated_at_to' => strtotime($this->getLastDate()),
            'updated_at_from' => strtotime($this->getFirstDate()),
        ];

        $parameterList = [];
        foreach ($parameters as $key => $param) {
            $parameterList[] = $key . '=' . $param;
        }

        try {

            // Get service
            $url = trim($this->entegration->url, "/  \n\r\t\v\ \\") . '/api/4.0/orders?' . implode('&', $parameterList);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_USERPWD, $this->entegration->user . ":" . $this->entegration->pass);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $body = curl_exec($ch);
            curl_close($ch);
            $response = json_decode($body);

        } catch (\Exception $e) {
            $this->setError($e->getMessage());
            return false;
        }

        if (isset($response->status)) {
            switch ($response->status) {
                case '401':
                    $this->setError($response->message);
                    break;
                default:
                    $this->setError('Without error message');
                    break;
            }

            return false;
        } else {

            if (intVal($response->params->total_items) > 0) {
                $this->getOrderInfos($response->orders);

                return true;
            } else {
                $this->setError(_('Order not found!'));
                NotificationService::notify($this->err);

                return false;
            }

            $this->updateLastDate();
        }
        return false;
    }

    /**
     *  STEP 2 - Get order info from cs-cart
     *
     * @param $orderList
     * @return array|void
     */
    private function getOrderInfos($orderList)
    {

        $statusNotAccepted = 0;

        foreach ($orderList as $order) {

            // Statusu uygun değilse tüm siparişi almaya gerek yok.
            if (!in_array($order->status, $this->downloadableStatusses())) {
                continue;
            }

            if ($order->is_parent_order == 'Y') {
                continue;
            }

            $parameters = [
                'add_english_name' => 'true',
            ];

            $parameterList = [];
            foreach ($parameters as $key => $param) {
                $parameterList[] = $key . '=' . $param;
            }

            $url = trim($this->entegration->url, "/  \n\r\t\v\ \\") . '/api/4.0/orders/' . $order->order_id . '?' .
                implode('&', $parameterList);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_USERPWD, $this->entegration->user . ":" . $this->entegration->pass);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $body = curl_exec($ch);
            curl_close($ch);
            $order = json_decode($body);

            if (!$this->isDownloadable($order)) {
                \Log::info(json_encode($order));
                continue;
            }

            $formattedOrder = $this->formatOrder($order);

            $this->addOrder($formattedOrder);
        }

        if ($statusNotAccepted > 0) {
            $msg = count($orderList) . ' orders downloaded. ';
            NotificationService::notify($msg);
        }
    }

    /**
     * @param object $order
     * @return bool
     */
    protected function isDownloadable(object $order): bool
    {

        if (!in_array($order->status, $this->downloadableStatusses())) {
            return false;
        }

        if ($order->s_country == 'TR') {
            return false;
        }

        // Only orders with this shipping id
        if (!in_array($order->shipping[0]->shipping_id, [137, 138])) {
            return false;
        }

        return true;
    }

    /**
     * Formatlanmalı ve içe aktarılan tüm orderlar aynı formatta olmalı.
     * cscartta "s_" ile başlayanlar shipping. Bunlar alınmalı. "b_" ile başlayanalr billing.
     *
     * @param $order
     * @return mixed
     */
    protected function formatOrder($order)
    {
        $packageWeight = 0;
        $packageWidth = 0;
        $packageHeight = 0;
        $packageLength = 0;

        $curencyList = json_decode($order->payment_info->currency_list_json, true);


        $order->order_id = (string)$order->order_id;
        $order->entegration_id = $this->entegrationID();
        $order->total_price = $order->total;
        $order->order_date = date("Y-m-d H:i:s", $order->timestamp);
        $order->firstname = $order->s_firstname;
        $order->lastname = $order->s_lastname;
        $order->address = $order->s_address . ' ' . $order->s_address_2;
        $order->post_code = $order->s_zipcode;
        $order->phone = $order->s_phone;
        $order->status = $this->convertStatus($order->status);
        $order->invoice_no = $order->order_id;

        // Locations
        list($cityID, $stateID, $countryID) = $this->convertLocation([
            'cityName' => $order->s_city,
            'stateName' => $order->s_state,
            'countryIso' => $order->s_country,
            'postCode' => $order->post_code,
        ]);
        $order->city_id = $cityID;
        $order->country_id = $countryID;
        $order->state_id = $stateID;

        // Add products
        foreach ($order->products as $product) {

            $quantity = $product->amount;

            $unique_id = $product->item_id;
            $total = $product->price * $product->amount;
            $unique_id = $product->item_id;
            $order->product_id[] = null;
            $name = $product->product;
            if (isset($product->name_in_english)) {
                $name = $product->name_in_english;
            }
            foreach ($order->product_groups[0]->package_info->packages as $interPackage) {
                $productWeight = 0;
                $productWidth = 0;
                $productHeight = 0;
                $productLength = 0;

                if (isset($interPackage->products->{$unique_id})) {
                    if (isset($interPackage->shipping_params)) {
                        $params = $interPackage->shipping_params;
                        $productLength = isset($params->box_length) ? $params->box_length : 0;
                        $productWidth = isset($params->box_width) ? $params->box_width : 0;
                        $productHeight = isset($params->box_height) ? $params->box_height : 0;
                        $productWeight = isset($interPackage->weight) ? $interPackage->weight : 0;
                    }
                }

                $order->product_length[] = $productLength;
                $order->product_width[] = $productWidth;
                $order->product_height[] = $productHeight;
                $order->product_desi[] = $productWeight * $quantity;

                $packageWeight += $productWeight * $quantity;
                $packageWidth += $productWidth;
                $packageHeight += $productHeight;
                $packageLength += $productLength;

                //min($productLength,$productWidth, $productHeight);

            }
            $order->name[] = $name;
            $order->unique_id[] = $unique_id;
            $order->type[] = 0;
            $order->quantity[] = $quantity;
            $order->declared_quantity[] = $product->amount;
            $order->unit_price[] = $product->price / $curencyList['EUR'];
            $order->gtip_code[] = 'gtip_code';
            $order->sku[] = 'sku';
        }

        // Add shipping price
        if ($order->shipping_cost > 0) {
            $order->product_id[] = null;
            $order->name[] = 'Shipping : ' . $order->shipping[0]->shipping;
            $order->type[] = 1;
            $order->quantity[] = 1;
            $order->declared_quantity[] = 1;
            // Cs-Cart'ta TL geliyor, currency ile bölünmeli
            $order->unit_price[] = $order->shipping_cost / $curencyList['EUR'];
            $order->gtip_code[] = '';
            $order->sku[] = 'sku';
        }

        // Add payment surcharge
        if ($order->payment_surcharge > 0) {
            $order->product_id[] = null;
            $order->name[] = 'Payment : ' . $order->shipping[0]->shipping;
            $order->type[] = 2;
            $order->quantity[] = 1;
            $order->declared_quantity[] = 1;
            // Cs-Cart'ta TL geliyor, currency ile bölünmeli
            $order->unit_price[] = $order->payment_surcharge / $curencyList['EUR'];
            $order->gtip_code[] = '';
            $order->sku[] = 'sku';
        }

        // Package dimentions
        $order->package_width[] = $packageWidth;
        $order->package_height[] = $packageHeight;
        $order->package_length[] = $packageLength;
        $order->package_weight[] = $packageWeight;
        $order->package_id[] = 0;
        $order->package_list = count($order->product_id);

        $order->shipped_at = $this->plannedShippingDate();

        return $order;
    }

    /**
     * @return void
     */
    protected function plannedShippingDate()
    {
        if (date('H') < 12) { // 24-hour format
            return date('Y-m-d'); // today
        }
        return date('Y-m-d', strtotime(' +1 Weekdays'));
    }
}
