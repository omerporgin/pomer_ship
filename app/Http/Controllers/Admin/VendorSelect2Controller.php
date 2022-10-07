<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;

class VendorSelect2Controller extends Controller
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getData(Request $request)
    {

        $vendorList = (new UserService)->getAll([
            'type' => 'all',
            'search' => [
                'value' => $request['search']
            ]
        ]);

        $results = [];
        foreach ($vendorList['list'] as $vendor) {
            $results[] = [
                'id' => $vendor->id,
                'text' => $vendor->name,
            ];
        }

        return response()->json([
            'results' => $results
        ]);
    }
}
