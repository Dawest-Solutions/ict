<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeRegisterRequest;
use App\Http\Requests\EmployeeUpdateRequest;
use App\Http\Traits\CodeGenerator;
use App\Http\Traits\SearchSort;
use App\Http\Traits\SendTestSms;
use App\Models\Employee;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Request;

class EmployeeDataController extends Controller
{

    /**
     * @return Application|Factory|View
     */
    public function employees(Request $request)
    {
        $credits = SearchSort::credits($request);

        $employees = Employee::where('first_name', 'like', "%" . $credits->get('search') . "%")
            ->orWhere('last_name', 'like', "%" . $credits->get('search') . "%")
            ->orWhere('phone', 'like', "%" . $credits->get('search') . "%")
            ->orderBy($credits->get('sort'), $credits->get('order'))
            ->paginate($credits->get('entries'));

        return view('admin.employees.index', compact('employees'));
    }

    /**
     * @param $id
     * @return Application|Factory|View
     */
    public function employeeInfo($id)
    {
        $info = Employee::find($id);

        return view('admin.employees.info', compact('info'));
    }

    /**
     * @return Application|Factory|View
     */
    public function employeeNew()
    {
        return view('admin.employees.new');
    }

    public function getAlphanumericCode()
    {
        return CodeGenerator::getAlphanumericCode(8);
    }

    /**
     * @param EmployeeRegisterRequest $request
     * @return RedirectResponse
     */
    public function employeeCreate(EmployeeRegisterRequest $request): RedirectResponse
    {

        if (Employee::create($request->all())) {
            return $this->employees($request)->with('success', collect(['A new employee has been created.']));
        }

        return redirect()->back()->with('errors', collect(['There were errors while creating the employee.']));
    }

    /**
     * @param EmployeeUpdateRequest $request
     * @param Int $id
     * @return Application|Factory|View
     */
    public function employeeUpdate(EmployeeUpdateRequest $request, Int $id)
    {

        $data = $request->filter();

        if (Employee::find($id)->update($data)) {
            return $this->employeeInfo($id)->with('success', collect(['User information has been updated.']));
        }

        return $this->employeeInfo($id)->with('errors', collect(['Errors occurred while updating the employee.']));
    }

    public function sendTestSms(Request $request): View
    {

        $id = $request->get('id');
        $phone = $request->get('phone');
        $message = $request->get('message');

        try {
            SendTestSms::dispatch($phone, $message);

            return $this->employeeInfo($id)->with('success', collect(['SMS was sent successfully']));
        } catch (\Exception $exception) {
            Log::warning('SMS sending error:');
            Log::warning($exception);
        }

        return $this->employeeInfo($id)->with('errors', collect(['Errors occurred when sending SMS']));
    }
}
