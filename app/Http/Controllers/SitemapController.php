<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SitemapController extends Controller
{
    public function sitemap()
    {

        $blogService = service('Blog');
        $blogList = [];
        foreach ($blogService->all() as $item) {
            $itemService = service('Blog', $item->id);
            $blogList[] = (object)[
                'link' => $itemService->url,
                'updatedAt' => date("Y-m-d", strtotime($itemService->updated_at)),
                'changefreq' => 'monthly',
                'images' => $itemService->imgs(),
                'priority' => '0.1',

            ];
        }

        return response()->view('sitemap', [
            'blogList' => $blogList,
        ])->header('Content-Type', 'application/xml');
    }
}
