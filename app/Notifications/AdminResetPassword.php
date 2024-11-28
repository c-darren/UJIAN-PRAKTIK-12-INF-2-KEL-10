<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminResetPassword extends Notification implements ShouldQueue
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
                    ->subject('Your Password Has Been Reset')
                    ->greeting('Hello, ' . $this->username)
                    ->line('Your account password has been successfully reset by an administrator.')
                    ->line('Here are your account details:')
                    ->line('Username: ' . $this->username)
                    ->line('Reset by: ' . $creatorInfo)
                    ->line('New Password: password')
                    ->line('Please use the provided password directly to log in.')
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
