<?php

namespace App\Jobs;

use App\Jobs\SendSmsJob;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;

class CommunicationJob
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $_user;
    protected $_payload;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, array $payload)
    {
        $this->_user = $user;
        $this->_payload = $payload;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $communication_channel = $this->_user->communication_channel;
        dd($communication_channel);
        $iso_code = $user->timezone->country_code;

        // EMAIL COMMUNICATION
        if(
            (isset($communication_channel->email) && $communication_channel->email) 
            || sizeof($communication_channel) < 1
        ) {
            $email_data = $payload['email'];
            $to = $email_data['to'];
            $subject = $email_data['subject'];
            $message = $email_data['message'];
            dispatch(new SendEmailJob($to, $subject, $message, $iso_code));

        }

        // SMS Communication
        if(isset($communication_channel->sms) && $communication_channel->sms) {
            $sms_data = $payload['sms'];
            $mobile = $sms_data['mobile'];
            $isd_code = $sms_data['isd_code'];
            $message = $sms_data['message'];

            dispatch(new SendSmsJob($mobile, $isd_code, $message, $iso_code));
        }
    }
}
