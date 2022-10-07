<?php

namespace App\Libraries\Payments\Payment_1\Services;

class PlatformTransfer extends AbstractServices
{

    /**
     * @var array
     */
    protected $vendor = [];

    /**
     * @var string
     */
    protected $url = "https://www.paytr.com/odeme/platform/transfer";

    /**
     * @return object|null
     */
    public function execute(): ?object
    {
        if (!empty($this->vendor)) {
            $this->vendor->totalAmount = $this->fixPrice($this->vendor->totalAmount);
            $this->vendor->submerchantAmount = $this->fixPrice($this->vendor->submerchantAmount);

            $hash_str = $this->merchant_id . $this->vendor->merchant_oid . $this->vendor->transId . $this->vendor->submerchantAmount . $this->vendor->totalAmount . $this->vendor->transferName . $this->vendor->transferIban;
            $token = base64_encode(hash_hmac('sha256', $hash_str . $this->merchant_salt, $this->merchant_key, true));

            $this->postVals = [
                'merchant_id' => $this->merchant_id,
                'merchant_oid' => $this->vendor->merchant_oid,
                'trans_id' => $this->vendor->transId,
                'submerchant_amount' => $this->vendor->submerchantAmount,
                'total_amount' => $this->vendor->totalAmount,
                'transfer_name' => $this->vendor->transferName,
                'transfer_iban' => $this->vendor->transferIban,
                'paytr_token' => $token,
                'company' => $this->vendor->company,
            ];

            $result = $this->initCurl();

            if ($result->status == 'error') {
                self::error_log(__FUNCTION__, $result->err_msg);
            }
            /*
                Başarılı yanıt örneği:
                {"status":"success", "merchant_amount":"5", "submerchant_amount":"92", "trans_id":"45ABT34", "reference":"12SF45" }

                Başarısız yanıt örneği:
                {"status":"error", "err_no":"010", "err_msg":"toplam transfer tutarı kalan tutardan fazla olamaz"}
            */
        } else {
            return (object)[
                'status' => 'error',
                'err_msg' => 'vendor required.',
            ];
        }
        return $result;
    }

    /**
     * @param array $vendor
     */
    public function setVendor(array $vendor)
    {
        $this->vendor = (object)$vendor;
    }
}
