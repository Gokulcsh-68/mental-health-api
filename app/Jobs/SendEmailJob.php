<?php

namespace App\Jobs;

class SendEmailJob extends Job
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
        logInfo("SendEmailJob Started", true);
        \Log::error("otp : " . $this->payload['otp']);
        logInfo("SendEmailJob End", true);
    }
}
