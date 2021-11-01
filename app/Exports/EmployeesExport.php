<?php

namespace App\Exports;

use App\Models\Employee;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EmployeesExport implements FromCollection, WithHeadings
{
    /**
    * @return Collection
    */
    public function collection()
    {
        return Employee::all();
    }

    public function headings(): array
    {
        return [
            'id',
            'first_name',
            'last_name',
            'phone',
            'registration_code',
            'type',
            'years_of_employment',
            'end_of_work',
            'password',
            'agreement_1',
            'agreement_1_text',
            'agreement_2',
            'agreement_2_text',
            'active',
            'registered_at',
        ];
    }
}
