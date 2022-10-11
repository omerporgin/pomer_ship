<?php

namespace App\Http\Controllers\Vendor\Payment;

use App\Http\Controllers\Controller;
use App\Http\Requests\DatatableRequest;
use App\Services\PaymentAccountService;
use Illuminate\Support\Facades\Auth;

class PaymentDataTableController extends Controller
{
    /**
     * @var PaymentAccountService
     */
    protected $service;

    public function __construct(PaymentAccountService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * dataTableJson
     *
     * @param DatatableRequest $request
     * @return Response
     */
    public function index(DatatableRequest $request)
    {
        try {
            $filters = [
                'start' => $request->start,
                'length' => $request->length,
                'search' => $request->search,
                'user_id' => Auth::id()
            ];

            $list = $this->service->getAll($filters);
            $items = $list["list"];
            $data = [];

            foreach ($items as $item) {
                $addData = $item->toArray();
                $payment = service('Payment', $item->payment_id);
                $addData['payment_name'] = $payment->get()->name;
                $addData['status_name'] = $this->service->getStatus($item->status);
                $data[] = $addData;
            }

            return [
                'status' => 200,
                "total" => $list["total"],
                "data" => $data,
                "draw" => $request->draw,
                "recordsTotal" => $list["total"],
                "recordsFiltered" => $list["total"],
            ];
        } catch (\Exception $e) {

            reportException($e);

            return [
                'status' => 500,
                'error' => $e->getMessage(),
                'debug' => __CLASS__,
            ];
        }
    }
}
