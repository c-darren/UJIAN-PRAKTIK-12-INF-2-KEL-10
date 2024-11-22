<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class CancelEmailChangeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $username;
    protected $newEmail;
    protected $cancelUrl;

    public function __construct($username, $newEmail, $cancelUrl)
    {
        $this->username = $username;
        $this->newEmail = $newEmail;
        $this->cancelUrl = $cancelUrl;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Cancel Email Change Request')
            ->greeting("Hello, {$this->username}")
            ->line("We received a request to change your email address to {$this->newEmail}.")
            ->line("If you did not request this change, please click the button below to cancel the request.")
            ->action('Cancel Email Change', $this->cancelUrl)
            ->line('If you did make this request, you can ignore this email.');
    }
}
