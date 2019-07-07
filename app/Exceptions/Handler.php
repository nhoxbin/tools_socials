<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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
    public function render($request, Exception $e)
    {
        $message;
        $error_code = 0;
        if ($e instanceof ModelNotFoundException) {
            $message = ['error' => 'Entry for Model '.str_replace('App\\', '', $e->getModel()).' not found!'];
            $error_code = 404;
        }

        if ($e instanceof MethodNotAllowHttpException) {
            dd($e->getMethod());
            $message = ['error' => 'This Route is not Support method!'];
            $error_code = 405;
        }

        if ($e instanceof QueryException) {
            $message = ['error' => 'QueryException Error!'];
            $error_code = 404;
        }

        if ($error_code != 0) {
            return response($message, $error_code);
        }

        return parent::render($request, $e);
    }
}
