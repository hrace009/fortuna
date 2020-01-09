<?php

namespace App\Exceptions;

use Exception;

class ForbiddenTransactionException extends Exception
{
    /**
     * Report the exception.
     *
     * @return void
     */
    public function report()
    {
        //
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        if ($request->wantsJson() && $request->user()->status == 'FORBIDDEN_TRANSACTION') {
            return response()->json(['error' => '[FORBIDDEN_TRANSACTION] You\'re not authorised to make this request. Please contact our Support Team.'], 403);
        }

        return true;
    }
}
