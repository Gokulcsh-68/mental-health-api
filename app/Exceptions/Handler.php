<?php

namespace App\Exceptions;

use Throwable;
use App\Utils\HttpResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $e
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $e)
    {
        $httpResponse = app(HttpResponse::class);
        if ($e instanceof ModelNotFoundException) {
            $httpResponse->setHttpCode(404)->setHttpMessage($e->getMessage());
        } elseif ($e instanceof BadRequestHttpException) {
            $httpResponse->setHttpCode(400)->setHttpMessage($e->getMessage());
        } elseif ($e instanceof NotFoundHttpException) {
            $httpResponse->setHttpCode(404)->setHttpMessage('Requested api path not found');
        } elseif ($e instanceof MethodNotAllowedHttpException) {
            $httpResponse->setHttpCode(405)->setHttpMessage('Method Not Allowed');
        } elseif ($e instanceof ConflictHttpException) {
            $httpResponse->setHttpCode(409)->setHttpMessage($e->getMessage())->setHttpData(['internal_code' => $e->getCode()]);
        } elseif ($e instanceof UnauthorizedException || $e instanceof AuthorizationException) {
            $httpResponse->setHttpCode(401)->setHttpMessage('Unauthorized');
        } elseif ($e instanceof ValidationException) {
            $httpResponse->setHttpCode(422)->setHttpMessage('Data Validation Failed')->setHttpData(["errors" => $this->reformatError($e->errors())]);
        } else {
            $httpResponse->setHttpCode(500)->setHttpMessage(!app()->environment('production') ? $e->getMessage() : 'Internal server error');
        }

        exceptionLogger("Exception Handler", $e);
        
        return $httpResponse->jsonResponse();
    }

    private function reformatError($errors)
    {
        $formatedError = [];
        foreach ($errors as $key => $value) {
            $formatedError[$key] = $value[0];
        }

        return $formatedError;
    }
}
