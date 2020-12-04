<?php

namespace App\Middleware;

use Closure;
use Exception;
use Firebase\JWT\JWT;
use App\Entities\User;
use Illuminate\Auth\Access\AuthorizationException;

class Authenticate
{
    public function handle($request, Closure $next)
    {
        $authorization = $request->bearerToken();

        if (empty($authorization) === true) {
            throw new AuthorizationException('Authorization header not found');
        }

        try {
            $decodedToken = JWT::decode($authorization, config('api.app.jwtKey'), ['HS256']);

            if (empty($decodedToken->sub) === true) {
                
                throw new AuthorizationException("Transaction key not found");                    
            }

            $payload['user'] = User::where('id', $decodedToken->sub)
                ->firstOrFail();

            /*if ($payload['user']->status !== 'Active') {

                throw new AuthorizationException("User Not Active"); 
            }*/

            $request->attributes->add($payload);

            return $next($request);
        }
        catch (\AuthorizationException $e) {
            throw new AuthorizationException($e->getMessage());
        } catch (\Exception $e) {
            throw new AuthorizationException($e->getMessage());
        }

        return $next($request);
    }
}
