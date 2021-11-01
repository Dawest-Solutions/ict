<?php

namespace App\Services;

use App\Exceptions\AwardReceived;
use App\Exceptions\ChanceLimitExceeded;
use App\Exceptions\UnavailableLotteryDay;
use App\Exceptions\Unemployed;
use App\Models\RewardInDay;
use App\Models\Employee;
use Illuminate\Support\Carbon;

class ChanceServices
{
    
    /**
     * @var LotteryDayServices
     */
    protected $lotteryDayServices;


    /**
     * @param LotteryDayServices $lotteryDayServices
     */
    public function __construct(LotteryDayServices $lotteryDayServices)
    {
        $this->lotteryDayServices = $lotteryDayServices;
    }


    /**
     * Use 1 of the 3 chances available today.
     *
     * @param Employee $user
     * @param RewardInDay $rewardInDay
     * @throws ChanceLimitExceeded|UnavailableLotteryDay|AwardReceived
     */
    public function useChance(Employee $user, RewardInDay $rewardInDay)
    {
        $now = now();

        // Whether Lottery Days are available and Check if you have used 3 chances today.
        if ($this->lotteryDayServices->availableLotteryDays($now)
            and (!$this->checkChances($user, $now))) {
            throw new ChanceLimitExceeded('Przekroczono dostępną ilość szans. Możesz wykorzystać maksymalnie 3 szanse.');
        }

        $user->chances()->create([
            'reward_in_day_id' => $rewardInDay->id,
            'updated_at' => $now,
            'created_at' => $now,
        ]);
    }


    /**
     * Check for unused chances. Maximum of 3 chances have been established.
     *
     * @param Employee $user
     * @param Carbon $date
     * @param int $maxNumberChances
     * @return bool
     * @throws AwardReceived|Unemployed
     */
    public function checkChances(Employee $user, Carbon $date, int $maxNumberChances = 3): bool
    {
        if ($user->isWinner()) {
            throw new AwardReceived('Nie posiadasz więcej szans. Otrzymałeś już nagrodę!');
        };

        if ($user->end_of_work && Carbon::parse($user->end_of_work) < now()) {
            throw new Unemployed('Blokada losów z uwagi na stosunek pracy!');
        };

        return $user->chances()->today($date)->count() < $maxNumberChances;
    }

}
