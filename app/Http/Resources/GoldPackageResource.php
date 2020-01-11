<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GoldPackageResource extends JsonResource
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
            'id' => $this->package_id,
            'name' => $this->package_name,
            'price' => $this->package_price,
            'golds' => $this->package_gold_amount,
        ];
    }
}
