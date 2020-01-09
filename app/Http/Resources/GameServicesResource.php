<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GameServicesResource extends JsonResource
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
            'name' => __('app.game.services.'.$this->name),
            'service' => $this->name,
            'icon' => $this->icon,
            'route' => (bool) $this->route,
        ];
    }
}
