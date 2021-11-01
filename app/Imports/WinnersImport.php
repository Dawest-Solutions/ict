<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\RewardInDay;
use App\Models\Winner;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithStartRow;

class WinnersImport implements ToCollection, WithStartRow
{

    /**
     * Start from second line
     *
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }

    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            if (!$row[0]) {
                return;
            }

            $reward_in_day = RewardInDay::findOrFail($row[0]);
            $employee = Employee::findOrFail($row[1]);
            $date_draw = Carbon::createFromFormat('Y-m-d', $reward_in_day->date)->addDay();

            if($date_draw->isWeekend()) {
                $date_draw->addDays(2);
            }

            $winner = Winner::where('employee_id', '=', $employee->id)
                ->where('reward_in_day_id', '=', $reward_in_day->id)
                ->first();

            if($winner) {
                $winner->date_draw = $date_draw->format('Y-m-d');
            } else {
                $winner = new Winner([
                    'employee_id' => $employee->id,
                    'reward_in_day_id' => $reward_in_day->id,
                    'date_draw' => $date_draw->format('Y-m-d'),
                ]);
                
                $winner->save();
            }
        }
    }

    public function batchSize(): int
    {
        return 1000;
    }
}
