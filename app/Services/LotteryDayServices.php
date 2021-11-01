<?php

namespace App\Services;

use App\Exceptions\UnavailableLotteryDay;
use App\Models\Employee;
use Illuminate\Support\Carbon;

class LotteryDayServices
{
    /**
     * @param Carbon $now
     * @return bool
     * @throws UnavailableLotteryDay
     */
    public function availableLotteryDays(Carbon $now): bool
    {
        $status = $this->status($now);

        // Outside the lottery time range.
        if (in_array($status, [4,5] )) {
            throw new UnavailableLotteryDay('Loteria jest niedostępna.');
        }

        // Only on working days.
        if ($status === 3) {
            throw new UnavailableLotteryDay('Loteria w weekendy jest niedostępna.');
        }

        // Between an hour 10:00 and 23:59.
        if ($status === 2) {
            throw new UnavailableLotteryDay("Loteria jest dostępna w godzinach od 10:00 do 23:59.");
        }

        return true;
    }

    /**
     * Return number view.
     *
     *  lottery available           : 1
     *  is out of time              : 2
     *  is weekend                  : 3
     *  is before the start date    : 4
     *  is after the end date       : 5
     *  user won a prize            : 6
     *
     * @param Carbon $date
     * @param Carbon $startLotteryDate
     * @param Carbon $endLotteryDate
     * @return int
     */
    public function status(Carbon $now): int
    {
        $startLotteryDate = Carbon::parse(config('app.lottery.begin_at'));
        $endLotteryDate = Carbon::parse(config('app.lottery.end_at'));
        $finalDrawLotteryDate = Carbon::parse(env('LOTTERY_FINAL_DRAW_AT'));
        $voucherChooseEndDate = Carbon::parse(env('LOTTERY_VOUCHER_CHOOSE_END_AT'));

        $startTime = $now->copy()->setHour(10)->setMinute(0)->setSecond(0);
        $endTime = $now->copy()->setHour(23)->setMinute(59)->setSecond(59);

        if ($now->gt($voucherChooseEndDate)) {
            return 8;
        }
        if ($now->lt($finalDrawLotteryDate)) {
            return 3;
        }

        if ($now->lt($startLotteryDate)) {
            return 4;
        }

        if ($now->gt($endLotteryDate)) {
            return 5;
        }

        if ($now->between($startLotteryDate, $endLotteryDate)) {
            if ($now->isWeekend()) {
                return 3;
            }

            if (!$now->isBetween($startTime, $endTime)) {
                return 2;
            }
        }

        return 1;
    }


}
