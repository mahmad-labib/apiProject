<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ChangeLanguage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        app()->setLocale(locale: 'ar');
        if (isset($request->lang) && $request->lang == 'en') {
            app()->setLocale(locale: 'en');
        }

        return $next($request);
    }
}
