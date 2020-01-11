<?php

namespace App\Http\Controllers\User\Game;

use App\Models\GameService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\GameServicesResource;

class ServicesController extends Controller
{
    public function index()
    {
        return GameServicesResource::collection(GameService::getEnabled());
    }

    /**
     * Execute the service.
     *
     * @param Request $request
     * @param GameService $gameService
     * @return void
     */
    public function execute(Request $request, GameService $gameService)
    {
        return $gameService->service($request->service)->executeService(decodeHashIdentifier($request->role_id), decodeHashIdentifier($request->game_account));
    }

    /**
     * TODO: Avoid hard-coding service. Could be used by any game service, where would be great
     * to request user confirmation.
     */
    public function process(Request $request, GameService $gameService)
    {
        return $gameService->service('storehousepasswd')->processService($request->service_token);
    }
}
