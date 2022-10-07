<?php

namespace App\Libraries\Payments\Payment_1\Services;

class ProcessList extends AbstractServices
{
    /**
     * @var string
     */
    protected $url = "https://www.paytr.com/rapor/islem-dokumu";

    /**
     * @var
     */
    protected $startDate;

    /**
     * @var
     */
    protected $endDate;

    /**
     * @var
     */
    public static $statusses = [
        'S' => 'Satış',
        'I' => 'Iade',
    ];

    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return object|null
     */
    public function execute(): ?object
    {

        if ($this->isTest == 'True') {
            return (object)[
                "status" => "error",
                "err_msg" => "Bu servis test modunda çalışmaz.",
            ];
        }

        $paytr_token = base64_encode(hash_hmac('sha256', $this->merchant_id . $this->startDate . $this->endDate . $this->merchant_salt, $this->merchant_key, true));

        $this->postVals = [
            'merchant_id' => $this->merchant_id,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
            'dummy' => 0,
            'paytr_token' => $paytr_token
        ];

        $result = $this->withouthLog()->initCurl();

        if ($result->status == 'success') {

            foreach ($result->list as $key => $item) {
                $result->list[$key]['islem_text'] = self::$statusses[$item['islem_tipi']];
            }
        } elseif ($result->status == 'failed') {
            $result->status = 'error';
            $result->err_msg = "ilgili tarih araliginda islem bulunamadi";
        } elseif(isset($result->err_msg)) {
            $result->err_msg = $result->err_no . " - " . $result->err_msg;
        }else{
            $result->err_msg = 'Not defined';
        }

        return $result;
    }

    /**
     * @param string $date
     */
    public
    function setStartDate(string $date)
    {
        $this->startDate = $date;
    }

    /**
     * @param string $date
     */
    public
    function setEndDate(string $date)
    {
        $this->endDate = $date;
    }
}
