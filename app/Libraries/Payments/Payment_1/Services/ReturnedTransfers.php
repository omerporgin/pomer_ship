<?php

namespace App\Libraries\Payments\Payment_1\Services;

class ReturnedTransfers extends AbstractServices
{
    /**
     * @var string
     */
    protected $url = "https://www.paytr.com/odeme/geri-donen-transfer";

    /**
     * @var
     */
    protected $startDate;

    /**
     * @var
     */
    protected $endDate;

    /**
     * @return object|null
     */
    public function execute(): ?object
    {

        $paytr_token = base64_encode(hash_hmac('sha256', $this->merchant_id . $this->startDate . $this->endDate . $this->merchant_salt, $this->merchant_key, true));

        $this->postVals = [
            'merchant_id' => $this->merchant_id,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
            'paytr_token' => $paytr_token
        ];

        $result = $this->initCurl();

        /*
            $result değeri içerisinde dönen yanıt örneği;

            [ref_no] => 1000001
            [date_detected] => 2020-06-10
            [date_reimbursed] => 2020-06-08
            [transfer_name] => ÖRNEK İSİM
            [transfer_iban] => TR100000000000000000000001
            [transfer_amount] => 35.18
            [transfer_currency] => TL
            [transfer_date] => 2020-06-08
        */

        if ($result->status == 'success') {
            // VT işlemleri vs.
        } elseif ($result->status == 'failed') {
            $result->status='error';
            $result->err_msg = "ilgili tarih araliginda islem bulunamadi";
        } else {
            $result->err_msg = $result->err_no . " - " . $result->err_msg;
        }
        return $result;
    }

    /**
     * @param string $date
     */
    public function setStartDate(string $date)
    {
        $this->startDate = $date;
    }

    /**
     * @param string $date
     */
    public function setEndDate(string $date)
    {
        $this->endDate = $date;
    }
}
