<?php

namespace App\Http\Resources\Invite;

use Illuminate\Http\Resources\Json\JsonResource;

class InviteResource extends JsonResource
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
            'email' => $this->email,
            'token' => $this->token,
            'shared_url' => $this->shared_url,
            'type' => $this->type,
            'type_id' => $this->type_id,
            'status' => $this->status,
            'expired_at' => $this->expired_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
