<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function success(int $id)
    {
        return response()->view(vendorTheme().".payment_success");
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function fail(int $id)
    {
        return response()->view(vendorTheme().".payment_fail");
    }

    /**
     * @param int $id
     * @return void
     */
    public function callback(int $id, Request $request)
    {
        $service = app()->make(PaymentService::class, [
            'id' => $id
        ]);
        $processorData = $service->get();

        $merchant_key = $processorData->key;
        $merchant_salt = $processorData->pass;

        // Do not change
        $hash = base64_encode(hash_hmac('sha256', $request->merchant_oid . $merchant_salt . $request->status .
            $request->total_amount, $merchant_key, true));

        if ($hash != $request->hash) {
            error_log('paytr step2 : PAYTR notification failed: bad hash.' . $request->merchant_oid);
            exit;
        }
        // Do not change end


        if ($request->status == 'success') {
            $paymentAccountId = str_replace('shipExpogin','', $request->merchant_oid);
            $paymentService = app()->make(\App\Services\PaymentAccountService::class, [
                'id'=>$paymentAccountId,
            ]);
            $paymentAccount =$paymentService->get();
            $paymentAccount->status = 1;
            $paymentAccount->save();
        } elseif ($post['status'] == 'failed') {

            $err = 'Callbask error not defined!';
            if (isset($post['failed_reason_msg'])) {
                $err = $post['failed_reason_msg'];
            }
        }
        // Paytr'ye bildiriyoruz.
        echo "OK";
    }
}
