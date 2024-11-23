<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;

class SendEmailCreateAccount extends Notification implements ShouldQueue
{
    use Queueable;

    public $username;

    /**
     * Receive username via constructor.
     */
    public function __construct($username)
    {
        $this->username = $username;
    }

    /**
     * Specify the delivery channels.
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Customize the email content.
     */
    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Verify Your Email Address')
            ->greeting("Hello, {$this->username}!")
            ->line('Please click the button below to verify your email address.')
            ->action('Verify Email', $verificationUrl)
            ->line('This link will expire in 5 minutes.')
            ->line('If you did not create an account, no further action is required.');
    }

    /**
     * Generate a custom verification URL.
     */
    protected function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(config('auth.verification.expire', 60)),
            ['id' => $notifiable->getKey(), 'hash' => sha1($notifiable->getEmailForVerification())]
        );
    }
}
