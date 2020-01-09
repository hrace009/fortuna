<?php

namespace App\Game;

use Illuminate\Database\Eloquent\Model;

class Logs extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'pw';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'log_main';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'date' => 'datetime',
    ];

    /**
     * Get related item data.
     *
     * @return void
     */
    public function items()
    {
        return $this->belongsTo(\App\Game\Items::class, 'item_id', 'item_id');
    }
}
