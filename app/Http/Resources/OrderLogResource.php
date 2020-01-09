<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderLogResource extends JsonResource
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
            'event' => $this->description,
            'type' => $this->getExtraProperty('status'),
            /*$this->mergeWhen(auth()->user()->isAdmin(), [
                'ip' => $this->getExtraProperty('ip_address'),
                'browser' => $userAgent['browser'],
                'os_name' => $userAgent['os_name']
            ]),*/
            'created_at' => (string) $this->created_at,
        ];
    }
}
