<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyNewEmailNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $username;
    protected $email;
    protected $verifyUrl;
    public function __construct($username, $email)
    {
        $this->username = $username;
        $this->email = $email;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);
    
        return (new MailMessage)
                    ->subject('Your Email Has Been Changed')
                    ->greeting('Hello, ' . $this->username)
                    ->line('Your account email has been changed.')
                    ->line('Username: ' . $this->username)
                    ->line('Email: ' . $this->email . ' (Unverified Email)')
                    ->line('Please verify your new email address by clicking the button below.')
                    ->action('Verify Email', $verificationUrl)
                    ->line('This link will expire in 5 minutes.')
                    ->line('If you have any questions, feel free to contact our support team.');
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

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'username' => $this->username,
            'email' => $this->email,
        ];
    }
}
