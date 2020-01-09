<?php

namespace App\Services\GameServices;

use Facades\App\Services\ApiConnect as GameAPI;

class Teleport
{
    public function executeService(int $role_id, int $account)
    {
        if (! $role_id || is_null($role_id) || ! is_numeric($role_id)) {
            return response()->json(['error' => true, 'message' => 'Role not found.']);
        }

        // Send a request to the API to get user roles
        $response = GameAPI::dispatch(
            [
                'service' => 'role/teleport',
                'data' => [
                    'role_id' => $role_id,
                    'posx' => 1285.395,
                    'posy' => 219.618,
                    'posz' => 1139.265,
                    'worldtag' => 1,
                ],
            ]
        );

        if ($response['error']) {
            return response()->json(['error' => $response['error'], 'message' => $response['message']], 400);
        }

        return response()->json(['error' => false, 'message' => 'Personagem teleportado com sucesso.'], 200);
    }
}
