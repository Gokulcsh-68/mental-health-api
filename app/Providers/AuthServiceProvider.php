<?php

namespace App\Providers;

use App\Entities\User;
use App\Utils\AuthHelper;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    use AuthHelper;
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        $this->app['auth']->viaRequest('api', function ($request) {
            if ($request->bearerToken()) {
                $authorization = $request->bearerToken();
                $decodedToken = $this->decodeJwt($authorization);
                if (empty($decodedToken->data->userId) === false) {
                    $user = User::where('id', $decodedToken->data->userId)->active()->firstOrFail();

                    return $user;

                    /*if ($user->active) {
                $payload['data'] = $decodedToken->data;
                if ($user->provider) {
                $payload['provider'] = $user->provider;
                }
                if ($user->patient) {
                $payload['patient'] = $user->patient;
                }

                $request->attributes->add($payload);

                return $user;
                }*/
                }
            }

            return null;
        });
    }
}
