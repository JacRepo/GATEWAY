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

//Test your remote service
$router->get('/test', function () {
    return 'OK';
});

$router->get('/test-db', function () {
    try {
        DB::connection()->getPdo();
        return "Connected successfully to database: " . DB::connection()->getDatabaseName();
    } catch (\Exception $e) {
        return "Could not connect to the database. Error: " . $e->getMessage();
    }
});

//For user token
$router->post('/register', 'AuthController@register');
$router->post('/login', 'AuthController@login');

$router->group(['middleware' => 'auth'], function () use ($router) {
    $router->get('/me', 'AuthController@me');
    
    //API GATEWAY FOR SITE1
    $router->get('/users', 'User1Controller@index');
    $router->post('/users', 'User1Controller@add');  // create new user 
    $router->get('/users/{id}', 'User1Controller@show'); // get user by id
    $router->put('/users/{id}', 'User1Controller@update'); // update user 
    $router->patch('/users/{id}', 'User1Controller@update'); // update user 
    $router->delete('/users1/{id}', 'User1Controller@delete'); // delete 

    //API GATEWAY FOR SITE2
    $router->get('/employees', 'User2Controller@index');   // Get all users
    $router->post('/employees', 'User2Controller@add');  // create new user 
    $router->get('/employees/{empID}', 'User2Controller@show'); // get user by id
    $router->put('/employees/{empID}', 'User2Controller@update'); // update user 
    $router->patch('/employees/{empID}', 'User2Controller@update'); // update user 
    $router->delete('/employees/{empID}', 'User2Controller@delete'); // delete 
});