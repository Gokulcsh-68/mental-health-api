<?php

namespace App\Middleware;

use Closure;
use Carbon\Carbon;
use App\Entities\ApiAccess;
use Illuminate\Auth\Access\AuthorizationException;

class ClientAuthenticate
{
    public function handle($request, Closure $next)
    {
        $token = $request->header('x-api-key');

        if (empty($token) === true) {
            
           throw new AuthorizationException('Authorization header not found');
        }

        $apiAccess = ApiAccess::where('token', $token)
            ->first();

        if (!$apiAccess) {
            
            throw new AuthorizationException('Invalid token.');
        }

        if (Carbon::now() > $apiAccess->expiry_date) {

            throw new AuthorizationException('Token expired');
        }

        return $next($request);
    }
}
