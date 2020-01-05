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
    return response()->json([
        'code' => 200,
        'message' => 'ok',
        'server_time' => \Carbon\Carbon::now()->toDateTimeLocalString()
    ]);
});

$router->group(['middleware' => 'auth'], function () use($router) {
    $router->get('wishlist',  ['uses' => 'WishlistController@list']);
    $router->post('wishlist',  ['uses' => 'WishlistController@create']);

    $router->group(['prefix' => 'wishlist/{id_wishlist:[0-9]+}'], function () use ($router) {
        $router->get('/',  ['uses' => 'WishlistController@show']);
        $router->put('/',  ['uses' => 'WishlistController@edit']);
        $router->delete('/',  ['uses' => 'WishlistController@delete']);
        
        $router->get('/product',  ['uses' => 'ProductController@list']);
        $router->post('/product',  ['uses' => 'ProductController@create']);
        $router->group(['prefix' => 'product/{id_product:[0-9]+}'], function () use ($router) {
            $router->get('/',  ['uses' => 'ProductController@show']);
            $router->put('/',  ['uses' => 'ProductController@edit']);
            $router->delete('/',  ['uses' => 'ProductController@delete']);
        });
    });
});