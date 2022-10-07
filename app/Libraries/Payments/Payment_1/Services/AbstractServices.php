<?php

namespace App\Libraries\Payments\Payment_1\Services;

use App\Payments\Payment_1\Services\Log;

abstract class AbstractServices
{

    /**
     * @var int
     */
    const DIGIT = 2;

    /**
     * If false request will not be logged
     *
     * @var bool
     */
    protected $isLogged = true;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var string
     */
    protected $merchant_id;

    /**
     * @var string
     */
    protected $merchant_key;

    /**
     * @var string
     */
    protected $merchant_salt;

    /**
     * @var int
     */
    protected $timeOut;

    /**
     * @var int
     */
    protected $connectionTimeOut;

    /**
     * @var bool
     */
    protected $isTest = false;

    /**
     * @var array
     */
    protected $postVals;

    /**
     * @var string
     */
    private $errMsg;

    /**
     * @var array
     */
    protected $result;

    /**
     * mobile data
     *
     * @var bool
     */
    protected $isMobile = false;

    /**
     * mobile data
     *
     * @var int
     */
    protected $total = 0;

    /**
     *
     */
    abstract function execute(): ?object;

    public function __construct()
    {
        $data = $this->getPaymentData();
        $this->merchant_id = $data->user;
        $this->merchant_key = $data->key;
        $this->merchant_salt = $data->pass;
        $this->timeOut = 90;
        $this->connectionTimeOut = 90;
        $this->isTest = $data->is_test;
    }

    /**
     * returns payment data
     *
     * @return mixed
     */
    protected function getPaymentData()
    {
        if (is_null($data = \App\Models\Payment::where('id', 1)->first())) {
            throw new \InvalidArgumentException('Paytr data is not set!');
        }
        return $data;
    }

    protected function initCurl()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->postVals);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeOut);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->connectionTimeOut);


        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);


        $this->result = @curl_exec($ch);

        $this->log();

        if (curl_errno($ch)) {
            $this->errMsg = curl_error($ch);
            error_log("PAYTR SERVICE : " . __CLASS__ . " " . $this->errMsg);
            curl_close($ch);
            return null;
        }

        curl_close($ch);
        $return = json_decode($this->result, 1);
        return (object)$return;
    }

    /**
     * Disable log
     */
    protected function withouthLog(): self
    {
        $this->isLogged = false;
        return $this;
    }

    /**
     * Logs every service request to './var/paytr_logs/'
     */
    protected function log(): void
    {
        if ($this->isLogged) {
            $isTest = 'false';
            if ($this->isTest) {
                $isTest = 'true';
            }
            (new Log)->setData([
                'url' => $this->url,
                'Post vals' => $this->postVals,
                'Result' => $this->result,
                'is_test' => $isTest,
            ])->filter()->toTelegram()->toFile();
        }
    }

    /**
     *
     * @param $price
     * @return int
     */
    public function fixPrice($price): int
    {
        return intVal($price * 100);
    }

    /**
     * Logs errors every error to php error_log file.
     *
     * @param $function
     * @param $err
     */
    public static function error_log($function, $err): void
    {
        error_log(__CLASS__ . '::' . $function . '() ' . $err);
    }

    /**
     * @param bool $bool
     * @return bool
     */
    public function setMobile(bool $bool = true)
    {
        $this->isMobile = $bool;
    }

    /**
     * @return bool
     */
    public function isMobile(): bool
    {
        return $this->isMobile;
    }

    /**
     * @param $total
     * @return void
     */
    public function setTotal($total)
    {
        $this->total = $total;
    }

    /**
     * @param $number
     * @return float
     */
    public static function digit($number, $digit = null): float
    {
        if (is_null($digit)) {
            $digit = self::DIGIT;
        }
        $newNumber = number_format($number, $digit, ".", "");
        return $newNumber;
    }

    public function result()
    {
        return $this->result;
    }
}
