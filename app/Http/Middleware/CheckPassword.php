<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPassword
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
        if ($request->api_password != env(key:"API_PASSWORD", default:'7HqmPmx6NDvs5ux2TYlV')) {
            return response()->json(['message'=> 'unauthenticated.']);
        };

        return $next($request);
    }
}
