<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

// API
$router->group(['prefix' => 'api'], function () use ($router) {
    // Auth
    $router->post('/auth/login', 'AuthController@login');
    $router->post('/auth/register', 'AuthController@register');

    // Users
    $router->get('/users/pending', [
        'middleware' => ['auth', 'App\Http\Middleware\CheckRoleMiddleware:SUPER_ADMIN'],
        'uses' => 'UserController@pending'
    ]);
    $router->patch('/users/{user_id}/confirm', [
        'middleware' => ['auth', 'App\Http\Middleware\CheckRoleMiddleware:SUPER_ADMIN'],
        'uses' => 'UserController@confirm'
    ]);
    $router->patch('/users/{user_id}/unconfirm', [
        'middleware' => ['auth', 'App\Http\Middleware\CheckRoleMiddleware:SUPER_ADMIN'],
        'uses' => 'UserController@unconfirm'
    ]);

    // Products
    $router->get('/products', [
        'middleware' => ['auth'],
        'uses' => 'ProductController@index'
    ]);
    $router->put('/products/{product_id}', [
        'middleware' => ['auth', 'App\Http\Middleware\CheckRoleMiddleware:ADMIN'],
        'uses' => 'ProductController@update'
    ]);
    $router->post('/products', [
        'middleware' => ['auth', 'App\Http\Middleware\CheckRoleMiddleware:ADMIN'],
        'uses' => 'ProductController@store'
    ]);

    // Categories
    $router->get('/categories', [
        'middleware' => ['auth'],
        'uses' => 'CategoryController@index'
    ]);

    // Units
    $router->get('/units', [
        'middleware' => ['auth', 'App\Http\Middleware\CheckRoleMiddleware:ADMIN'],
        'uses' => 'UnitController@index'
    ]);

    // ORDERs
    $router->post('/orders', [
        'middleware' => ['auth'],
        'uses' => 'OrderController@store'
    ]);
});
