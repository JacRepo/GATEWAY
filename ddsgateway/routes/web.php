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

//For laravel server
$router->get('/', function () use ($router) {
    return $router->app->version();
});


//For user token
$router->post('/register', 'AuthController@register');
$router->post('/login', 'AuthController@login');

$router->group(['middleware' => 'auth'], function () use ($router) {
    $router->get('/me', 'AuthController@me');
    
    //API GATEWAY FOR SITE1
    $router->get('/users1', 'User1Controller@index');
    $router->post('/users1', 'User1Controller@add');  // create new user 
    $router->get('/users1/{id}', 'User1Controller@show'); // get user by id
    $router->put('/users1/{id}', 'User1Controller@update'); // update user 
    $router->patch('/users1/{id}', 'User1Controller@update'); // update user 
    $router->delete('/users1/{id}', 'User1Controller@delete'); // delete 

    //API GATEWAY FOR SITE2
    $router->get('/users2', 'User2Controller@index');
    $router->post('/users2', 'User2Controller@add');  // create new user 
    $router->get('/users2/{id}', 'User2Controller@show'); // get user by id
    $router->put('/users2/{id}', 'User2Controller@update'); // update user 
    $router->patch('/users2/{id}', 'User2Controller@update'); // update user 
    $router->delete('/users2/{id}', 'User2Controller@delete'); // delete 
});