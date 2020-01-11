<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;

class IconLog extends Model
{
    protected $primaryKey = 'faction_id';

    protected $fillable = ['user_id', 'faction_id'];

    /**
     * Verify if current model instance is less than 24 hours.
     */
    public function escrow()
    {
        return Carbon::parse($this->created_at)->lt(Carbon::now()->addDays(7));
    }
}
