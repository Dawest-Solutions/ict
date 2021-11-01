<?php

namespace App\Imports;

use App\Models\Employee;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithStartRow;

class EmployeesImport implements ToCollection, WithStartRow
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
        foreach ($collection as $row)
        {
            if(! $row[3]) {
                return;
            }

            $employee = Employee::updateOrCreate([
                'phone' => strlen($row[3]) == 9 ? implode('-', str_split($row[3], 3)) : $row[3],
                ],
                [
                'first_name' => $row[1],
                'last_name' => $row[2],
                'type' => $row[4] == 'terenowy' ? 'terrain' : 'stationary',
                'years_of_employment' => $row[5],
                'registration_code' => $row[6],
            ]);
        }
    }

    public function batchSize(): int
    {
        return 1000;
    }


    public function customValidationAttributes(): array
    {
        return [
            '3' => 'regex:/^\d{3}-\d{3}-\d{3}$/',
            '6' => 'size:8',
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            '3.unique' => 'Numer telefonu nie jest unikalny.',
            '8.unique' => 'Kod rejestracyjny nie jest unikalny.',
        ];
    }
}
