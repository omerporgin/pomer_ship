<?php

namespace App\Http\Controllers\Vendor\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\DatatableRequest;
use Illuminate\Http\Request;
use App\Services\PaymentAccountService;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PaymentAccountRequest;

class PaymentAccountController extends Controller
{
    /**
     * @var service
     */
    protected $service;

    public function __construct(PaymentAccountService $service)
    {
        $this->service = $service;
    }

    /**
     * Payment page
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $paymentService = service('Payment');
        $All = $paymentService->getAll();
        $paymentList = $All['list'];

        $paymentAccountService = service('PaymentAccount');

        return response()->view(vendorTheme() . ".payment", [
            'total' => $paymentAccountService->getLoggedUsersAccount(),
            'payments' => $paymentList,
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * dataTableJson
     *
     * @param DatatableRequest $request
     * @return Response
     */
    public function indexAjax(DatatableRequest $request)
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

    /**
     * Store a newly created resource in storage.
     *
     * @param PaymentAccountRequest $request
     * @return Response
     */
    public function store(PaymentAccountRequest $request)
    {

    }

    /**
     *
     */
    public function update(PaymentAccountRequest $request, $id)
    {
    }

    /**
     *
     */
    public function destroy($id)
    {
    }

    /**
     * Created a new resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function create(Request $request)
    {
        return response()->view(vendorTheme('forms.payment'), [
            'payment_id' => $request->data['payment_id']
        ]);
    }
}
