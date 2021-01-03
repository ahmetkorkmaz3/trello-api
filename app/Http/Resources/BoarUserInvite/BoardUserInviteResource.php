<?php

namespace App\Http\Resources\BoarUserInvite;

use App\Http\Resources\Board\BoardResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BoardUserInviteResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'board' => BoardResource::make($this->whenLoaded('board')),
        ];
    }
}
