<?php

namespace App\Jobs;

use App\Services\CureselectApis\SMSApiService;

class SendSmsJob extends Job
{    
    protected $mobile;
    protected $isd_code;
    protected $message;
    protected $iso_code;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $mobile, string $isd_code, string $message, string $iso_code)
    {
        $this->mobile = $mobile;
        $this->isd_code = $isd_code;
        $this->message = $message;
        $this->iso_code = $mobile;
        $this->onQueue('sendSms');
    }

    public function handle()
    {
        // logInfo("SendSMSJob Started", true);

        $response = (new SMSApiService)->send($this->mobile, $this->isd_code, $this->message, $this->iso_code);

        // \Log::info($response);

        // logInfo("SendSMSJob End", true);
    }
}
