<?php

namespace App\Exports;

use App\Models\Chance;
use Illuminate\Support\Collection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ChancesInDaysExport implements FromView, WithHeadings
{
    protected $id;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
    }
    /**
    * @return Collection
    */
    public function collection(): Collection
    {
        return Chance::where('reward_in_day_id', $this->id)->get();
    }

    /**
     * @return View
     */
    public function view(): View
    {
        return view('admin.exports.chances-in-days', [
            'items' => $this->collection()
        ]);
    }

    public function headings(): array
    {
        return [
            'id',
            'employee_id',
            'reward_in_day_id',
            'created_at',
            'updated_at',
        ];
    }
}
