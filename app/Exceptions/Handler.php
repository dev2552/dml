<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {

        if($exception instanceof NotFoundHttpException)
        {
            return response(['error'=>'Not Found'],404);
        }

        if($exception instanceof TokenMismatchException)
        {
             return response(['error'=>'This Action is Unauthorized'],419);
        }

       if($exception instanceof AuthorizationException)
        {
             return response(['error'=>'This Action is Unauthorized'],403);
        }

        if($exception instanceof MethodNotAllowedHttpException)
        {
             return response(['error'=>'This Action not Allowed'],405);
        }

        return parent::render($request, $exception);
    }
}