<?php

namespace App\Middleware;

use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class ResourceMiddleware
{
    public function handle($request, Closure $next, $resource = null)
    {
        $resource = empty($request->route()[2]['resource']) === false ? $request->route()[2]['resource'] : $resource;
        if ($resource) {
        	$resource = ucfirst(camel_case(str_singular($resource)));
        	$class = sprintf('\App\Entities\%s', $resource);

	        if (class_exists($class)) {
                $entity = app($class);
                $action = $this->getCurrentAction($request->method());
                
                if (!$entity->canDoAction($action)) {
                    throw new MethodNotAllowedHttpException([]);
                }
	        	$request->attributes->add(["entity" => $entity, "resource" => $resource]);

        		return $next($request);
	        }

	        throw new ModelNotFoundException();
        }

       throw new NotFoundHttpException();
    }

    private function getCurrentAction($method)
    {
        $action = null;

        switch ($method) {
            case 'GET':
                $action = 'VIEW';
                break;
            case 'POST':
                $action = 'CREATE';
                break;
            case 'PUT':
            case 'PATCH':
                $action = 'UPDATE';
                break;
            case 'DELETE':
                $action = 'DELETE';
                break;
        }

        return $action;
    }
}
