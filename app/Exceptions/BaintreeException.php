<?php

namespace App\Exceptions;

use BraintreeHttp\HttpException;
use Exception;

class BaintreeException extends Exception
{
    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param \Exception $exception
     *
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Exception               $exception
     *
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof HttpException) {
            return response()->json(['error' => $exception->getMessage()], 400);
        }

        return parent::render($request, $exception);
    }
}
