<?php

namespace App\Http\Middleware;

use App\CustomHelpers\JSONResponseHelper;
use App\Role;
use App\User;
use Closure;

/**
 * Middleware to check the User's role
 * CAREFULL : We MUST use this middleware RIGHT AFTER the Auth middleware
 *
 * Be aware that we use role's id !! Roles are sorted by priority (minus ID with higher priority), so if we want to
 * protect the route for ADMIN (id = 2), all user with a role <= ADMIN (2) are allowed. So ADMIN or SUPER_ADMIN are allowed
 *
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

        // Minimum role target
        $minimumRole = Role::where("shortName", $targetRole)->first();

        // User's role id must be equals or minus than the minimum role target (because role are sorted by priority)
        if($user->role_id <= $minimumRole->id){
            return $next($request);
        }

        // Else, return unauthorized json response
        $JSONResponseHelper = new JSONResponseHelper();
        return $JSONResponseHelper->unauthorizedJSONResponse();
    }
}
