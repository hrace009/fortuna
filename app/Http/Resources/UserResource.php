<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'id' => $this->id,
            'avatar' => $this->getAvatar(),
            'first_name' => split_name($this->name)[0],
            'name' => $this->name,
            'email' => $this->email,
            'gender' => $this->gender,
            'birthday' => $this->birthday,
            'document' => $this->document,
            'role' => 'admin',
            'status' => (string) $this->status,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
        ];
    }
}
