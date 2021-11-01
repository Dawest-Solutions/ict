<?php

namespace App\Http\Controllers\API;

use App\Exceptions\AwardReceived;
use App\Exceptions\ChanceLimitExceeded;
use App\Exceptions\UnavailableLotteryDay;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChanceStoreRequest;
use App\Http\Resources\ChanceCollection;
use App\Http\Resources\ChanceResource;
use App\Http\Resources\ChanceTemplateViewResurce;
use App\Http\Resources\WinnerResource;
use App\Models\Chance;
use App\Models\Employee;
use App\Models\RewardInDay;
use App\Models\Winner;
use App\Services\ChanceServices;
use App\Services\LotteryDayServices;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ChanceController extends Controller
{
    /**
     * @var ChanceServices
     */
    protected $chanceServices;

    public function __construct(ChanceServices $chanceServices)
    {
        $this->chanceServices = $chanceServices;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return new ChanceTemplateViewResurce(new LotteryDayServices());
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function winners()
    {
        $dates = Winner::groupBy('date_draw')->pluck('date_draw');
        $winners_array = [];

        foreach($dates as $date_draw) {
            $winners = Winner::where('date_draw', '=', $date_draw)->get();
            $winners_array[$date_draw] = WinnerResource::collection($winners);
        }
        
        return $winners_array;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ChanceStoreRequest $request
     * @return JsonResponse
     */
    public function store(ChanceStoreRequest $request)
    {
        $rewardInDay = RewardInDay::findOrFail($request->input('reward_in_day_id'));

        $employee = Employee::find(auth()->user()->id);

        try {
            $this->chanceServices->useChance($employee, $rewardInDay);

            $response = response()->json([
                'success' => [
                    'message' => 'Pomyślnie dodano szanse.',
                ]
            ], 200);
        } catch (ChanceLimitExceeded $ex) {
            $response = $this->error(['chance' => [$ex->getMessage()]]);
        } catch (UnavailableLotteryDay $ex) {
            $response = $this->error(['chance' => [$ex->getMessage()]]);
        } catch (AwardReceived $ex) {
            $response = $this->error(['chance' => [$ex->getMessage()]]);
        } catch (Exception $ex) {
            abort(500);
        }

        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show()
    {
        $employee = Employee::find(auth()->user()->id);

        return new ChanceCollection($employee->chances()->today()->get());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param RewardInDay $rewardInDay
     * @return JsonResponse
     */
    public function destroy(RewardInDay $rewardInDay)
    {
        $chance = auth()->user()->chances()->where('reward_in_day_id', '=', $rewardInDay->id)->first();
        if (!$chance) {
            return $this->error(['chance' => ['Brak losów do usunięcia.']]);
        }

        if ($chance->employee_id != Auth::user()->id) {
            return $this->error(['chance' => ['Błąd. Nie możesz usunąć losu.']]);
        }

        $chance->delete();

        return response()->json([
            'success' => [
                'message' => 'Usunięto szanse.',
                'code' => 200,
            ]
        ], 200);
    }

    protected function error($data, $code = 422)
    {
        return response()->json([
            'errors' => $data,
            'message' => "The given data was invalid.",
        ], $code);
    }
}
