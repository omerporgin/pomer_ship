<?php

namespace App\Libraries\Payments\Payment_1\Services;

use function App\Payments\Payment_1\Services\fn_get_order_info;
use function App\Payments\Payment_1\Services\getMerchantOIDByOrder;

if (!defined('BOOTSTRAP')) {
    die('Access denied');
}

class CancelTransfer extends AbstractServices
{
    /**
     * @var string
     */
    protected $url = "https://www.paytr.com/odeme/iade";

    /**
     * @var int
     */
    protected $returnAmount = 0;

    /**
     * @var string
     */
    protected $merchantOId;

    /**
     * @return object|null
     */
    public function execute(): ?object
    {
        if ($this->returnAmount > 0) {
            $paytr_token = base64_encode(hash_hmac('sha256', $this->merchant_id . $this->merchantOId . $this->returnAmount . $this->merchant_salt, $this->merchant_key, true));

            $this->postVals = [
                'merchant_id' => $this->merchant_id,
                'merchant_oid' => $this->merchantOId,
                'return_amount' => $this->returnAmount,
                'paytr_token' => $paytr_token,
                //'reference_no' => $reference_no,
            ];

            $result = $this->initCurl();

            if ($result->status == 'success') {

                // Void

            } elseif ($result->status == 'failed') {
                $result->status = 'error';
                $result->err_msg = "ilgili tarih araliginda islem bulunamadi";
            } else {
                $result->err_msg = $result->err_no . " - " . $result->err_msg;
            }
        } else {
            return (object)[
                'status' => 'error',
                'err_msg' => 'Miktar 0 dan büyük olmalı.',
            ];
        }
        return $result;
    }

    /**
     * Price must be price * 100
     *
     * @param string $amount
     */
    public function setReturnAmount(float $amount): void
    {
        $this->returnAmount = $amount;
    }

    /**
     * setMerchantOId() -> method is replaced with setMerchantOIdByOrder()
     *
     * @param int $orderId
     */
    public function setMerchantOIdByOrder(int $orderId): ?string
    {
        $order = fn_get_order_info($orderId);
        $this->merchantOId = getMerchantOIDByOrder($order);
        return $this->merchantOId;
    }

    /**
     * @param int $orderId
     */
    public function setMerchantOId(string $merchantOId): ?string
    {
        $this->merchantOId = $merchantOId;
        return $this->merchantOId;
    }

    /**
     * Order can be cancelled, if method returns empty string.
     *
     * @param array $order
     * @return string
     */
    public static function cancellable(array $orderInfo): string
    {

        if (empty($orderInfo)){
            return 'Order error.';
        }

        if (!isset($orderInfo['payment_method'])){
            return 'Payment method is not set.';
        }

        if($orderInfo['payment_method']['processor'] != 'Paytr') {
            return 'Payment method is not paytr.';
        }

        if (!in_array($orderInfo['status'], ['P', 'C'])) {
            // return 'Payment status must be (P) Paid.';
        }

        // Check merchand_oid
        if (is_null(getMerchantOIDByOrder($orderInfo))) {
            return 'MerchantOid does not exist.';
        }

        return '';
    }

}
