<?php

namespace App\Http\Resources\Card;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CardResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'column_id' => $this->column_id,
            'board_id' => $this->column->board->id,
            'check_lists' => CardCheckListResource::collection($this->whenLoaded('checkLists')),
            'assignees' => CardAssignedUserResource::collection($this->whenLoaded('assignedUsers')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
