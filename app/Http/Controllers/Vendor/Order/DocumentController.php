<?php

namespace App\Http\Controllers\Vendor\Order;

use App\Http\Controllers\Controller;
use App\Libraries\Documents\EtgbDocument;
use App\Libraries\Documents\CustomDocument;
use App\Libraries\Documents\InvoiceDocument;
use http\Exception\InvalidArgumentException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function createDocuments(Request $request)
    {
        if (!isset($request->orderId)) {
            return response()->json([
                'err' => 'order_id required'
            ], 500);
        }

        try {
            $order = service('Order', $request->orderId);
            if(!$order->hasItem()){
                Throw new InvalidArgumentException('Order not found : '.$request->orderId);
            }

            new EtgbDocument($order);

            new InvoiceDocument($order);

            new CustomDocument($order);

            return response()->json([], 200);

        } catch (\Exception $e) {

            return response()->json([
                'err' => $e->getMessage()
            ], 500);

        }
    }
}
