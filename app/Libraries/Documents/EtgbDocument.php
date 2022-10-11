<?php

namespace App\Libraries\Documents;

use Illuminate\Support\Facades\Storage;
use setasign\Fpdi\Tcpdf\Fpdi;
use App\Services\OrderService;

class EtgbDocument extends AbstractDocument
{


    /**
     * @param int $orderId
     */
    public function __construct(OrderService $order)
    {
        $this->order = $order;
        $this->create();
    }

    /**
     * @param int $orderId
     * @return void
     */
    public function create()
    {
        $outputFile = Storage::disk('local')->path($this->saveAs('etgb'));

        $pdf = new Fpdi(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $this->editFile($pdf);
        $pdf->Output($outputFile, 'F');

    }

    /**
     * @param $pdf
     * @param $totalPages
     * @param $path
     * @return void
     */
    public function editFile($pdf)
    {

        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);

        $pdf->AddPage();
        $image_file = public_path('/logo.png');
        $pdf->Image($image_file, 170, 5, 30, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);

        $pdf->SetFont('dejavusans', '', 9);
        $pdf->SetTextColor(0, 0, 0);

        $html = '
                <br>
                <br>
                <br>
                <h2>ETGB İHRACAT BİLGİ FORMU</h2>
                ' . $this->createDataTable() . '
                <br>
                <br>
                ' . $this->createProductTable() . '
                 <br>
            <div>
                <b>AD – SOYAD / KAŞE / İMZA</b>
            </div>';

        $pdf->writeHTML($html, true, false, false, false, '');
    }

    /**
     * @return string
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function createDataTable(): string
    {
        $user = service('UserService', $this->order->vendor_id);

        $trackingNumbers = [];
        $package = $this->order->packages();
        foreach ($package as $orderInfo) {
            $trackingNumbers[] = $orderInfo->tracking_number;
            $desi = $orderInfo->tracking_number;
        }

        $name = $this->order->firstname . ' ' . $this->order->lastname;

        $inputFileds = [
            'İHRACATÇI FİRMA' => $user->company_name,
            'ADRES' => $user->warehouse_address,
            'TELEFON,FAX VE E-MAIL' => $user->warehouse_phone . ' | ' . $user->email,
            'İLGİLİ KİŞİ' => $user->name . ' ' . $user->surname,
            'VERGİ DAİRESİ VE NO' => $user->company_tax . ' | ' . $user->company_taxid,
            'HAVALİMANI GÜMRÜK KAYDI' => 'VAR | YOK',
            'BANKA BİLGİSİ ' => $user->bank,
            'İMALATÇI BİLGİSİ' => '...',
            'MENŞEİ BİLGİSİ' => 'MADE IN TURKEY',
            'ALICI ADI' => $name,
            'ALICI ADRESİ' => $this->order->address,
            'KONŞİMENTO NO' => $orderInfo->tracking_number,
        ];

        $htmlContent = '';
        foreach ($inputFileds as $header => $value) {
            $htmlContent .= '
                <tr>
                    <td width="200" style="text-align:right"><b>' . $header . ' </b>: </td>
                    <td>' . $value . '</td>
                </tr>';
        }
        $htmlContent = '
            <br>
            <br>
            <table cellspacing="0", cellpadding="2" border="0">
                <thead></thead>
                <tbody>' . $htmlContent . '</tbody>
            </table>';
        return $htmlContent;
    }

    /**
     * @return string
     */
    public function createProductTable(): string
    {
        $productList = $this->order->get()->orderProducts()->where('type', 0)->get();

        // Table with rowspans and THEAD
        $tbl = '<table border="1" cellpadding="2" cellspacing="0">
            <thead>
             <tr style="background-color:rgb(255,193,7);color:#000000;">
              <td width="30" align="center"><b>A</b></td>
              <td width="100" align="center"><b>GTIP NO:</b></td>
              <td width="150" align="left"><b>Eşya Cinsi</b></td>
              <td width="60" align="center"><b>Eşya Miktarı</b></td>
              <td width="60" align="center"> <b>Eşya Kıymeti</b></td>
              <td width="60" align="center"><b>Kap Adedi</b></td>
              <td width="45" align="center"><b>Ağırlık</b></td>
             </tr>
            </thead>';
        $row = 1;
        foreach ($productList as $product) {
            $tbl .= '
            <tr>
                <td width="30" align="center">' . $row . '</td>
                <td  width="100" align="center">' . $product['gtip_code'] . '</td>
                <td  width="150">' . $product['name'] . '</td>
                <td  width="60" align="center">' . $product['quantity'] . ' ADET</td>
                <td width="60" align="center">' . number_format($product['quantity'] * $product['unit_price'], 2) . '</td>
                <td width="60" align="center">1</td>
                <td width="45" align="center">'.$product['desi'].'</td>
            </tr>';
            $row++;
        }

        $tbl .= '</table>';
        return $tbl;
    }
}

