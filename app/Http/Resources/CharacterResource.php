<?php

namespace App\Http\Resources;

use App\Models\GameService;
use Illuminate\Http\Resources\Json\JsonResource;
use Vinkla\Hashids\Facades\Hashids;

class CharacterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => Hashids::encode($this->id),
            'game_account' => Hashids::encode($this->user_id),
            'name' => $this->name,
            'class' => __("app.game.classesName.$this->cls"),
            'level' => $this->level,
            'cultivation' => __("app.game.cultivationName.$this->level2"),
            'game_services' => GameServicesResource::collection(GameService::whereEnabled(true)->get()),
        ];
    }
}
