<?php

namespace App\Game;

use Illuminate\Database\Eloquent\Model;

class BankReset extends Model
{
    protected $guarded = [];
    public $timestamps = false;
    protected $table = 'game_storehouse_resets';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'game_account' => 'array',
    ];

    /**
     * Get user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id', 'id');
    }

    /**
     * Get role data.
     *
     * @return void
     */
    public function role()
    {
        return $this->belongsTo(\App\Models\Roles::class, 'role_id', 'id');
    }

    /**
     * Get reset data by given token.
     *
     * @param [type] $query
     * @param [type] $token
     * @return void
     */
    public function scopeByToken($query, $token)
    {
        return $query->where('token', $token);
    }
}
