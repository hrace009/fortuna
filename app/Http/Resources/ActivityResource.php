<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ActivityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $userAgent = json_decode($this->getExtraProperty('user_agent'), true);

        return [
            'id'         => $this->id,
            'name'       => $this->description,
            'actor_ip'   => $this->getExtraProperty('ip_address'),
            'user_agent'   => [
                'browser'   =>  $userAgent['browser'],
                'os_name'   =>  $userAgent['os_name'],
            ],
            'log_type'   => $this->getExtraProperty('log_type'),
            'created_at' => (string) $this->created_at,
        ];
    }
}
