<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoldPackage extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    public static function findByUuid($packageId)
    {
        return self::wherePackageId($packageId);
    }
}
