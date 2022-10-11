<?php

namespace App\Http\Controllers\Admin\Localization;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ScanVariablesStatusController extends Controller
{

    /**
     * scans for language variables
     *
     * @return void
     */
    public function scan()
    {
        try {
            $response = $this->service->scan();
        } catch (\Exception $e) {

            $errorList = $e->getMessage();
            if (!is_array($errorList)) {
                $errorList = [$errorList];
            }

            reportException($e);

            return response()->json($errorList, 500);
        }

        return response()->json($response, 200);
    }
}
