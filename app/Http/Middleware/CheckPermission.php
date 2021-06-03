<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$parm1 )
    {
        // dd($request->user()->can(...$parm1));
        // if (!$request->user()->hasRole(...$parm1)) {

        //     abort(404);
        // }

        if ($parm1 !== null && !$request->user()->can(...$parm1)) {

            abort(404);
        }

        return $next($request);
    }
}
