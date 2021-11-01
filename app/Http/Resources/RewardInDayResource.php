<?php

namespace App\Http\Resources;

use App\Models\Chance;
use App\Models\Employee;
use Illuminate\Http\Resources\Json\JsonResource;

class RewardInDayResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $user = Employee::find(auth()->user()->id);

        return [
          'chances_used' => $this->chances()
              ->whereHas('employee', function ($query) use ($user) {
                  return $query->where('id', $user->id);
              })->count(),
          'id' => $this->id,
          'date' => $this->date,
          'value' => $this->value,
          'reward' => new RewardResource($this->whenLoaded('reward')),
        ];
    }
}
