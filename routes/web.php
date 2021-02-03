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

$router->get('/key', function() {
    return \Illuminate\Support\Str::random(32);
});

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'v1/', 'middleware' => 'clientAuth'], function ($router) {

    $router->group([], function ($router) {
        $router->group(['prefix' => 'users'], function ($router) {
            $router->post('authenticate', 'AuthService@generalLogin');
            $router->post('forgot-password/email', 'AuthService@forgotPasswordEmail');
            $router->post('forgot-password/email-otp', 'AuthService@forgotPasswordEmailOtp');
            $router->post('verify-email', 'AuthService@verifyEmail');
            $router->post('verify-otp', 'AuthService@verifyOtp');
            $router->post('resend-otp', 'AuthService@resendOtp');
        });

        $router->get('resource/masters/list', 'MasterService@masterList');
        
    });

    $router->group(['middleware' => 'userAuth'], function ($router) {

        $router->group(['prefix' => 'users'], function ($router) {
            $router->patch('set-password', 'AuthService@setPassword');
            $router->patch('change-password', 'AuthService@changePassword');
            $router->patch('twofa', 'AuthService@twofa');
            $router->patch('communication', 'AuthService@communication');
            $router->get('info', 'AuthService@info');
            $router->post('uploadDocs', 'AuthService@uploadDocs');
            $router->post('uploadAvatar', 'AuthService@uploadAvatar');
            // $router->post('verify-otp', 'AuthService@verifyOtp');
            // $router->post('resend-otp', 'AuthService@resendOtp');
            $router->patch('{id:[0-9]+}/change-password', ['middleware' => 'acl:users,change-user-password', 'uses' => 'AuthService@changeUserPassword']);
        });

        // Consult provider list
        $router->get('resource/available-providers/list', 'ProviderService@list');

        /*Resource Operations*/
        $router->group(['prefix' => '/resource', 'middleware' => ['resource']], function ($router) {
            $router->post('{resource}', ['middleware' => 'acl:resource,create', 'uses' => 'ResourceService@create']);
            $router->put('{resource}/{id:[0-9]+}', ['middleware' => 'acl:resource,update', 'uses' => 'ResourceService@update']);
            $router->patch('{resource}/{id:[0-9]+}', ['middleware' => 'acl:resource,update', 'uses' => 'ResourceService@partialUpdate']);
            $router->delete('{resource}/{id:[0-9]+}', ['middleware' => 'acl:resource,delete', 'uses' => 'ResourceService@delete']);
            $router->get('{resource}', ['middleware' => 'acl:resource,view', 'uses' => 'ResourceService@list']);
            $router->get('{resource}/all', ['middleware' => 'acl:resource,view', 'uses' => 'ResourceService@getAll']);
            $router->get('{resource}/first', ['middleware' => 'acl:resource,view', 'uses' => 'ResourceService@getFirst']);
            $router->get('{resource}/{id:[0-9]+}', ['middleware' => 'acl:resource,view', 'uses' => 'ResourceService@fetch']);
            $router->get('{resource}/aggregate', ['middleware' => 'acl:resource,view', 'uses' => 'ResourceService@aggregate']);
        });
    });
});
