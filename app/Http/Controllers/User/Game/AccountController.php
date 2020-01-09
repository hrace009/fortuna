<?php

namespace App\Http\Controllers\User\Game;

use App\Events\GamePasswordChanged;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserGameResource;
use App\Repositories\User\Game\UserRepository as UserGameRepository;
use App\Repositories\User\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AccountController extends Controller
{
    protected $user;
    protected $userGame;

    public function __construct(UserRepository $user, UserGameRepository $gameUser)
    {
        $this->user = $user;
        $this->gameUser = $gameUser;
    }

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

    /**
     * Get associated game accounts from the authenticated user.
     *
     * @return void
     */
    public function all(Request $request)
    {
        $accounts = $this->user->getGameAccounts($request->user());

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

    public function update($user, Request $request)
    {
        $this->validate($request, [
            'password' => 'required|confirmed|min:6',
        ]);

        $user = $this->gameUser->getUser(decodeHashIdentifier($user));

        if (! $user) {
            return $this->errorWrongArgs(__('app.errors.account_not_found'));
        }

        if (! $request->user()->can('update', $user)) {
            return $this->setStatusCode(403)->errorUnauthorized(__('app.errors.unauthorized'));
        }

        event(new GamePasswordChanged($user));

        $user->update([
            'passwd' => hash_passwd($user->name, $request->password),
        ]);

        return response()->json(['success' => true], Response::HTTP_OK);
    }
}
