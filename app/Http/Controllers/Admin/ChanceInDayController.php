<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ChancesInDaysExport;
use App\Http\Controllers\Controller;
use App\Models\RewardInDay;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ChanceInDayController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {
        $date = $request->date ?? Carbon::yesterday()->format('Y-m-d');

        $items = RewardInDay::where('date', $date)->get();

        return view('admin.chances-in-days.index', compact('items', 'date'));
    }

    public function export(Request $request)
    {
        $data = $request->all();

        return Excel::download(new ChancesInDaysExport($data), 'chances-in-day.xls');
    }
}
