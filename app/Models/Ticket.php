<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use App\Services\FileManager\Traits\UploadFile;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Ticket extends Model implements HasMedia
{
    use HasMediaTrait;

    const PENDING = 1;
    const WAITING_USER = 2;
    const WAITING_STAFF = 3;
    const RESOLVED = 4;

    /**
     * Boot function from laravel.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->ip_address = request()->ip();
            $model->track_id = substr_replace(strtoupper(str_random(8)), '-', 3, 0);
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'subject', 'message', 'game_account',  'category_id', 'status_id', 'closed_at',
        'track_id', 'ip_address',
    ];

    // REPLIES and FILES
    protected $with = ['owner', 'replies', 'lastReply', 'category', 'status', 'media'];

    /**
     * Ticket has owner.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * A ticket may have many replies.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function replies()
    {
        return $this->hasMany(\App\Models\TicketReply::class, 'track_id', 'track_id');
    }

    /**
     * Get the latest reply from the ticket.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function lastReply()
    {
        return $this->hasOne(TicketReply::class, 'track_id', 'track_id')->latest();
    }

    /**
     * A ticket belongs to a category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(\App\Models\TicketCategory::class);
    }

    /**
     * A ticket has a status (dynamic).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status()
    {
        return $this->belongsTo(\App\Models\TicketStatus::class, 'status_id');
    }

    /**
     * Add a reply to the ticket.
     *
     * @param array $reply
     *
     * @return Model
     */
    public function addReply($reply)
    {
        $reply = $this->replies()->create($reply);

        return $reply;
    }

    /**
     * Check if ticket is closed.
     *
     * @return bool
     */
    public function isClosed()
    {
        return ! is_null($this->closed_at);
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

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'track_id';
    }
}
