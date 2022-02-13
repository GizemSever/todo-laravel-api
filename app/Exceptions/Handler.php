<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        if ($e instanceof AuthenticationException) {
            return response()->error(null, Response::HTTP_UNAUTHORIZED, __('auth.unauthorized'));
        }

        if ($e instanceof ValidationException) {
            return response()->error($e->errors(), Response::HTTP_BAD_REQUEST);
        }

        if ($e instanceof ModelNotFoundException) {
            return response()->error(null, Response::HTTP_NOT_FOUND, __('api.resource_not_found'));
        }

        if ($e instanceof AuthorizationException){
            return response()->error(null, Response::HTTP_FORBIDDEN);
        }

        if ($e instanceof ConflictHttpException){
            return response()->error(null, Response::HTTP_CONFLICT,  $e->getMessage());
        }

        return parent::render($request, $e);
    }
}
