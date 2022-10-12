<?php

namespace App\Http\Controllers\Admin\Localization;

use App\Http\Controllers\Controller;
use App\Services\LocalizationService;
use Illuminate\Http\Request;

class ScanVariablesStatusController extends Controller
{
    /**
     * @var service
     */
    protected $service;

    public function __construct(LocalizationService $service)
    {
        $this->service = $service;
    }

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
