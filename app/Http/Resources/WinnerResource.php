<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WinnerResource extends JsonResource
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
            'name' => $this->employee->first_name . ' ' . mb_substr($this->employee->last_name, 0, 1) . '.',
            'reward' => $this->rewardInDay()->first()->reward()->first()->name,
            'value' => $this->rewardInDay()->first()->value,
        ];
    }
}
