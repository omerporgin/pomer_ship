<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Libraries\Shippings\Factory;
use App\Http\Requests\OrderPackageRequest;

class LabelController extends Controller
{

    public function print(OrderPackageRequest $request)
    {

        try {
            $package = $request->getPackage();
            $packageData = $request->All();

            $shipping = Factory::shipping($request->shipment_id);
            $packageData['calculated_desi'] = $shipping->calculateDesi((object)$packageData);

            $package->save($packageData);

            if (!is_null($package->shipment_id) and !is_null($shipping)) {

                $order = service('order', $package->order_id);
                if ($request->description != $order->description) {
                    $orderObj = $order->get();
                    $orderObj->description = $request->description;
                    $orderObj->save();
                }

                // Create request
                $request = Factory::request('CreateShipment', $shipping, $order);

                $data = $request->build();

                if ($shipping->withData($data)->createShipment()) {
                    $response = $shipping->response();

                    if (!isset($response->shipmentTrackingNumber)) {
                        // shipmentTrackingNumber not exist
                        $errorList = [];
                        if (isset($response->detail)) {
                            $order->newLog($response->detail);
                            $errorList[] = $response->detail;
                        }
                        if (isset($response->additionalDetails)) {
                            foreach ($response->additionalDetails as $detail) {
                                $order->newLog($detail);
                                $errorList[] = $detail;
                            }
                        }

                        return response()->json([
                            'errorList' => $errorList
                        ], 500);

                    } else {

                        event(new \App\Events\OrderLabelPrinted($package, $order, $response));

                        return response()->json([]);
                    }
                } else {
                    $errorList = $shipping->getErrorList();
                }

            } else {
                $errorList = 'Select shipment_id';
            }
        } catch (\Exception $e) {
            reportException($e, 0);
            $errorList = $e->getMessage();
        }

        if (!is_array($errorList)) {
            $errorList = [$errorList];
        }
        return response()->json([
            'errorList' => $errorList
        ], 500);
    }
}
