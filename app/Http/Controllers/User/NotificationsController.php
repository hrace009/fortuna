<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Notifications\DatabaseNotification;

class NotificationsController extends Controller
{
    /**
     * Get the user's notifications.
     *
     * @return void
     */
    public function notifications(int $offset, int $limit)
    {
        return request()->user()->unreadNotifications()->skip($offset)->take($limit)->get();
    }

    /**
     * Get all user's notifications.
     *
     * @return void
     */
    public function allNotifications()
    {
        return request()->user()->notifications()->get();
    }

    /**
     * Mark the notification as read.
     *
     * @return void
     */
    public function markAsRead(DatabaseNotification $notification)
    {
        return tap($notification)->markAsRead();
    }

    /**
     * Mark all user notification as read.
     *
     * @return void
     */
    public function markAllAsRead()
    {
        return request()->user()->unreadNotifications->markAsRead();
    }
}
