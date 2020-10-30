<?php

namespace App\Http\Resources\Board;

use App\Http\Resources\Column\ColumnResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class BoardResource extends JsonResource
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
            'photo_id' => $this->photo_id,
            'columns' => ColumnResource::collection($this->columns),
            'users' => UserResource::collection($this->users),
        ];
    }
}
