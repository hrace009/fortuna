<?php

namespace App\Http\Controllers\User\Ticket;

use App\Exceptions\TicketClosed;
use App\Http\Controllers\Controller;
use App\Http\Requests\TicketReplyRequest;
use App\Http\Resources\TicketResource;
use App\Models\Ticket;
use Illuminate\Support\Facades\Crypt;

class ReplyController extends Controller
{
    public function store(TicketReplyRequest $request, $ticket)
    {
        $ticket = Ticket::where('track_id', $ticket)->firstOrFail();

        if ($ticket->isClosed()) {
            throw new TicketClosed();
        }

        $reply = $ticket->addReply([
            'message' => $request->message,
        ]);

        $ticket->status_id = Ticket::WAITING_STAFF;
        $ticket->save();

        if ($request->has('attachments') && ! is_null($request->attachments)) {
            $pathParts = pathinfo(Crypt::decryptString($request->attachments));

            $reply->addMedia("{$pathParts['dirname']}/{$pathParts['basename']}")->toMediaCollection();
        }

        return new TicketResource($ticket);
    }
}
