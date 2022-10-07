<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Libraries\Payments\Payment_1\Services\CheckBinNumber;
use App\Payments\Payment_1\PaymentForm;
use App\Services\PaymentAccountService;
use App\Services\PaymentService;
use http\Exception\InvalidArgumentException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{

    /**
     *
     */
    public function __construct(PaymentAccountService $service)
    {
        $this->service = $service;
    }

    /**
     * Checks card number for validity and gets installment list.
     *
     * @param Request $request
     * "       card_number" => "4355084355084358"
     * "       payment_id" => "1"
     * @return \Illuminate\Http\Response
     */
    public function installments(Request $request)
    {
        $service = service('Payment', $request->payment_id);
        $payment = $service->get();
        switch ($payment->processor) {
            case "paytr":
                $getInstallments = new CheckBinNumber;
                $getInstallments->setBinNumber($request->card_number);
                $result = $getInstallments->execute();
                break;
            default:
                return response()->json([
                    'error' => 'processor not found!',
                ], 500);
                break;
        }
        return response()->json($result);
    }

    /**
     * @param Request $request
     * @return void
     */
    public function pay(Request $request)
    {
        $service = service('Payment', $request->payment_id);

        $user = Auth::user();

        if (is_null($data = \App\Models\Payment::where('id', $request->payment_id)->first())) {
            throw new InvalidArgumentException('Paytr data is not set!');
        }

        $processorData = (object)[
            'merchant_id' => $data->user,
            'merchant_key' => $data->key,
            'merchant_salt' => $data->pass,
            'success_url' => $data->success_url,
            'fail_url' => $data->fail_url,
        ];

        // We store payment here
        $storedPayment = $this->service->save([
            'user_id' => $user->id,
            'payment_id' => $request->payment_id,
            'installment' => $request->installment,
            'status' => 0,
            'total' => $request->total_payment,
        ]);

        if ($storedPayment['result']) {
            $storedPaymentItem = $storedPayment;

            switch ($payment->processor) {
                case "paytr":
                    $formData = $this->paytr($processorData, $user, $request, $storedPaymentItem);
                    break;
                default:
                    return response()->json([
                        'error' => 'payment_id not found!',
                    ], 500);
                    break;
            }

            $viewName = vendorTheme() . '.payments.' . $payment->processor;
            $output = view($viewName, $formData)->render();

            return [
                'result' => true,
                'form' => $output,
                'err' => ''
            ];
        } else {
            return [
                'result' => false,
                'err' => 'Payment store error!'
            ];
        }
    }

    /**
     * PRIVATE !
     *
     * @return array
     */
    private function paytr($processorData, $user, Request $request, array $storedPaymentItem)
    {
        $payment_amount = $request->total_payment;

        $merchant_oid = 'shipExpogin' . $storedPaymentItem['id'];

        $test_mode = "1";

        $non_3d = "0";
        $non3d_test_failed = "0";

        $user_ip = request()->ip();
        $email = $user->email;

        $installment_count = "0"; // 0, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12

        $installment_count = 2;

        $card_type = '';
        if (isset($request->card_type)) {
            $card_type = $request->card_type;
        }

        $currency = 'TL';

        $payment_type = "card"; // 'card', 'card_points'

        $hash_str = $processorData->merchant_id . $user_ip . $merchant_oid . $email . $payment_amount . $payment_type . $installment_count . $currency . $test_mode . $non_3d;
        $token = base64_encode(hash_hmac('sha256', $hash_str . $processorData->merchant_salt, $processorData->merchant_key, true));

        $debug_on = '0';// or 1


        $okUrl = $processorData->success_url . '/?merchant_oid=' . $merchant_oid;
        $failUrl = $processorData->fail_url . '/?merchant_oid=' . $merchant_oid;

        $formData = [
            'card_name' => $request->card_name,
            'card_number' => $request->card_number,
            'card_cvv' => $request->card_cvv,
            'expiry_month' => $request->expiry_month,
            'expiry_year' => $request->expiry_year,

            'processorData' => $processorData,
            'user_ip' => $user_ip,
            'merchant_oid' => $merchant_oid,
            'email' => $email,
            'payment_type' => $payment_type,
            'payment_amount' => $payment_amount,
            'currency' => $currency,
            'test_mode' => $test_mode,
            'non_3d' => $non_3d,
            'card_type' => $card_type,
            'user_name' => $user->name,
            'user_address' => $user->address,
            'user_phone' => preg_replace('/\D/', '', $user->phone),

            // One product in basket
            'user_basket' => htmlentities(json_encode([
                'Payment',
                1000,
                1,
            ])),
            'client_lang' => "tr", // tr or en
            'token' => $token,
            'non3d_test_failed' => $non3d_test_failed,
            'installment_count' => $installment_count,
            'debug_on' => $debug_on,
            'store_card' => 'N',
            'merchant_ok_url' => $okUrl,
            'merchant_fail_url' => $failUrl,
            'redirect_message' => _('Redirecting to 3D secure page.'),
        ];
        return $formData;
    }
}
