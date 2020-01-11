<?php

namespace App\Http\Controllers\User\Profile;

use App\Events\ProfilePhoto;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\AvatarRequest;
use App\Http\Resources\UserResource;

class AvatarController extends Controller
{
    public function store(AvatarRequest $request)
    {
        // TODO
    }
}
