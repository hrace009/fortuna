<?php

namespace App\Http\Controllers\User\Game;

use App\Events\GamePasswordChanged;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserGameResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AccountController extends Controller
{

    /**
     * Paginate associated game accounts from the authenticated user.
     *
     * @return void
     */
    public function index()
    {
        $accounts = auth()->user()->accounts()->simplePaginate(20);

        return UserGameResource::collection($accounts);
    }

    public function show($user, Request $request)
    {
        $user = $request->user()->accounts()->where('ID', decodeHashIdentifier($user))->firstOrFail();

        if (! $request->user()->can('view', $user)) {
            return \response()->json([
                'error' => __('You don\'t have permission to view this page.'),
            ], Response::HTTP_FORBIDDEN);
        }

        return new UserGameResource($user);
    }
}
