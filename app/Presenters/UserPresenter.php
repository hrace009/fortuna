<?php

namespace App\Presenters;

use Laracasts\Presenter\Presenter;

class UserPresenter extends Presenter
{
    /**
     * Get user status.
     *
     * @return void
     */
    public function statusLabel()
    {
        return __("app.userStatus.{$this->status}");
    }
}
