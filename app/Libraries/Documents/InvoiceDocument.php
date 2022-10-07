<?php

namespace App\Libraries\Documents;

use Illuminate\Support\Facades\Storage;
use setasign\Fpdi\Tcpdf\Fpdi;

use App\Services\OrderService;


class InvoiceDocument extends AbstractDocument
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
        $outputFile = Storage::disk('local')->path($this->saveAs('commerical_invoice'));

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
        $totalGoods = $this->order->get()->orderProducts()->where('type', 0)->sum('total_price');
        $totalLineItems = $this->order->get()->orderProducts()->where('type', '!=',0)->sum('total_price');
        $totalInvoice = $this->order->get()->orderProducts()->sum('total_price');

        $package = $this->order->get()->orderPackages()->get();
        $totalDesi = 0;
        foreach($package as $p){
            $totalDesi += $p->calculated_desi;
        };

        $user = $this->order->get()->user()->first();

        $shipping = service('shipping', $package[0]->shipment_id);

        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);
        $pdf->AddPage();

        $image_file = public_path('/logo.png');
        $pdf->Image($image_file, 170, 5, 30, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('dejavusans', '', 9);
        $pdf->SetTextColor(0, 0, 0);

        $html = '
               <h1>COMMERICAL INVOICE</h1>
               <br>
               <div style="font-size:10pt;">
               AWB No: ........... Invoice Date: ' . date("Y-m-d") . ' Invoice No: '.$this->order->invoice_no.'
               <br>
               <br>
                <table style="font-size: 8pt">
                    <tr>
                        <td>
                            <b>SHIP FROM:</b><br>

                            <table border="0" cellpadding="5">
                            <tr><td style="border:1px solid #ddd">
                             <u>' . $user->real_company_name . '</u><br>
                            ' . $user->warehouse_address . '<br>
                            ' . $user->city_name . ' / ' . $user->state_name . ' / Turkey<br>
                             ' . $user->warehouse_phone . '<br>
                            ' . $user->email . '<br>
                            </td></tr>
                            </table>
                            <br>
                            <br>
                            Trader Type : BUSINESS<br>
                            VAT No:' . $user->real_tax_id . '<br>
                            EORI : <br>
                            TAX ID : <br>
                            <br><br>
                            <b>Shipper Reference :</b><br>
                            <b>Receiver Reference :</b><br>
                            <b>Remarks :</b><br>
                        </td>
                        <td>
                            <b>SHIP TO:</b><br>
                             <table border="0" cellpadding="5">
                            <tr><td style="border:1px solid #ddd">
                            <u>' . $this->order->firstname . ' ' . $this->order->lastname . '</u><br>
                            ' . $this->order->address . ' ' . $this->order->post_code . '<br>
                           ' . $this->order->city_name . ' / ' . $this->order->state_name . ' / ' . $this->order->country_name . '<br>
                           ' . $this->order->phone . '<br>
                            ' . $this->order->email . '<br>
                           </td></tr>
                            </table>
                            <br>
                            <br>
                            Trader Type : PRIVATE<br>
                            VAT No : <br>
                            EORI : <br>
                        </td>
                    </tr>
                </table>
                </div>
                </div>';
        $list = '';
        $count = 1;
        $total = 0;
        foreach ($this->order->get()->orderProducts()->where('type', 0)->get() as $product) {
            $sumRow = $product->unit_price * $product->quantity;
            $list .= ' <tr>
                <td width="15"  style="text-align:center">' . $count . '</td>
                <td width="130">' . $product->name . '</td>
                <td style="text-align:center">' . $product->gtip_code . '</td>
                <td style="text-align:center"></td>
                <td style="text-align:center">' . $product->calculated_desi . '</td>
                <td style="text-align:center"></td>
                <td style="text-align:center"></td>
                <td width="30" style="text-align:center">' . $product->quantity . '</td>
                <td width="50" style="text-align:center">' . $product->unit_price . '  EUR</td>
                <td width="50" style="text-align:center">' . $sumRow . '  EUR</td>
            </tr>';
            $total += $sumRow;
        }
        foreach ($this->order->get()->orderProducts()->where('type', '!=', 0)->get() as $product) {
            $sumRow = $product->unit_price * $product->quantity;
            $type = match ($product->type) {
                1 => 'Cargo',
                2 => 'Payment surcharge',
                default => '?',
            };
            $list .= ' <tr>
                <td width="15"  style="text-align:center">' . $count . '</td>
                <td width="130">' . $product->name . '</td>
                <td style="text-align:right" colspan="5">'.$type.'</td>
                <td width="30" style="text-align:center">' . $product->quantity . '</td>
                <td width="50" style="text-align:center">' . $product->unit_price . '  EUR</td>
                <td width="50" style="text-align:center">' . $sumRow . '  EUR</td>
            </tr>';
            $total += $sumRow;
        }
        $html .= '<table  border="1" cellpadding="2" cellspacing="0" style="font-size: 7pt">
            <thead>
            <tr style="background-color:rgb(255,193,7);color:#000000;">
                <th width="15"  style="text-align:center">#</th>
                <th  width="130">Description</th>
                <th>Commodity Code</th>
                <th>GST paid</th>
                <th>Net / Gross Weight</th>
                <th>COO</th>
                <th>Reference Type & ID</th>
                <th width="30">QTY</th>
                <th width="50">Unit Value</th>
                <th width="50">Sub Total Value</th>
            </tr>
            </thead>
            <tbody>' . $list . '</tbody></table>
            <br>
            <br>';

        $html .= '<table  border="0" cellpadding="2" cellspacing="0" style="font-size: 7pt">

            <tbody>
            <tr>
                <td>Total Goods Value:</td>
                <td>' . $totalGoods . ' EUR</td>
                <td>Total line items:</td>
                <td>' . $this->order->get()->orderProducts()->count() . '</td>
            </tr>
            <tr>
                <td>Total Invoice Amount:</td>
                 <td>' . $totalInvoice . ' EUR</td>
                <td>Number of Pallets:</td>
                <td>0</td>
            </tr>
            <tr>
                <td>Currency Code: </td>
                 <td>EUR</td>
                <td>Total units</td>
                <td></td>
            </tr>
            <tr>
                <td>Terms of Payment:</td>
                 <td></td>
                <td>Package Marks / Other Info</td>
                <td></td>
            </tr>
            <tr>
                <td>Terms of Trade: </td>
                 <td>Delivered at Place</td>
                <td>Payer of GST / VAT</td>
                <td></td>
            </tr>
            <tr>
                <td>Place of Incoterm:</td>
                 <td></td>
                <td>Duty / taxes acct</td>
                <td>Receiver Will Pay</td>
            </tr>
            <tr>
                <td>Reason for Export:</td>
                 <td>Micro Export, Commercial</td>
                <td>Requiere Pedimento:</td>
                <td>No</td>
            </tr>
            <tr>
                <td>Type of Export: </td>
                 <td>Permanent</td>
                <td>Duty / tax billing service</td>
                <td></td>
            </tr>
            <tr>
                <td>Total Net Weight:</td>
                 <td>'.$totalDesi.' kg</td>
                <td>Carrier</td>
                <td>' . $shipping?->name . '</td>
            </tr>
            <tr>
                <td>Total Gross Weight:</td>
                 <td>'.$totalDesi.' kg</td>
                <td>Ultimate Consignee:</td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                 <td></td>
                <td>Exemption Citation:</td>
                <td></td>
            </tr>
            </tbody></table>';

        $pdf->writeHTML($html, true, false, true, false, '');
    }

}

