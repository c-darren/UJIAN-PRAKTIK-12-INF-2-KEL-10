<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;

class CustomVerifyEmailNotification extends Notification implements ShouldQueue
{
    use Queueable;
    public $username;

    /**
     * Terima username melalui konstruktor.
     */
    public function __construct($username)
    {
        $this->username = $username;
    }

    /**
     * Tentukan channel pengiriman (email).
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Kustomisasi isi email.
     */
    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Verify Your Email Address')
            ->greeting("Hello, {$this->username}!")
            ->line('Please log in first, then click the button below to verify your email address.')
            ->action('Verify Email', $verificationUrl)
            ->line('This link will expire in 5 minutes.')
            ->line('If you did not create an account, no further action is required.');
    }

    /**
     * Generate URL verifikasi khusus.
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