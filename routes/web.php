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
     * Admins end-points
     */
    $router->get('/admins', 'AdminController@index');
    $router->post('/admins', 'AdminController@store');
    $router->post('/admins/login', 'AdminController@login');
    $router->post('/admins/logout', 'AdminController@logout');
    $router->post('/admins/refresh', 'AdminController@refresh');
    $router->get('/admins/{id}', 'AdminController@show');
    $router->get('/admins/auth/me', 'AdminController@me');
    $router->put('/admins/{id}', 'AdminController@update');
    $router->delete('/admins/{id}', 'AdminController@destroy');

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
    $router->put('/users/{id}', 'UserController@update');
    $router->put('/users/auth/me', 'UserController@updateMe');
    $router->delete('/users/{id}', 'UserController@destroy');

});