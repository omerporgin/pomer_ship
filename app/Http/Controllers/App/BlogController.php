<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;

class BlogController extends Controller
{


    /**
     * @return \Illuminate\Http\Response
     */
    public function blog($lang, int $id)
    {

        $cacheName = 'blog.' . $id;

        return \Cache::remember($cacheName, env('CACHE_TIME'), function () use ($id) {
            $blog = service('Blog', $id);

            $view = \View::make(theme('blog'), [
                'lang' => $blog->lang,
                'blog' => $blog,
            ]);

            return $view->render();
        });
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function blogs($lang = 109)
    {
        $cacheName = 'blogs.' . $lang;

        return \Cache::remember($cacheName, env('CACHE_TIME'), function () use ($lang) {
            $blogs = \App\Models\Blog::paginate(9);

            $blogList = [];
            foreach ($blogs as $blog) {
                $blogList[$blog->id] = service('Blog', $blog->id);
            }
            $view = \View::make(theme('blogs'), [
                'blogList' => $blogList,
                'blogs' => $blogs,
                'lang' => $lang,
            ]);
            return $view->render();
        });
    }
}
