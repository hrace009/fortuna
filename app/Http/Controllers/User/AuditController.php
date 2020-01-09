<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\ActivityResource;
use App\Repositories\User\UserRepository;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    private $user;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserRepository $user)
    {
        $this->user = $user;
    }

    public function activity(Request $request)
    {
        $userActivity = $this->user->getActivity(
            $request->user(),
            $request->per_page ?? 10
        );

        return ActivityResource::collection($userActivity);
    }
}
