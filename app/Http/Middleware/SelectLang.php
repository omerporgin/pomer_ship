<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SelectLang
{
    /**
     * Select language by using first segment of url.
     * (Not in friendy Urls)
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $langID = $request->segment(1);
        \App::setLocale(langCode($langID)); 
       
        return $next($request);
    }
}
