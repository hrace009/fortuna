<?php

namespace App\Game;

use Illuminate\Database\Eloquent\Model;

class Forbid extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'game';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'forbid';

    /**
     * Get related ticket.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(\App\Game\User::class, 'ID', 'user_id');
    }
}
