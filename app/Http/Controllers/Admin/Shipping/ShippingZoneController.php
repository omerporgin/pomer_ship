<?php

namespace App\Http\Controllers\Admin\Shipping;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Libraries\Shippings\Factory;

class ShippingZoneController extends Controller
{
    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateShippingZone(Request $request)
    {
        if (isset($request->shippingId)) {
            $shipping = Factory::shipping($request->shippingId);
            if (!is_null($shipping->getShipping())) {
                $processor = $shipping->getShipping()->processor;
                return response()->json(
                    array_merge(['result' => 1], \App\Libraries\Shippings\Zones\Zones::updateZone($processor))
                );
            } else {
                response()->json([
                    'result' => 0,
                    'error' => 'Shipping not found.'
                ]);
            }
        } else {
            response()->json([
                'result' => 0,
                'error' => 'Parameter shippingId not found.'
            ]);
        }
    }
}
