<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketCategory extends Model
{
    protected $fillable = ['name'];
    /**
     * Indicates that this model shouldn't be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * A category may have many tickets.
     *
     * @return void
     */
    public function tickets()
    {
        return $this->hasMany(\App\Models\Ticket::class, 'category_id');
    }
}
