<?php

namespace App\Notifications;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Queueable;

class OtpNotification extends Notification implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;

    public function __construct($user)
    {
        // The $notifiable is already a User instance so not really necessary to pass it here
        $this->user = $user;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Notifications has launched!')
            ->markdown('mail.default', [
                'user' => $this->user
            ])
            ->greeting('Welcome '.$this->user['name'])
            ->line('Your OTP :'.$this->user['otp'])
            ->line('Message :'.$this->user['message'])
            ->line('Mail: '.$this->user['email']);
    }

}