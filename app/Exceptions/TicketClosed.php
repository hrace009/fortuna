<?php

namespace App\Exceptions;

use Exception;

class TicketClosed extends Exception
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
        if ($request->wantsJson()) {
            return response()->json(['error' => 'Wrongs args.'], 400);
        }

        return abort(400, 'Wrong args.');
    }
}
