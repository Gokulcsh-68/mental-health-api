<?php

namespace App\Middleware;

use App\Entities\ApiAccess;
use Carbon\Carbon;
use Closure;
use Illuminate\Auth\Access\AuthorizationException;

class ApiServiceAuthenticate
{
    public function handle($request, Closure $next)
    {
        
        $token = $request->header('x-api-key');

        if (empty($token) === true) {

            throw new AuthorizationException('Authorization header not found');
        }

        $apiAccess = ApiAccess::where('token', $token)
                    ->where('active',1)
                    ->where('username','ApiServiceToken')
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
