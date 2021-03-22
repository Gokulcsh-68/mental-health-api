<?php

namespace App\Jobs;
use App\Entities\User;
use App\Notifications\OtpNotification;
use App\Services\CureselectApis\EmailApiService;

class SendEmailJob extends Job
{   
    protected $to;
    protected $subject;
    protected $message;
    protected $iso_code;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $to, string $subject, string $message, string $iso_code)
    {
        $this->to = $to;
        $this->subject = $subject;
        $this->message = $message;
        $this->iso_code = $iso_code;
        $this->onQueue('sendEmail');
    }

    public function handle()
    {
        // logInfo("SendEmailJob Started", true);

        $response = (new EmailApiService)->send($this->to, $this->subject, $this->message, $this->iso_code);

        // \Log::info($response);

        // logInfo("SendEmailJob End", true);
    }
}
