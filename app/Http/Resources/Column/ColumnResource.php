<?php

namespace App\Http\Resources\Column;

use App\Http\Resources\Card\CardResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ColumnResource extends JsonResource
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
            'name' => $this->name,
            'board_id' => $this->board_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'cards' => CardResource::collection($this->whenLoaded('cards')),
        ];
    }
}
