<?php

namespace App\Models;

use App\Notifications\ResetPassword as ResetPasswordNotification;
use App\Traits\ShouldConfirmEmail;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Activitylog\Traits\LogsActivity;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable,
    ShouldConfirmEmail,
    CausesActivity, LogsActivity;

    /**
     * Boot function from laravel.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) Str::orderedUuid();
        });
    }

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'gender', 'birthday', 'two_factor_auth',
        'confirmation_token', 'status', 'document',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * @return int
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Get the oauth providers.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function oauthProviders()
    {
        return $this->hasMany(OAuthProvider::class);
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
     * Set the user's birthday.
     *
     * @param string $value
     *
     * @return string
     */
    public function setBirthdayAttribute($value)
    {
        return $this->attributes['birthday'] = Carbon::createFromFormat('d/m/Y', $value);
    }

    /**
     * Get the user's birthday.
     *
     * @param string $value
     *
     * @return string
     */
    public function getBirthdayAttribute()
    {
        if (! isset($this->attributes['birthday'])) {
            return;
        }

        return Carbon::parse($this->attributes['birthday'])->format('d/m/Y');
    }

    /**
     * An user may have many linked accounts.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function accounts()
    {
        return $this->hasMany(\App\Game\User::class, 'cid', 'id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * An user may have many orders.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(\App\Models\Payments::class, 'user_id', 'id');
    }

    /**
     * Find an user by the given id.
     *
     * @param $query
     * @param $code
     * @return void
     */
    public function scopeById($query, $id)
    {
        return $query->where('id', $id);
    }

    /**
     * Get user that has admin access.
     *
     * @param [type] $query
     * @return void
     */
    public static function scopeStaff($query)
    {
        return $query->whereAdmin(true);
    }

    public function formatDocument($document)
    {
        return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", '$1.$2.$3-$4', $document);
    }

    public function getDocumentAttribute($value)
    {
        return ! is_null($value) ? $this->formatDocument($value) : null;
    }

    public function scopeByConfirmationToken($query, string $token)
    {
        return $query->whereConfirmationToken($token);
    }

    /**
     * Get user avatar.
     *
     * @return void
     */
    public function getAvatar()
    {
        return 'https://secure.gravatar.com/avatar/'.md5(strtolower(trim($this->email))).'?s=145&d=identicon';
    }
}
