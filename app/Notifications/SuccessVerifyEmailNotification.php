<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SuccessVerifyEmailNotification extends Notification implements ShouldQueue
{    use Queueable;
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
        return (new MailMessage)
            ->subject('Your Email Has Been Successfully Verified')
            ->greeting("Hello, {$this->username}!")
            ->line('We are happy to inform you that your email address has been successfully verified.')
            ->line('Thank you for confirming your email. You can now enjoy all the features of our platform.')
            ->line('If you have any questions, feel free to reach out to our support team.');
    }
}
