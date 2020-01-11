<?php

namespace App\Http\Controllers\User\Ticket;

use App\Models\Ticket;
use App\Events\NewTicket;
use Illuminate\Http\Request;
use App\Models\TicketCategory;
use App\Services\TicketService;
use App\Http\Controllers\Controller;
use App\Http\Requests\TicketRequest;
use Illuminate\Support\Facades\Crypt;
use App\Http\Resources\TicketResource;
use App\Notifications\Ticket\TicketCreated;
use App\Http\Resources\TicketCategoriesResource;

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
        $tickets = $request->user()->tickets()->paginate(10);

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
        $ticket = request()->user()->tickets()->whereTrackId($ticket)->first();

        $this->authorize('view', $ticket);

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
