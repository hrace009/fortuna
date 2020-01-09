<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class TicketReply extends Model implements HasMedia
{
    use HasMediaTrait;
    /**
     * Don't auto-apply mass assignment protection.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * All of the relationships to be touched.
     *
     * @var array
     */
    protected $touches = ['ticket'];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['owner', 'media'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'attachments' => 'array',
    ];

    /**
     * Boot function from laravel.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->user_id = auth()->user()->id;
            $model->ip_address = request()->ip();
            $model->staff = false;
        });
    }

    /**
     * Get related ticket.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'track_id', 'track_id');
    }

    /**
     * Get reply's owner.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->select(['id', 'name', 'email']);
    }

    /**
     * The folder where attachments will be stored.
     *
     * @return void
     */
    public function folder()
    {
        return 'attachments';
    }
}
