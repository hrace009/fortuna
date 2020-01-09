<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
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
            'id' => $this->track_id,
            'ip_address' => $this->ip_address,
            'category' => $this->category->name,
            'status' => $this->status,
            'subject' => $this->subject,
            'message' => $this->message,
            'replies' => TicketReplyResource::collection($this->whenLoaded('replies')),
            'last_reply' => new TicketReplyResource($this->whenLoaded('lastReply')),
            'closed' => $this->isClosed(),
            'created_at' => (string) $this->created_at->toIso8601String(),
            'updated_at' => (string) $this->updated_at->toIso8601String(),
            'attachments' => TicketAttachmentsResource::collection($this->whenLoaded('media')),
            'owner' => new TicketOwnerResource($this->whenLoaded('owner')),
        ];
    }
}
