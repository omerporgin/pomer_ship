<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Exception;
use Session;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     *
     */
    public function __construct()
    {
        $data = [];
        try {
            if (!is_null(request()->route())) {
                $data['routeName'] = request()->route()->getname();
            }
        } catch (\Exception $e) {

        }
        \View::share($data);
    }
}
