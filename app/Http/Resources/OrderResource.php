<?php

namespace App\Http\Resources;

use Illuminate\Support\Str;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $billet = $this->getExtraProperty('billet');

        return [
            // 'history' => OrderLogResource::collection($this->whenLoaded('activities')),
            'id' => $this->order_id,
            'game_account' => $this->game_login,
            'status' => [
                'label' => $this->present()->statusLabel(),
                'color' => $this->present()->statusColor(),
            ],
            'product' => $this->product,
            'transaction_code' => $this->transaction_code,
            'value' => $this->present()->moneyFormatted(),
            'value_cash' => $this->present()->cashFormatted(),
            'gateway' => $this->formatGateway(),
            'gateway_slug' => Str::slug($this->gateway),
            'delivered_at' => $this->delivered_at,
            'created_at' => $this->created_at->toIso8601String(),
            $this->mergeWhen($this->hasBillet(), [
                'billet' => [
                    'link' => $billet['billet'],
                    'digitable_line' => $billet['digitable_line'],
                    'due_at' => $billet['due_data'],
                ],
            ]),
        ];
    }
}
