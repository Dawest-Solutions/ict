<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RewardInDayCreateRequest;
use App\Http\Requests\RewardInDayUpdateRequest;
use App\Models\Reward;
use App\Models\RewardInDay;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class RewardInDayController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $items = RewardInDay::with('reward')->get();

        return view('admin.rewards-in-days.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        $rewards = Reward::all();

        return view('admin.rewards-in-days.new', compact('rewards'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  RewardInDayCreateRequest  $request
     * @return Application|Factory|View
     */
    public function store(RewardInDayCreateRequest $request)
    {
        try {
            $data = $request->validated();
            $reward_value = $request->input('value');
            $inserted = 0;

            foreach ($data['rewards'] as $reward_id => $value) {
                for ($i=0;$i<$data['amounts'][$reward_id];$i++) {
                    $newRewardInDay = new RewardInDay([
                        'date' => date_create_from_format('Y-m-d',  $data['date']),
                        'reward_id' => $reward_id,
                        'value' => $reward_value
                    ]);
                    $newRewardInDay->save();
                    $inserted++;
                }
            }

            return $this->index()->with('success', collect([$inserted . ' new rewards in day ' . $data['date'] . ' has been created.']));

        } catch (\Exception $e) {
            return $this->index()->with('errors', collect(['There was an error while creating the new reward in day: ' . $e->getMessage()]));
        }


    }

    /**
     * Display the specified resource.
     *
     * @param int $rewardInDayId
     * @return Application|Factory|View
     */
    public function show(int $rewardInDayId)
    {
        $rewardInDay = RewardInDay::with('reward')->where('id', $rewardInDayId)->first();

        return view('admin.rewards-in-days.details', compact('rewardInDay'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $rewardInDayId
     * @return Application|Factory|View
     */
    public function edit(int $rewardInDayId)
    {
        $rewardInDay = RewardInDay::findOrFail($rewardInDayId);
        $rewards = Reward::all();

        return view('admin.rewards-in-days.edit', compact('rewardInDay', 'rewards'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param RewardInDayUpdateRequest $request
     * @param int $rewardInDayId
     * @return Application|Factory|View
     */
    public function update(RewardInDayUpdateRequest $request, int $rewardInDayId)
    {
        $data = $request->validated();

        try {
            RewardInDay::findOrFail($rewardInDayId)->update($data);

            return $this->index()->with('success', collect(['Reward in day has been updated.']));
        } catch (\Exception $e) {
            return $this->index()->with('errors', collect(['There was an error while updating the reward in day: ' . $e->getMessage()]));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $rewardInDayId
     * @return Application|Factory|View
     */
    public function destroy(int $rewardInDayId)
    {
        try {
            RewardInDay::findOrFail($rewardInDayId)->delete();

            return $this->index()->with('success', collect(['Reward in day has been deleted.']));
        } catch (\Exception $e) {
            return $this->index()->with('errors', collect(['There was an error while deleting the new reward in day: ' . $e->getMessage()]));
        }
    }
}
