<?php

namespace App\Middleware;

use App\Entities\ApiAccess;
use Carbon\Carbon;
use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class PeripheralAuthenticate
{
    public function handle($request, Closure $next)
    {
        $token = $request->access_token;

        if (empty($token) === true) {
            throw new AuthorizationException('Authorization header not found');
        }

        $apiAccess = ApiAccess::where('token', $token)->first();

        if (!$apiAccess) {
            throw new AuthorizationException('Invalid token.');
        }

        if (Carbon::now() > $apiAccess->expiry_date) {
            throw new AuthorizationException('Token expired.');
        }

        return $next($request);
    }
}
