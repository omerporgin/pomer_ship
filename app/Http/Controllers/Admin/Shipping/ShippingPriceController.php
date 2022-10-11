<?php

namespace App\Http\Controllers\Admin\Shipping;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Libraries\Shippings\Factory;
use App\Http\Requests\Admin\UpdateShppingRequest;

class ShippingPriceController extends Controller
{

    /**
     * Updates shipping price list of given shipping id.
     *
     * @param UpdateShppingRequest $request
     * @return void
     */
    public function updateShippingPrices(UpdateShppingRequest $request)
    {
        try {

            $shipping = Factory::shipping($request->shippingId);
            $shipping->updateShippingPrices($request->region, $request->maxDesi);

        } catch (\Exception $e) {
            return response()->json([
                'region' => $e->getMessage(),
                's' =>$shipping->getErrorList(),
            ], 500);
        }

        return response()->json([
            'result' => 'ok'
        ]);

    }

    /**
     * @param int $id
     * @return Response
     */
    public function shippingPrices(int $id)
    {
        return response()->view(adminTheme('shipping_prices'), [
            'id' => $id,
            'shipping' => service('Shipping', $id)
        ]);
    }

    /**
     * @param int $id
     * @return Response
     */
    public function updateShippingPriceView(int $id)
    {
        return response()->view(adminTheme('forms.shippingPrices.1'), [
            'id' => $id,
        ]);
    }
}
