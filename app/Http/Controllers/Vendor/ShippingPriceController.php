<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Libraries\Shippings\Factory;
use App\Http\Requests\Vendor\ShippingPriceRequest;

class ShippingPriceController extends Controller
{

    /**
     * Fiyatlar direk kargo servislerinden alınıyor.
     * Bu bölüm test amaçlı, indirimli fiyatlarla karşılaştırılacak.
     *
     * Fiyatlar ülkeye göre belirleniyor.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function getPrice(ShippingPriceRequest $request)
    {
        $user = Auth::user();


        $countryService = service('LocationCountry', $request->country_id);
        if (!$countryService->hasItem()) {
            return response()->json(['Country not exist.'], 500);
        }

        $result = [];

        foreach ([1] as $shippingId) {// Sadece dhl var.

            $shipping = Factory::shipping($shippingId);
            $desi = $shipping->getStandartDesi($request->desi);
            $shipping->setCountryService($countryService);

            $region = $countryService->getRegionByShippingId($shippingId);

            if (is_null($region)) {
                return response()->json(['Region not exist.'], 500);
            }

            $userGroupService = service('UserGroup', $user->user_group_id);
            $result = $userGroupService->getCalculatedPrice($region, $desi);
        }

        $price = array_column($result, 'price');
        array_multisort($price, SORT_ASC, $result);

        return response()->json($result);
    }

    /**
     * @return Response
     */
    public function shippingPrices()
    {
        return response()->view(vendorTheme('shipping_prices'), [
            'id' => 1, // We only show DHL
            'userGroupId' => Auth::user()->user_group_id
        ]);
    }

    /**
     * Download vendor prices as json
     *
     * @param int $id
     * @return Response
     */
    public function downloadShippingPrices(int $id)
    {

        $shippingServiceName = $_GET['service'];
        $shipping = Factory::shipping($id);
        $countryService = service('LocationCountry');
        $userGroupService = service('UserGroup');
        $countryList = $countryService->All();
        $userGroupId = Auth::user()->user_group_id;
        $list = [];
        if (!is_null($userGroupId)) {
            $priceList = $userGroupService->getPricesOfUserGroup($userGroupId);
        }
        foreach ($countryList as $country) {
            $countryService = service('LocationCountry', $country->id);
            $region = $countryService->getRegionByShippingId($id);

            $sql = \App\Models\ShippingPrices::where('service', $shippingServiceName)->where('region', $region)
                ->orderBy('desi');
            if ($sql->count() > 0) {
                foreach ($sql->get() as $row) {

                    $minDesi = 0;
                    if (!is_null($nextRow = \App\Models\ShippingPrices::where('service', $shippingServiceName)->where('region',
                        $region)->where('desi', '<', $row->desi)->orderBy('desi', 'desc')->first())) {
                        $minDesi = $nextRow->desi;
                    }
                    $list[] = [
                        'iso2' => $country->iso2,
                        'iso3' => $country->iso3,
                        'numeric_code' => $country->numeric_code,
                        'country' => $country->name,
                        'countryAlternatives' => json_decode($country->translations),
                        'minDesi' => $minDesi,
                        'maxDesi' => $row->desi,
                        'price' => $row->price * $userGroupService->desiPrice($priceList, $row->desi),
                    ];

                }
            };

        }

        return response(json_encode($list), 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="shipping_' . strtolower($shipping->name . ' ' . $shippingServiceName) . '_json.txt"',
        ]);
    }
}
