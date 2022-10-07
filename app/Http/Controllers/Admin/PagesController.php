<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\PaymentAccount;
use App\Models\User;
use App\Services\OrderStatusService;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;

class PagesController extends Controller
{

    /**
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shippedList = [];
        $orderList = [];


        $month = 1;
        while ($month < 13) {
            $fist = date("Y-#-1 00:00:00");
            $second = date("Y-#-t 23:59:59");

            $fist = str_replace('#', $month, $fist);
            $second = str_replace('#', $month, $second);

            $name = Carbon::createFromFormat('m', $month)->format('F');

            $orderList[$name] = Order::where('real_status', 13)->whereBetween('created_at', [$fist, $second])->get()->count();
            $shippedList[$name] = Order::where('real_status', 14)->whereBetween('created_at', [$fist, $second])->get()->count();;

            $month++;

        }

        $data = User::select('id', 'created_at')->get()->groupBy(function ($data) {
            return Carbon::parse($data->created_at)->format('M');
        });

        $months = [];
        $monthCount = [];
        foreach ($data as $month => $values) {
            $months[] = $month;
            $monthCount[] = count($values);
        }

        $paymentInfo = [];

        $month = 1;
        $total = [];
        while ($month < 13) {
            $fist = date("Y-#-1 00:00:00");
            $second = date("Y-#-t 23:59:59");

            $fist = str_replace('#', $month, $fist);
            $second = str_replace('#', $month, $second);

            $name = Carbon::createFromFormat('m', $month)->format('F');

            $paymentInfo[$name] = PaymentAccount::where('status', 1)->whereBetween('created_at', [$fist, $second])->get();

            $total[$name] = PaymentAccount::where('status', 1)->whereBetween('created_at', [$fist, $second])->sum('total');

            $month++;

        }

        return response()->view(adminTheme() . '.dashboard_admin', [
                'months' => $months,
                'data' => $data,
                'monthCount' => $monthCount,
                'orderList' => $orderList,
                'shippedList' => $shippedList,
                'paymentInfo' => $paymentInfo,
                'total' => $total
            ]
        );
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function userGates()
    {
        return response()->view(adminTheme() . '.gates', [
            'gateList' => Gate::abilities()
        ]);
    }

    /**
     * @return void
     */
    public function orderStatusses()
    {
        return response()->view(adminTheme() . '.order_status');
    }
}
