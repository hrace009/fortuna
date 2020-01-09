<?php

namespace App\Http\Controllers\User\Game;

use App\Game\User;
use App\Http\Controllers\Controller;
use App\Http\Resources\CharacterResource;
use App\Jobs\UserUpdateGameCharacters;
use App\Models\Roles;
use Facades\App\Services\ApiConnect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class RolesController extends Controller
{
    public function updateCharacters(Request $request)
    {
        //ApiConnect::dispatch();

        $user = User::where('ID', decodeHashIdentifier($request->user_id))->first();

        if ($user->characterUpdateIsRunning()) {
            return response()->json([
                'message' => 'Opa! Estamos trabalhando nisso, aguarde mais um pouco. :)',
            ], 200);
        }

        //UserUpdateGameCharacters::dispatch($user);

        return response()->json([
            'message' => 'Vamos atualizar os personagem por de trás dos panos, te aviaremos assim que isso acabar.',
        ], 200);
    }

    /**
     * Get user roles.
     *
     * @param Request $request
     * @return mixed
     */
    public function getRoles(Request $request)
    {
        $user = User::where('ID', decodeHashIdentifier($request->user_id))->firstOrFail();

        if ($request->user()->id !== (string) $user->cid) {
            return response()->json(
                ['error' => 'Você nao tem permissão para visualizar essa página.'],
                403
            );
        }

        $getCharacters = Roles::where('user_id', $user->ID)->get();

        return CharacterResource::collection($getCharacters);
    }
}
