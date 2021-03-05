<?php

namespace App\Http\Middleware;

use App\Http\Traits\GeneralTrait;
use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class CheckUserToken
{
    use GeneralTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // $user = null;
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (\Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return $this->returnError(msg: 'invalid-token', errNum: 'E3001');
            } elseif ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return $this->returnError(msg: 'expired-token', errNum: 'E3001');
            } else {
                return $this->returnError(msg: 'token-notFound', errNum: 'E3001');
            }
        } catch (\Throwable $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return $this->returnError(msg: 'invalid-token', errNum: 'E3001');
            } elseif ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return $this->returnError(msg: 'expired-token', errNum: 'E3001');
            } else {
                return $this->returnError(msg: 'token-notFound', errNum: 'E3001');
            }
        }
        if (!$user) {
            $this->returnError(msg: 'unauthenticated', errNum: 'E3001');
        }
        return $next($request);
    }
}
