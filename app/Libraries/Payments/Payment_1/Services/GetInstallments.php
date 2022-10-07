<?php

namespace App\Libraries\Payments\Payment_1\Services;

use App\Payments\Payment_1\Services\Tygh;
use function App\Payments\Payment_1\Services\fn_format_price;
use const App\Payments\Payment_1\Services\PAYTR_PAYMENT_COMMISION;

class GetInstallments extends AbstractServices
{
    /**
     * @var string
     */
    protected $url = "https://www.paytr.com/odeme/taksit-oranlari";

    /**
     * @var bool
     */
    protected $act = true;

    /**
     * @var bool
     */
    protected $withNewComission = true;
    /**
     * Cart total
     *
     * @var null|float
     */
    protected $cartTotal = null;

    /**
     * Değişecek taksitler
     *
     * @var array
     */
    protected $rows = [];

    public function __construct(){
        parent::__construct();
    }
    /**
     * Paytr | Gets installments
     * (deprecated : fn_paytr_get_installments)
     *
     * @return object|null
     */
    public function execute(): ?object
    {
        $requestId = time();
        $token = base64_encode(hash_hmac('sha256', $this->merchant_id . $requestId . $this->merchant_salt, $this->merchant_key, true));

        $this->postVals = [
            'merchant_id' => $this->merchant_id,
            'request_id' => $requestId,
            'paytr_token' => $token,
        ];

        $result = $this->withouthLog()->initCurl();

        // Tefeci faizi için düzenleme yapıyoruz.
        foreach ($result->oranlar as $key => $items) {
            foreach ($items as $kk => $val) {
                $result->oranlar[$key][$kk] = $this->newCommission($val);
            }
        }

        $this->result = $result;

        return $result;
    }

    /**
     * Calculates paratika's new commission
     */
    public function newCommission($newCommission)
    {
        if ($this->withNewComission) {
            $newCommission = $newCommission / 100;
            $newCommission = 100 / (1 - $newCommission) - 100;
        }
        return $newCommission;
    }

    /**
     * $d = new GetInstallments;
     * $d->withNewComission(false)->execute();
     *
     * @param bool $bool
     * @return $this
     */
    public function withNewComission(bool $bool): self
    {
        $this->withNewComission = $bool;
        return $this;
    }

    /**
     * Must be called before execute()
     *
     * @param $total
     * @return void
     */
    public function setCartTotal($total = null): void
    {
        if (is_null($total)) {
            $cart = &Tygh::$app['session']['cart'];
            $total = fn_format_price($cart['total']);
        }
        $this->cartTotal = $total;
    }

    /**
     * Calculates payments surcharge.
     *
     * @param $cart
     * @param $installment
     * @return float
     */
    public static function getSurcharge($total, $installment, $cardType, bool $withRatio = false)
    {
        $service = new GetInstallments;
        $service->setCartTotal();
        $installments = $service->execute();

        $ratio = 0;
        if ($installment == 0 or $installment == 1) {
            if (!$withRatio) {
                return 0;
            } else {
                return [0, $service->defaultCommission()];
            }
        }

        $surcharge = 0;

        if ($installments->status == 'success') {

            if (isset($installments->oranlar[$cardType])) {

                if (isset($installments->oranlar[$cardType]['taksit_' . $installment])) {

                    $ratio = $installments->oranlar[$cardType]['taksit_' . $installment];

                    $surcharge = $ratio * $total / 100;


                } else {
                    error_log('addon paytr func :' . __FUNCTION__ . ' installment not set');
                }
            } else {
                error_log('addon paytr func :' . __FUNCTION__ . ' oranlar not set');
            }

        } else {
            error_log('addon paytr func :' . __FUNCTION__ . ' get installments failed');
        }

        if (!$withRatio) {
            return $surcharge;
        } else {
            return [$surcharge, $ratio];
        }
    }

    /**
     * Peşin komisyon oranını döndürür.
     *
     * @return mixed|void
     */
    public function defaultCommission()
    {
        //$newCommission = self::newCommission(PAYTR_PAYMENT_COMMISION);
        return PAYTR_PAYMENT_COMMISION;
    }

    /**
     * @param float $price
     * @return $this
     */
    function ifTotalPriceGreaterThan(float $price): GetInstallments
    {
        if (is_null($this->cartTotal)) {
            $this->setCartTotal();
        }

        if ($this->cartTotal > $price) {
            $this->act = true;
        } else {
            $this->act = false;
        }
        return $this;
    }

    /**
     * @param int|array $row
     * @return void
     */
    protected function changeThisInstallements($newRows): GetInstallments
    {
        if (!is_array($newRows)) {
            $newRows = [$newRows];
        }
        $this->rows = $newRows;

        return $this;
    }

    /**
     * @return void
     */
    protected function toCartTotal(): GetInstallments
    {
        if ($this->act) {
            foreach ($this->rows as $row) {
                foreach ($this->result->oranlar as $key => $card) {
                    $this->result->oranlar[$key]['taksit_' . $row] = 0;
                }
            }
        }
        return $this;
    }

    /**
     * @return void
     */
    protected function addDefinition(): GetInstallments
    {
        $this->result->promotionRows = $this->rows;
        $this->result->promotionOverPrice = $this->rows;
        return $this;
    }
}
