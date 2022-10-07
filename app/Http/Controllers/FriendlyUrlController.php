<?php

namespace App\Http\Controllers;

use App\Services\UrlService;
use App\Services\PageService;
use App\Services\ImageService;

class FriendlyUrlController extends Controller
{

    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     *
     */
    public function index()
    {

        $url = \URL::current();
        $site = str_replace(['https://', 'http://'], '', env('APP_URL'));
        $url = str_replace(['https://', 'http://', $site], '', $url);
        $blog = \App\Models\Blog::where('url', $url)->first();


        if (!is_null($blog)) {


            $controller = app()->make(\App\Http\Controllers\App\BlogController::class);
            return $controller->callAction('blog', [
                'lang' => $blog->lang,
                'id' => $blog->id,
            ]);

        } else {
            return response('', 410);
        }
    }
}
