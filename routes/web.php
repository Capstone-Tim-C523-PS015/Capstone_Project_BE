<?php

$router->get('/', function () use ($router) {
    // return $router->app->version();
    return response()->json(['message' => 'hello world']);
});

$router->group(['prefix'=>'api'],function() use ($router){
    $router->post('login','AuthController@login');
    $router->post('register','AuthController@register');
    $router->post('logout','AuthController@logout');
});