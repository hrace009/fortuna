<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Http\Resources\UserResource;
use App\Repositories\User\UserRepository;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    protected $user;

    public function __construct(UserRepository $user)
    {
        $this->users = $user;
    }

    /**
     * Get authenticated user data.
     */
    public function getUser(Request $request)
    {
        return new UserResource($request->user());
    }

    public function getLatestOrder()
    {
        $order = request()->user()->orders()->paid()->latest()->first();

        if ($order) {
            return new OrderResource($order);
        }

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Update user selected server.
     *
     * @param Request $request
     * @return void
     */
    public function updateServer(Request $request)
    {
        $user = $this->users->update(
            $request->user()->id,
            $request->only('current_server_zoneid')
        );

        return $this->respondWithItem($user, new UserTransformer());
    }

    public function updateDocument()
    {
        $document = request()->validate(
           [
               'document' => 'required|cpf',
           ]
        );

        $user = tap(request()->user())->update($document);

        return new UserResource($user);
    }
}
