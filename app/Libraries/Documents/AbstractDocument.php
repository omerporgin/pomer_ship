<?php

namespace App\Libraries\Documents;

use App\Services\OrderService;

class AbstractDocument
{

    protected $lineHeight = 7;

    /**
     * @var OrderService
     */
    public OrderService $order;

    /**
     * @param $pdf
     * @param float $x
     * @param float $y
     * @param string $text
     * @return void
     */
    protected function textInfo($pdf, float $x, float $y, string $text)
    {
        $pdf->SetXY($x, $y);
        $pdf->Write(0, $text);
    }

    /**
     * @return string
     */
    protected function saveAs(string $prefix): string
    {
        return 'public/documents/' . $prefix . '_' . $this->order->getID() . '.pdf';
    }

}
