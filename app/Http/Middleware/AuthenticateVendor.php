<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticateVendor
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (is_null(Auth::user())) {
            return redirect(route('login', [
                'lang' => langId()
            ]));
        }

        if (!$request->user()->hasVerifiedEmail()) {
            return redirect(RouteServiceProvider::HOME)->withErrors(['message' => _('Please confirm your mail address.')]);
        }

        if (Auth::user()->permission_id < 2) {
            return redirect(RouteServiceProvider::HOME)->withErrors(['message' => _('You dont have permission.')]);
        }

        return $next($request);
    }
}
