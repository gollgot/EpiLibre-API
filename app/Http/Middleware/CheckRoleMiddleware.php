<?php

namespace App\Http\Middleware;

use App\CustomHelpers\JSONResponseHelper;
use Closure;
use Firebase\JWT\JWT;

/**
 * Middleware to check the User's role
 * CAREFULL : We MUST use this middleware RIGHT AFTER the Auth middleware
 * @package App\Http\Middleware
 */
class checkRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $targetRole)
    {
        $jwt = $request->bearerToken();
        // JWT is correct because we must call this middleware after the auth middleware
        $decoded = JWT::decode($jwt, env("JWT_SECRET"), array('HS256'));
        // Token role are same as target role -> OK
        if($decoded->role == $targetRole){
            return $next($request);
        }

        // Else, return unauthorized json response
        $JSONResponseHelper = new JSONResponseHelper();
        return $JSONResponseHelper->unauthorizedJSONResponse();
    }
}
