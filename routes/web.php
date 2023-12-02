<?php

$router->get('/', function () {
    return response()->json(['message' => 'capstone project C523-PS015 - backend']);
});

$router->group(['prefix' => 'auth'], function () use ($router) {
    $router->post('login', 'AuthController@login');
    $router->post('register', 'AuthController@register');
    $router->post('logout', 'AuthController@logout');
});

$router->group(['prefix' => 'todo'], function() use($router){
    $router->get('/', 'TodoController@all');
    $router->get('/{id}', 'TodoController@single');
    $router->post('/', 'TodoController@store');
    $router->put('/{id}', 'TodoController@update');
    $router->delete('/{id}', 'TodoController@delete');
});

$router->group(['prefix' => 'user'], function () use ($router) {
    $router->get('/', 'UserController@index');
    $router->post('/', 'UserController@update');
    $router->put('/password', 'UserController@updatePassword');
    $router->delete('/', 'UserController@delete');
});