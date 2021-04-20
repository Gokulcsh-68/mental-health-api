<?php

namespace App\Middleware;

use Closure;
use App\Utils\AuthHelper;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Auth\Access\AuthorizationException;
use Carbon\Carbon;

class UserAuthenticate
{
    use AuthHelper;
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if ($this->auth->guard($guard)->guest()) {

            throw new AuthorizationException("Unauthorized");
        }

        $response = $next($request);

        // Check for token to refresh or not
        $authorization = $request->bearerToken();
        $decodedToken = $this->decodeJwt($authorization);
        $expiration_time = Carbon::createFromTimestamp($decodedToken->exp)->toDateTimeString(); 

        $diffrence = Carbon::now()->diffInMinutes($expiration_time);
        // $response['diff'] = $diffrence;
        if($diffrence < 5) {
            $response_content = $response->getOriginalContent();
            $response_content['refresh_token'] = $this->refreshToken($request, $decodedToken);;
            $response->setContent(json_encode($response_content));

        }

        return $response;
    }
}
