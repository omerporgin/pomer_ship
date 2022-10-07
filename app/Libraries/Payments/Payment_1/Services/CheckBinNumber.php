<?php

namespace App\Libraries\Payments\Payment_1\Services;

use function __;

class CheckBinNumber extends AbstractServices
{
    /**
     * @var string
     */
    protected $bin_number;

    /**
     * @var string
     */
    protected $url = "https://www.paytr.com/odeme/api/bin-detail";

    /**
     * @var int
     */
    protected $timeOut = 20;

    /**
     * Paytr | Checks bin number
     *
     * @return object|null
     */
    public function execute(): ?object
    {
        $this->isLogged = false;
        $cartTotal = $this->total;
        $err = '';
        $brand = 'Undefined Card Type';
        if (strlen($this->bin_number) > 6) {

            $bin_number = substr($this->bin_number, 0, 6); // First 6 chars of card number

            $hashStr = $bin_number . $this->merchant_id . $this->merchant_salt;
            $token = base64_encode(hash_hmac('sha256', $hashStr, $this->merchant_key, true));

            $this->postVals = [
                'merchant_id' => $this->merchant_id,
                'bin_number' => $bin_number,
                'paytr_token' => $token
            ];

            $result = $this->initCurl();

            if(isset($result->brand)){
                $brand = $result->brand;
            }

            if ($result->status == 'error') {
                $err = "PAYTR BIN detail request error. Error:" . $result->err_msg;
            } elseif ($result->status == 'failed') {
                $err = 'BIN tanımlı değil. (Örneğin bir yurtdışı kartı)';
            } else {
                /*
                    Kart Program Ortaklığı İsmi: Kartın program ortaklığı ismi (Kart bir program ortaklığına dahil değil ise değer none olur. Bu durumda ilgili kart ile PayTR üzerinden taksitli işlem yapılamaz.
                */

                if (isset($brand)) {
                    $installments = new GetInstallments;
                    $ratios = $installments->execute();

                    if ($ratios->status == 'success') {
                        if (isset($ratios->oranlar[$brand])) {
                            $brand_ratios = $ratios->oranlar[$brand];
                            if (!empty($brand_ratios)) {
                                $newRatios = [];
                                foreach ($brand_ratios as $key => $ratio) {
                                    $totalWithRatio = $cartTotal * ($ratio + 100) / 100;
                                    $installment = intVal(str_replace('taksit_', '', $key));
                                    $newRatios[$key] = [
                                        'installment' => $installment,
                                        'ratio' => $ratio,
                                        'monthly' => self::digit($totalWithRatio / $installment),
                                        'total' => self::digit($totalWithRatio)
                                    ];
                                }
                                return (object)[
                                    'status' => 'success',
                                    'response' => 200,
                                    'brand' => $brand,
                                    'cardType' => $result->cardType,
                                    'businessCard' => $result->businessCard,
                                    'bankCode' => $result->bankCode,
                                    'allow_non3d' => $result->allow_non3d,
                                    'ratios' => $newRatios,
                                    'cartTotal' => $cartTotal

                                ];
                            } else {
                                $err = __('Blank installments.');
                            }
                        } else {
                            $err = __('Cant get installment ratios.');
                        }
                    } else {
                        $err = __('Cant get installment ratios.');
                    }
                } else {
                    $err = __('Brand name is not set.');
                }
            }
        } else {
            $err = __('At least 6 chars');
        }

        return (object)[
            'err' => $err,
            'brand' => $brand,
            'cartTotal' => $cartTotal,
            'ratios' => [],
            'response' => 500,
        ];
    }

    /**
     * @param string $bin_number
     */
    public function setBinNumber(string $bin_number)
    {
        $this->bin_number = $bin_number;
    }
}
