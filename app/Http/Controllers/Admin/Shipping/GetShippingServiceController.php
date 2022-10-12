<?php

namespace App\Http\Controllers\Admin\Shipping;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GetShippingServiceController extends Controller
{
    public function __invoke(Request $request)
    {
        if( !isset($request->shipping_id)){
            throw new InvalidArgumentException('shipping_id required');
        }

        return response()->json([]);
    }
}
