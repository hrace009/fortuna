<?php

namespace App\Game;

use Illuminate\Database\Eloquent\Model;

class Faction extends Model
{
    protected $guarded = [];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'game_factions';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'fid';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /* Indicates if the model should be timestamped.
    *
    * @var bool
    */
    public $timestamps = false;
}
