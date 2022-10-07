<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Libraries\Shippings\Factory;

class UploadInvoiceController extends Controller
{

    public function upload(Request $request)
    {


        $package = \App\Models\OrderPackages::where('order_id', $request->order_id)->first();
        $shipping = Factory::shipping($package->shipment_id);

        $uploadRequest = Factory::request('UploadImage', $shipping, service('Order', $request->order_id));
        foreach ($request->file as $file) {
            $uploadRequest->addDocument('INV', $file);
        }

        $uploadRequest->set();
        $data = $uploadRequest->build();

        if ($shipping->withData($data)->uploadImage()) {

            $response = $shipping->response();

            dd($response);
        } else {
            $errorList = $shipping->getErrorList();
            dd($errorList);
        }

    }
}
