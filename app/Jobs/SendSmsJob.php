<?php

namespace App\Jobs;

use App\Utils\PosService;

class SendSmsJob extends Job
{    
    protected $payload;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($payload)
    {
        $this->payload = $payload;
    }

    public function handle()
    {
        logInfo("SendSmsJob Started", true);
        app(PosService::class)->setData($this->payload)
            ->sendSms();
      
        logInfo("SendSmsJob End", true);
    }
}
