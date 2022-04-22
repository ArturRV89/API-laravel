<?php

namespace App\Http\Resources;

use App\Models\DeskList;
use Illuminate\Http\Resources\Json\JsonResource;

class DeskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
           'name' => $this->name,
            'created_at' => $this->created_at,
            'list' => DeskListResource::collection($this->lists)
        ];
    }
}
