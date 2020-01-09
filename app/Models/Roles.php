<?php

namespace App\Models;

use App\Notifications\BankResetConfirmation as BankResetConfirmationNotification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Roles extends Model
{
    use Notifiable;

    protected $table = 'game_characters';

    protected $guarded = [];

    protected $with = ['game'];

    /**
     * An role belongs to a game account.
     *
     * @return void
     */
    public function game()
    {
        return $this->belongsTo(\App\Game\User::class, 'user_id', 'ID');
    }

    /**
     * The roleidA.
     *
     * @return void
     */
    public function trades()
    {
        return $this->hasMany(\App\Game\Logs\Trade::class, 'roleidA', 'id');
    }

    /**
     * Get the faction that belongs to the role.
     */
    public function faction()
    {
        return $this->hasOne(\App\Game\Faction::class, 'fid', 'faction_id');
    }

    /**
     * Send the confirmation notification.
     *
     * @param string $token
     *
     * @return void
     */
    public function sendBankResetConfirmation($token)
    {
        $this->notify(new BankResetConfirmationNotification($token));
    }
}
