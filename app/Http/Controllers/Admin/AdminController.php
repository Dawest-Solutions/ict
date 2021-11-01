<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminCreateRequest;
use App\Http\Requests\AdminUpdateRequest;
use App\Models\Admin;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class AdminController extends Controller
{

    /**
     * @return Application|Factory|View
     */
    public function admins()
    {
        $admins = Admin::all();

        return view('admin.admins.index', compact('admins'));
    }

    /**
     * @param $id
     * @return Application|Factory|View
     */
    public function adminInfo($id)
    {
        $info = Admin::find($id);

        return view('admin.admins.info', compact('info'));
    }

    /**
     * @return Application|Factory|View
     */
    public function adminNew()
    {
        return view('admin.admins.new');
    }

    /**
     * @param AdminCreateRequest $request
     * @return Application|Factory|View
     */
    public function adminCreate(AdminCreateRequest $request)
    {

        $data = $request->filter();
        $data["password"] = bcrypt($data['password']);

        if (Admin::create($data)) {
            return $this->admins()->with('success', collect(['A new admin user has been created.']));
        }

        return $this->admins()->with('errors', collect(['There were errors while creating the admin user.']));
    }

    /**
     * @param AdminUpdateRequest $request
     * @param Int $id
     * @return Application|Factory|View
     */
    public function adminUpdate(AdminUpdateRequest $request, Int $id)
    {

        $data = $request->filter();

        if (array_key_exists('password', $data)) {
            $data['password'] = bcrypt($data['password']);
        }

        if (Admin::find($id)->update($data)) {
            return $this->adminInfo($id)->with('success', collect(['Admin user information has been updated.']));
        }

        return $this->adminInfo($id)->with('errors', collect(['Errors occurred while updating the admin user.']));
    }
}
