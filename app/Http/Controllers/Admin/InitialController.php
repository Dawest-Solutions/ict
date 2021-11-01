<?php

namespace App\Http\Controllers\Admin;

use App\Exports\EmployeesExport;
use App\Http\Controllers\Controller;
use App\Http\Traits\SendSms;
use App\Http\Traits\SendTestSms;
use App\Imports\EmployeesImport;
use App\Imports\WinnersImport;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class InitialController extends Controller
{
    public function index()
    {
        return view('admin.initial.index');
    }

    public function sendSMSPack()
    {
        $employees = Employee::where('active', true)
            ->get()
            ->pluck('registration_code', 'phone');

        $failed = 0;

        foreach ($employees as $phone => $code) {
            $message = "Zachecamy do rejestracji na stronie w celu utworzenia konta i wziecia udzialy w jubileuszowej loterii. Twoj indywidualny kod to:$code. Na rejestracje masz czas do 5 sierpnia! Powodzenia!";

            try {
                SendTestSms::dispatch($phone, $message);
            } catch (\Exception $exception) {
                Log::warning('SMS sending error:');
                Log::warning($exception);

                $failed++;
            }
        }

        return redirect()->back()->with($failed ? 'errors' : 'success', collect(["Wysłano " . $employees->count() . " SMS-ów. Wystąpiło $failed błędów."]));
    }

    public function importEmployeesList(Request $request)
    {

        $filename = 'EmployeesList_'.date('m-d-Y-hi').'.xlsx';
        $path = Storage::putFileAs('seeders', $request->file('employees_list'), $filename);

        try {
            Excel::import(new EmployeesImport, $path);

            return view('admin.initial.index')->with('success', collect(['Dane zostały pomyślnie zaimportowane.']));
        } catch(\Exception $e) {
            return view('admin.initial.index')->with('errors', collect([$e->getMessage()]));
        }
    }

    public function exportEmployeesList(): BinaryFileResponse
    {
        return Excel::download(new EmployeesExport(), 'employees.xlsx');
    }

    public function importWinnersList(Request $request)
    {
        $filename = 'WinnersList_'.date('Ymd-Hi').'.xlsx';
        $path = Storage::putFileAs('seeders', $request->file('winners_list'), $filename);

        try {
            Excel::import(new WinnersImport(), $path);
            
            return view('admin.initial.index')->with('success', collect(['Dane zostały pomyślnie zaimportowane.']));
        } catch(\Exception $e) {
            return view('admin.initial.index')->with('errors', collect([$e->getMessage()]));
        }
    }
}
