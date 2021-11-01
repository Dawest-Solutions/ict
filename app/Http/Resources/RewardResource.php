<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RewardResource extends JsonResource
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
            'service' => $this->service,
            'name_chance' => $this->name_chance,
            'name' => $this->name,
            'image_path' => asset('rewards/' . $this->image_path),
            'location' => auth()->user()->type !== 'stationary' ? $this->location : null,
            'value' => $this->value,
            'description' => $this->description,
        ];
    }
}
