<?php

namespace App\Http\Controllers\Vendor\Payment;

use App\Http\Controllers\Controller;
use App\Http\Requests\DatatableRequest;
use Illuminate\Http\Request;
use App\Services\PaymentAccountService;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PaymentAccountRequest;

class PaymentAccountController extends Controller
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
