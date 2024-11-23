<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminSendEmailVerificationNotificationCustom extends Notification implements ShouldQueue
{
    use Queueable;

    public $username;
    public $email;

    public $creatorInfo;

    /**
     * Create a new notification instance.
     */
    public function __construct($username, $email, $creatorInfo)
    {
        $this->username = $username;
        $this->email = $email;
        $this->creatorInfo = $creatorInfo;
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
        $creatorInfo = $this->creatorInfo; // UserID dan username pembuat

        return (new MailMessage)
                    ->subject('Welcome to Our Application!')
                    ->greeting('Welcome to Our Application!')
                    ->line('Your account has been successfully created by an administrator.')
                    ->line('Here are your account details:')
                    ->line('Username: ' . $this->username)
                    ->line('Email: ' . $this->email . ' (Unverified Email)')
                    ->line('Created by: ' . $creatorInfo)
                    ->line('Please use the password provided to you directly to log in.')
                    ->line('Once logged in, follow the instructions on the website to verify your email.')
                    ->action('Log In', url('/login'))
                    ->line('If you have any questions, feel free to contact our support team.');
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