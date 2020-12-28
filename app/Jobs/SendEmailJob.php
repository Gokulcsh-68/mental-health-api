<?php

namespace App\Jobs;
use App\Entities\User;
use App\Notifications\OtpNotification;

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
        $this->onQueue('default');
    }

    public function handle()
    {
        /*$user = User::first();
        $data = $user->notify(new OtpNotification($this->payload));*/

        logInfo("SendEmailJob Started", true);
        \Log::error("otp : " . $this->payload['otp']);
        logInfo("SendEmailJob End", true);
    }
}
