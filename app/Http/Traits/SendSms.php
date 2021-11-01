<?php

namespace App\Http\Traits;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Smsapi\Client\Feature\Sms\Bag\SendSmsBag;
use Smsapi\Client\Curl\SmsapiHttpClient;

class SendSms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $phone, $message;

    public function __construct($phone, $message)
    {
        $this->phone = $phone;
        $this->message = $message;
    }

    public function handle()
    {
        $phone = $this->phone;
        $message = $this->message;
        $client = new SmsapiHttpClient();
        $smsapi = $client->smsapiPlService('i');

        Log::debug(sprintf('Sending message to %s; content: %s', $phone, $message));

        $sms = SendSmsBag::withMessage($phone, $message);
        $sms->from = '';
        $smsapi->smsFeature()->sendSms($sms);
    }
}
