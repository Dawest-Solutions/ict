<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\VoucherUserStoreRequest;
use App\Models\UserVoucher;
use Illuminate\Support\Carbon;


class VoucherController extends Controller
{
    public function store(VoucherUserStoreRequest $request)
    {
        $voucherChooseEndDate = Carbon::parse(env('LOTTERY_VOUCHER_CHOOSE_END_AT'));

        if($voucherChooseEndDate->lt(now())) {
            return $this->error(['Czas na wybór vouchera minął.']);
        }

        // check if unique
        if(UserVoucher::where('user_id', '=', $request->user()->id)->exists()) {
            return $this->error(['Twój wybór vouchera został zapisany wcześniej, nie można dokonać zmiany.']);
        }

        $item = new UserVoucher();

        $item->fill([
           'user_id' => auth()->user()->id,
           'voucher_id' => $request->input('voucher_id')
        ]);

        $item->save();
        
        return response()->json(['Wybór został zapisany']);
    }

    protected function error($data, $code = 422)
    {
        return response()->json([
            'errors' => $data,
            'message' => "The given data was invalid.",
        ], $code);
    }

}
