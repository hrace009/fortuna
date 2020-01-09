<?php

namespace App\Http\Controllers\User\Profile;

use App\Events\ProfilePhoto;
use App\Models\File;
use App\Http\Controllers\Controller;
use App\Http\Requests\AvatarRequest;
use App\Http\Resources\UserResource;
use App\Repositories\User\UserRepository;
use Illuminate\Support\Facades\DB;

class AvatarController extends Controller
{
    protected $users;

    /**
     * Create new PhotoController instance.
     */
    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    public function store(AvatarRequest $request)
    {
        // TODO
    }
}
