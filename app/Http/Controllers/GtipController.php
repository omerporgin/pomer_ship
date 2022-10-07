<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GtipService;

class GtipController extends Controller
{

    /**
     * @return void
     */
    public function apiGtip(Request $request)
    {

        $vendorList = (new GtipService)->selectGtip( $request['search'] );

        $results = [];
        foreach ($vendorList as $vendor) {
            $description = str_replace('-','', $vendor->description);
            $search = str_replace(',',', ', $vendor->search);
            $search = str_replace(':>',' > ', $search);
            $results[] = [
                'id' => $vendor->gtip,
                'text' => $search . " : " . $description,
                'html' => "<div style='font-size:0.8em'>".$search . "</div> <span style='color:red'>" .
                    $description .
                '</span>',
            ];
        }

        return response()->json([
            'results' => $results
        ]);
    }
}
