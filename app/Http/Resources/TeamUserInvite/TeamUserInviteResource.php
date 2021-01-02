<?php

namespace App\Http\Resources\TeamUserInvite;

use App\Http\Resources\Team\TeamResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamUserInviteResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'team' => TeamResource::make($this->whenLoaded('team')),
        ];
    }
}
