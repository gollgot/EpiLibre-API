<?php

namespace App\Http\Middleware;

use App\CustomHelpers\JSONResponseHelper;
use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
use PHPUnit\Util\Json;

class Authenticate
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * uses the AuthServiceProvider boot() method to check the auth process
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        // TokenAPI is wrong -> return unauthorized json response
        if ($this->auth->guard($guard)->guest()) {
            $JSONResponseHelper = new JSONResponseHelper();
            return $JSONResponseHelper->unauthorizedJSONResponse();
        }

        return $next($request);
    }
}
