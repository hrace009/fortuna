<?php

namespace App\Game;

use Illuminate\Database\Eloquent\Model;

class Items extends Model
{
    public $table = 'game_items';
    public $timestamp = false;
    public $incrementing = false;
    public $guarded = [];
}
