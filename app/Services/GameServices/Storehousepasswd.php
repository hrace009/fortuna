<?php

namespace App\Services\GameServices;

use App\Game\User as UserGame;
use App\Game\BankReset as StorehouseReset;
use App\Notifications\BankResetConfirmation;
use Facades\App\Services\ApiConnect as GameAPI;

class Storehousepasswd
{
    public function executeService(int $role_id, int $account)
    {
        if (! $role_id || is_null($role_id) || ! is_numeric($role_id)) {
            return response()->json(['error' => true, 'message' => 'Role not found.']);
        }

        // Send a request to the API to get user roles
        $response = GameAPI::dispatch(
            [
                'service' => '/role/storehousepasswd/request',
                'data' => [
                    'role_id' => $role_id,
                ],
            ]
        );

        if ($response['error']) {
            return response()->json(['error' => $response['error'], 'message' => $response['message']], 400);
        }

        $gameAccount = UserGame::byId($account)->first();

        $createToken = StorehouseReset::updateOrCreate(
            ['role_id' => $role_id],
            [
                'user_id' => auth()->user()->id,
                'game_account' => ['id' => $account, 'name' => $gameAccount->name],
                'token' => hash_hmac('sha256', str_random(64), $role_id),
            ]
        );

        activity()->causedBy(auth()->user())->log("Solicitou o remoção de senha do banqueiro do personagem {$createToken->role->name}).");

        auth()->user()->notify(new BankResetConfirmation($createToken));

        return response()->json(['error' => false, 'message' => 'Enviamos um e-mail de confirmação para '.auth()->user()->email.'.'], 200);
    }

    /**
     * Process the storehouse.
     *
     * @return void
     */
    public function processService($service_token)
    {
        $storehouse = StorehouseReset::byToken($service_token)->first();

        if (! $storehouse) {
            return response()->json(['error' => true, 'message' => 'Service token not found.'], 400);
        }

        $requestAPI = GameAPI::dispatch(
            [
                'service' => 'role/storehousepasswd/reset',
                'data' => ['role_id' => $storehouse->role_id],
            ]
        );

        if ($requestAPI['error']) {
            return response()->json(['error' => $requestAPI['error'], 'message' => $requestAPI['message']], 400);
        }

        activity()->causedBy(auth()->user())->log("Resetou a senha do banqueiro do personagem {$storehouse->role->name} na conta {$storehouse->game_account['name']}");

        $storehouse->delete();

        return response()->json(['Senha do banqueiro removida com sucesso.'], 200);
    }
}
