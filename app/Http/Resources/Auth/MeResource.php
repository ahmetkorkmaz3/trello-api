<?php

namespace App\Http\Resources\Auth;

use App\Http\Resources\Board\BoardResource;
use App\Http\Resources\Team\TeamResource;
use Illuminate\Http\Resources\Json\JsonResource;

class MeResource extends JsonResource
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
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->fullname,
            'username' => $this->username,
            'email' => $this->email,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'boards' => BoardResource::collection($this->whenLoaded('boards')),
            'teams' => TeamResource::collection($this->whenLoaded('teams')),
        ];
    }
}
