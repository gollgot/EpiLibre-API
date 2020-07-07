<?php

namespace App\Providers;

use App\User;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        $this->app['auth']->viaRequest('api', function ($request) {
            // Fetch the JWT token from bearer (Authorization header)
            $jwt = $request->bearerToken();
            // Successfully decoded (no error) -> return the connected User
            try {
                $decoded = JWT::decode($jwt, env("JWT_SECRET"), array('HS256'));
                return User::where("email", $decoded->email)->first();
            }
            // Error when decoded -> Return null
            catch (\Exception $e) {
               return null;
            }
        });
    }
}
