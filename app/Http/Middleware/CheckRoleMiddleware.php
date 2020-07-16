<?php

namespace App\Http\Middleware;

use App\CustomHelpers\JSONResponseHelper;
use App\User;
use Closure;

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
        $tokenAPI = $request->bearerToken();
        // The API token is correct because we must call this middleware after the auth middleware
        $user = User::where("tokenAPI", $tokenAPI)->with("role")->first();
        // Token role are same as target role -> OK
        if($user->role["shortName"] == $targetRole){
            return $next($request);
        }

        // Else, return unauthorized json response
        $JSONResponseHelper = new JSONResponseHelper();
        return $JSONResponseHelper->unauthorizedJSONResponse();
    }
}
