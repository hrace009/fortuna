<?php

namespace App\Http\Controllers\User\Ticket;

use App\Events\NewTicket;
use App\Models\File;
use App\Http\Controllers\Controller;
use App\Http\Requests\TicketRequest;
use App\Http\Resources\TicketCategoriesResource;
use App\Http\Resources\TicketResource;
use App\Notifications\Ticket\TicketCreated;
use App\Services\TicketService;
use App\Models\Ticket;
use App\Models\TicketCategory;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    private $ticket;

    public function __construct(TicketService $ticket)
    {
        $this->ticket = $ticket;
    }

    /**
     * Get all user tickets.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $tickets = $this->ticket->getUserTickets(
            $request->user()->id
        );

        return TicketResource::collection($tickets);
    }

    /**
     * Store a newly created ticket in database.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return TicketResource
     */
    public function store(TicketRequest $request)
    {
        $ticket = $request->user()->tickets()->create($request->only('category_id', 'subject', 'message'));

        if ($request->has('attachments') && ! is_null($request->attachments)) {
            $pathParts = pathinfo(Crypt::decryptString($request->attachments));

            $ticket->addMedia("{$pathParts['dirname']}/{$pathParts['basename']}")->toMediaCollection();
        }
        

        event(new NewTicket($ticket));

        $ticket->owner->notify(new TicketCreated($ticket));

        return new TicketResource($ticket);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($ticket)
    {
        /*  $ticket = QueryBuilder::for(Ticket::class)
         ->allowedIncludes(['replies', 'file'])
         ->where([
             'user_id' => request()->user()->id,
             'track_id' => $ticket,
         ])
         ->first(); */
        $ticket = $this->ticket->getTicket($ticket);

        $this->authorize('view', $ticket);

        return new TicketResource($ticket);
    }

    /**
     *  Close (mark as resolved) the specified ticket.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($ticket)
    {
        $ticket = $this->ticket->closeTicket($ticket);

        $this->authorize('delete', $ticket);

        return new TicketResource($ticket);
    }

    /**
     * Return available ticket categories.
     *
     * @return \Illuminate\Http\Response
     */
    public function categories()
    {
        $categories = TicketCategory::all();

        return TicketCategoriesResource::collection($categories);
    }
}
