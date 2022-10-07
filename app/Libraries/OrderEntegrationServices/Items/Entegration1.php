<?php
/**
 * OPEN CART ENTEGRATION
 */
namespace App\Libraries\OrderEntegrationServices\Items;

use App\Libraries\OrderEntegrationServices\AbstrackOrderEntegrationService;
use Illuminate\Support\Facades\Http;
use function session;

class Entegration1 extends AbstrackOrderEntegrationService
{

    /**
     * @var string
     */
    protected $token = '';

    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return void
     */
    public function execute(): bool
    {

        if (!$this->getToken()) {
            $this->err = 'Token alınamıyor';
            return false;
        }

        $orderUrl = 'http://127.0.0.1/opencart_2.3.0.2/upload/index.php';
        $order = (object)Http::get($orderUrl, [
            'route' => 'api/order/info',
            'api_token' => session('token'),
            'order_id' => '1',
        ]);
        $order = json_decode($order->body());

        $this->addOrder($order);
        $this->saveOrders();
        return true;
    }

    /**
     * Creates and sets opencart app token (For Open Cart version 2.*)
     *
     *
     * @return void
     */
    private function getToken(): bool
    {
        if (session()->has('token')) {
            return true;
        }

        $url = 'http://127.0.0.1/opencart_2.3.0.2/upload/index.php?route=api/login';
        $key = 'IYhQEeQEB2RNPlAz6oSO3JJ4trRaSHmX0fiAHYSsXfNDelHJmIb2Z7Pjv5OzSqqpvvAywbnjiDGbYN6kzLb3XQZv315Iftxrpakh0ydiFurii2K0ol7fTynqziLBF1XqmUVIZSRjMnEMVfsQGnKRQxmuniwa1UfmAKZ5CzbQWsdd5L6hXad8aJewKuegKONTci3AbHZDzIU5YYd2rljk1krhAywAVfXNS2xjS4rZyRZfBPoLpJkSsf4kzukNa2dN';
        $user = 'api_user';

        $response = (object)Http::asForm()->post($url, [
            'key' => $key,
            'user' => $user
        ])->json();

        if (isset($response->token)) {
            $this->token = $response->token;
            session(['token'=>$response->token]);
            return true;
        }
        return false;

    }
}
