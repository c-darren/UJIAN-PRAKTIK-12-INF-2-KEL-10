<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyNewEmailNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $username;
    protected $verifyUrl;

    public function __construct($username, $verifyUrl)
    {
        $this->username = $username;
        $this->verifyUrl = $verifyUrl;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Verify Your New Email Address')
            ->greeting("Hello, {$this->username}")
            ->line('Please verify your new email address by clicking the button below.')
            ->action('Verify Email', $this->verifyUrl)
            ->line('If you did not request this change, please contact our support team immediately.');
    }
}
