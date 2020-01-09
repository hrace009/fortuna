<?php

namespace App\Http\Resources;

use Hashids;
use Illuminate\Http\Resources\Json\JsonResource;

class UserGameResource extends JsonResource
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
            'id'    => Hashids::encode($this->ID),
            'name'           => $this->name,
        ];
    }
}
