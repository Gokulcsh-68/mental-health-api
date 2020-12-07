<?php

namespace App\Middleware;

use Closure;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class ACLMiddleware
{

    public function handle($request, Closure $next, $parent, $access)
    {
    	if ($parent === 'resource') {
    		$parent = $request->attributes->get('resource');
    	}

        if (!$request->user()->canAccess($parent, $access)) {

            throw new AccessDeniedHttpException();
        }

        return $next($request);
    }
}
