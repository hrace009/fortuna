<?php

namespace App\Game;

use App\Events\UpdateGameCharactersFinished;
use App\Events\UpdateGameCharactersRunning;
use App\Notifications\ResetPasswordGame as ResetPasswordNotification;
use DB;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Spatie\Activitylog\Traits\LogsActivity;

class User extends Model implements CanResetPasswordContract
{
    use Notifiable, LogsActivity, CanResetPassword;

    protected $connection = 'pw';

    protected $table = 'users';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'ID';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['ID', 'truename', 'name', 'email', 'passwd', 'creatime', 'cid', 'ugc_status'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['passwd', 'passwd2', 'answer'];

    /**
     * Indicates that this model shouldn't be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * An user can have many characters.
     *
     * @return void
     */
    public function characters()
    {
        return $this->hasMany(\App\Models\Roles::class, 'user_id', 'ID');
    }

    /**
     * Get reply's owner.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(\App\Models\User::class, 'id', 'cid');
    }

    /**
     * An user may have many cash added.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cashLog()
    {
        return $this->hasMany('App\Game\UseCashLog', 'userid', 'ID');
    }

    /**
     * An user may have many cash in the queue.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cashQueue()
    {
        return $this->hasMany('App\Game\UseCashNow', 'userid', 'ID');
    }

    /**
     * Set the user's birthday.
     *
     * @param string $value
     *
     * @return string
     */
    public function setcreatimeAttribute()
    {
        return $this->attributes['creatime'] = Carbon::now();
    }

    /**
     * Send the password reset notification.
     *
     * @param string $token
     *
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * Add cubi gold to the given account.
     *
     * @param int $userId
     * @param int $amount
     *
     * @return void
     */
    public static function addcash(int $userId, int $amount)
    {
        return DB::connection('pw')->statement('CALL usecash (?, ?, ? ,?, ?, ?, ?, @error)', [
            $userId,
            config('pw.zone_id'),
            0,
            config('pw.aid'),
            0,
            $amount * 100,
            1,
        ]);
    }

    /**
     * Add forbid status to the given account.
     *
     * 30030222
     * 08007220222
     *
     * 94,68
     * 224
     *
     * @param int $identifier The user's account identifier.
     * @param int $time Forbid time must be in SECONDS.
     * @param int $type Forbid type
     * @param string $reason The reason for the forbid
     * @param int $gmroleid The Role ID of the game masster that applied the forbid.
     *
     * @return bool
     */
    public static function addForbid(int $userId, int $time = 999999, int $type = 100, string $reason = '')
    {
        return DB::connection('pw')->statement('CALL addForbid (?, ?, ?, ?, ?)', [
            $userId,
            $type,
            $time,
            $reason,
            -1,
        ]);
    }

    public function characterUpdateIsRunning()
    {
        return $this->ugc_status === 'running';
    }

    public function markUpdateGameCharactersAsRunning()
    {
        UpdateGameCharactersRunning::dispatch(tap($this)->update([
            'ugc_status' => 'running',
        ]));
    }

    public function markUpdateGameCharactersAsFinished()
    {
        UpdateGameCharactersFinished::dispatch(tap($this)->update([
            'ugc_status' => 'finished',
        ]));
    }

    public function findByID($id)
    {
        return $this->where('ID', $id);
    }
}
