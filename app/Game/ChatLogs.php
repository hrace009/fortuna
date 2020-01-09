<?php

namespace App\Game;

use Illuminate\Database\Eloquent\Model;

class ChatLogs extends Model
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
    protected $table = 'log_chat';
}
