<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordResetSuccessNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $username;

    public function __construct($username)
    {
        $this->username = $username;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Password Successfully Reset')
            ->greeting("Hello, {$this->username}!")
            ->line('Your account password has been successfully reset.')
            ->line('If you did not make this change, please contact our support team immediately.')
            ->action('Go to Login', route('login'));
    }
}
