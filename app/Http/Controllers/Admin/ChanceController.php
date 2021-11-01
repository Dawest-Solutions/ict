<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChanceCreateRequest;
use App\Http\Requests\ChanceUpdateRequest;
use App\Models\Chance;
use App\Models\Reward;
use App\Models\RewardInDay;
use App\Models\Employee;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ChanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        ini_set('memory_limit', -1);
        $items = Chance::with(['employee', 'rewardInDay', 'rewardInDay.reward'])->get();

        return view('admin.chances.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        $rewards = Reward::all();
        $employees = Employee::all();

        return view('admin.chances.new', compact('rewards', 'employees'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Application|Factory|View
     */
    public function store(ChanceCreateRequest $request)
    {
        $data = $request->validated();
        $rewardInDay = RewardInDay::where('date', $data['date'])->first();

        if (!$rewardInDay) {
            return $this->index()->with('errors', collect(['W tym dniu nie została jeszcze przypisana ani jedna nagroda']));
        }

        $rewardInDay = RewardInDay::where('date', $data['date'])
            ->where('reward_id', $data['reward_id'])
            ->first();

        if (!$rewardInDay) {
            return $this->index()->with('errors', collect(['Ta nagroda nie jest dostepna tego dnia']));
        }

        $newChance = new Chance([
                'reward_in_day_id' => $rewardInDay->id,
                'employee_id' => $data['employee_id'],
        ]);
        $newChance->save();

        return $this->index()->with('success', collect(['New chance has been created.']));

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Application|Factory|View
     */
    public function show($id)
    {
        $chance = Chance::with(['employee', 'rewardInDay', 'rewardInDay.reward'])->findOrFail($id);

        return view('admin.chances.details', compact('chance'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Application|Factory|View
     */
    public function edit($id)
    {
        $chance = Chance::with(['employee', 'rewardInDay', 'rewardInDay.reward'])->find($id);
        $rewards = Reward::all();
        $employees = Employee::all();

        return view('admin.chances.edit', compact('chance', 'rewards', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ChanceUpdateRequest $request
     * @param int $id
     * @return Application|Factory|View
     */
    public function update(ChanceUpdateRequest $request, $id)
    {
        $data = $request->validated();
        $rewardInDay = RewardInDay::where('date', $data['date'])->first();

        if (!$rewardInDay) {
            return $this->index()->with('errors', collect(['W tym dniu nie została jeszcze przypisana ani jedna nagroda']));
        }

        $rewardInDay = RewardInDay::where('date', $data['date'])
            ->where('reward_id', $data['reward_id'])
            ->first();

        if (!$rewardInDay) {
            return $this->index()->with('errors', collect(['W tym dniu nie została jeszcze przypisana ani jedna nagroda']));
        }

        $chance = Chance::find($id);
        $chance->employee_id = $data['employee_id'];
        $chance->reward_in_day_id = $rewardInDay->id;
        $chance->save();

        return $this->index()->with('success', collect(['Chance in day has been updated.']));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Application|Factory|View
     */
    public function destroy(int $id)
    {
        try {
            Chance::findOrFail($id)->delete();

            return $this->index()->with('success', collect(['Chance has been deleted.']));
        } catch (\Exception $e) {
            return $this->index()->with('errors', collect(['There was an error while deleting chance: ' . $e->getMessage()]));
        }
    }
}
