<?php

namespace App\Game\Logs;

use Illuminate\Database\Eloquent\Model;

class Trade extends Model
{
    protected $table = 'pw_trades';

    protected $fillable = [
        'roleidA',
        'roleidB',
        'moneyA',
        'moneyB',
        'objectsA',
        'objectsB',
    ];

    protected $with = ['roleA', 'roleB'];

    /**
     * The roleidA.
     *
     * @return void
     */
    public function roleA()
    {
        return $this->belongsTo(\App\Models\Roles::class, 'roleidA', 'id');
    }

    /**
     * The roleidB.
     *
     * @return void
     */
    public function roleB()
    {
        return $this->belongsTo(\App\Models\Roles::class, 'roleidB', 'id');
    }
}
