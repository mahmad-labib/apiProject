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
    public function handle($request, Closure $next, ...$parm1)
    {
        // 
        $permissions = [];
        array_push($permissions, ...$parm1);
        $keys = [];
        foreach ($permissions as $perm) {
            if ($request->user()->can($perm)) {
                array_push($keys, 1);
            } else {
                array_push($keys, 0);
            }
        }
        if (in_array(1, $keys)) {
            return $next($request);
        } else {
            abort(404);
        }
        // if (!$request->user()->hasRole(...$parm1)) {

        //     abort(404);
        // }

        // if ($parm1 !== null && !$request->user()->can(...$parm1)) {

        //     abort(404);
        // }


    }
}
