<?php

namespace App\Http\Resources\Team;

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
            'users' => TeamUserResource::collection($this->whenLoaded('users')),
        ];
    }
}
