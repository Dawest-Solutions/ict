<?php

namespace App\Http\Resources;

use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class RewardInDayCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $user = Employee::find(auth()->user()->id);

        return [
            'chances' => new ChanceCollection($user->chances()->today()->get()),
            'data' => $this->collection,
            'date' => [
                'till_chances_left' => now()->endOfDay()->diffInMilliseconds(now()),
                'till_final_left' => Carbon::parse(config('app.lottery.final_draw_at'))->diffInMilliseconds(now()),
            ]
        ];
    }
}
