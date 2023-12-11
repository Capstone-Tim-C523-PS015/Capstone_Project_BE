<?php

use Illuminate\Http\Request;

$router->get('/', function () {
    return response()->json(['message' => 'capstone project C523-PS015 - backend']);
});

$router->group(['prefix' => 'auth'], function () use ($router) {
    $router->post('login', 'AuthController@login');
    $router->post('register', 'AuthController@register');
    $router->post('logout', 'AuthController@logout');
});

$router->group(['prefix' => 'user'], function () use ($router) {
    $router->get('/', 'UserController@index');
    $router->post('/', 'UserController@update');
    $router->put('/password', 'UserController@updatePassword');
    $router->delete('/', 'UserController@delete');
});

$router->group(['prefix' => 'todo'], function () use ($router) {
    $router->get('now', 'TodoController@now');
    $router->get('nowAndSoon', 'TodoController@nowAndSoon');
    $router->get('tomorrow', 'TodoController@tomorrow');
    $router->get('yesterday', 'TodoController@yesterday');
    $router->get('span', 'TodoController@span');
    $router->get('history', 'TodoController@history');
    $router->get('priority', 'TodoController@priority');

    $router->get('/', 'TodoController@all');
    $router->get('/{id}', 'TodoController@single');
    $router->post('/', 'TodoController@store');
    $router->put('/{id}', 'TodoController@update');
    $router->delete('/{id}', 'TodoController@delete');
});

$router->group(['prefix' => 'activity'], function () use ($router) {
    $router->get('now', 'ActivityController@now');
    $router->get('tomorrow', 'ActivityController@tomorrow');
    $router->get('yesterday', 'ActivityController@yesterday');
    $router->get('span', 'ActivityController@span');

    $router->get('/', 'ActivityController@all');
    $router->get('/{id}', 'ActivityController@single');
    $router->post('/', 'ActivityController@store');
    $router->put('/{id}', 'ActivityController@update');
    $router->delete('/{id}', 'ActivityController@delete');
});

$router->post('header', function (Request $request) {
    return $request->headers->all();
});

$router->group(['prefix' => 'reset'], function () use($router){
    $router->post('email', 'ResetPasswordController@email');
    $router->get('verifyToken', 'ResetPasswordController@verifyToken');
    $router->post('updatePassword', 'ResetPasswordController@updatePassword');
});
