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
    return $router->app->version();
});

	$router->group(['middleware'=>['resource']], function ($router) {
	    $router->post('{resource}', 'ResourceService@create');
	    $router->put('{resource}/{id:[0-9]+}', 'ResourceService@update');
    	$router->patch('{resource}/{id:[0-9]+}', 'ResourceService@partialUpdate');
    	$router->get('{resource}', 'ResourceService@list');
	    $router->get('{resource}/first', 'ResourceService@getFirst');
	    $router->get('{resource}/{id:[0-9]+}', 'ResourceService@fetch');
	    $router->delete('{resource}/{id:[0-9]+}', 'ResourceService@delete');
    });
    