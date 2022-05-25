<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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
    return 'coguafood API';
});

$router->group(['namespace' => 'Api', 'prefix' => 'api'], function () use ($router) {
    /**
     * Users end-points
     */
    $router->post('/users', 'UserController@store');
    $router->post('/users/login', 'UserController@login');
    $router->post('/users/logout', 'UserController@logout');
    $router->post('/users/refresh', 'UserController@refresh');
    $router->get('/users', 'UserController@showAll');
    $router->get('/users/{id}', 'UserController@show');
    $router->get('/users/auth/me', 'UserController@me');
    $router->get('/users/auth/me/events', 'UserController@meWithEvents');
    $router->get('/users/{id}/events', 'UserController@showWithEvents');
    $router->put('/users/{id}', 'UserController@update');
    $router->put('/users/auth/me', 'UserController@updateMe');
    $router->delete('/users/{id}', 'UserController@destroy');

});