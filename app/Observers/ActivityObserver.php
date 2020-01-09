<?php

namespace App\Observers;

use Spatie\Activitylog\Models\Activity;

class ActivityObserver
{
    /**
     * Handle the activity "saving" event.
     *
     * @param  \App\Spatie\Activitylog\Models\Activity  $activity
     * @return void
     */
    public function saving(Activity $activity)
    {
        /* $userAgent = [
            'browser'  => \Browser::browserFamily(),
            'browser_version'   => \Browser::browserVersion(),
            'os_name' => \Browser::platformFamily(),
            'os_version' => \Browser::platformVersion(),
        ]; */

        $activity->properties = $activity->properties->put('ip_address', request()->ip());
        //$activity->properties = $activity->properties->put('user_agent', json_encode($userAgent));
    }
}
