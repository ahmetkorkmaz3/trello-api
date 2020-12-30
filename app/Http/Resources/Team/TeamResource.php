<?php

namespace App\Http\Resources\Team;

use App\Http\Resources\Board\BoardResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class TeamResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'website' => $this->website,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'boards' => BoardResource::collection($this->whenLoaded('boards')),
            'users' => TeamUserResource::collection($this->whenLoaded('users')),
        ];
    }
}
