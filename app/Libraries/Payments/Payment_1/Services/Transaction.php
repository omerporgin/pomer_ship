<?php

namespace App\Libraries\Payments\Payment_1\Services;

class Transaction extends AbstractServices
{
    /**
     * @var string
     */
    protected $url = "https://www.paytr.com/odeme/durum-sorgu";

    /**
     * paytr merchant_oid
     * example : 5418A1647852585
     *
     * @var null|string
     */
    protected $merchantOid = null;

    /**
     * Paytr | Gets installments
     * (deprecated : fn_paytr_get_installments)
     *
     * @return object|null
     */
    public function execute(): ?object
    {
        $token = base64_encode(hash_hmac('sha256', $this->merchant_id . $this->merchantOid . $this->merchant_salt, $this->merchant_key, true));

        $this->postVals = [
            'merchant_id' => $this->merchant_id,
            'merchant_oid' => $this->merchantOid,
            'paytr_token' => $token,
        ];

        $result = $this->withouthLog()->initCurl();


        return $result;
    }

    /**
     * @param string $id
     * @return void
     */
    public function setMerchantOid(string $id): void
    {
        $this->merchantOid = $id;
    }

}
