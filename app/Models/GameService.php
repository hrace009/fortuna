<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Facades\App\Services\GameServices\ServicesFactory;

class GameService extends Model
{
    public $timestamps = false;

    public function scopegetEnabled($query)
    {
        return $query->whereEnabled(true)->get();
    }

    public function service(string $service)
    {
        return ServicesFactory::make($service);
    }
}
