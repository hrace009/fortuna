<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketStatus extends Model
{
    /**
     * Indicates that this model shouldn't be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * A status may have many tickets.
     *
     * @return void
     */
    public function tickets()
    {
        return $this->hashMany(\App\Models\Ticket::class, 'status_id');
    }
}
