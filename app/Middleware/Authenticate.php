<?php

namespace App\Middleware;

use Closure;
use Exception;
use Firebase\JWT\JWT;
use App\Entities\User;
use Illuminate\Auth\Access\AuthorizationException;
use App\Utils\AuthHelper;

class Authenticate
{
    use AuthHelper;
    public function handle($request, Closure $next)
    {
        $authorization = $request->bearerToken();

        if (empty($authorization) === true) {
            throw new AuthorizationException('Authorization header not found');
        }

        try {

            // $decodedToken = JWT::decode($authorization, config('api.app.key'), ['HS256']);
            $decodedToken = $this->decodeJwt($authorization);

            
            if (empty($decodedToken->data) === true) {
                
                throw new AuthorizationException("Transaction key not found");                    
            }

            $payload['user'] = User::where('id', $decodedToken->data)
                ->firstOrFail();

            /*if ($payload['user']->status !== 'Active') {

                throw new AuthorizationException("User Not Active"); 
            }*/
            $request->user =  $payload;
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
