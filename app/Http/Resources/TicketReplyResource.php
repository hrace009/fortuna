<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TicketReplyResource extends JsonResource
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
            'message' => $this->message,
            'created_at' => (string) $this->created_at->format('d/m/Y H:i'),
            'attachments' => TicketAttachmentsResource::collection($this->whenLoaded('media')),
            'owner' => new TicketOwnerResource($this->whenLoaded('owner')),
            'staff' => (bool) $this->staff,
        ];
    }
}
