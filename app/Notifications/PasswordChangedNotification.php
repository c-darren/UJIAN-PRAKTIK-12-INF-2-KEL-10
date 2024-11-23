<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PasswordChangedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $changeTime;
    protected $userData;
    
    /**
     * Create a new notification instance.
     */
    public function __construct($userData)
    {
        $this->changeTime = Carbon::now();
        $this->userData = $userData;
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
    public function toMail($notifiable): MailMessage
    {
        $formattedTime = $this->changeTime->toDayDateTimeString();

        return (new MailMessage)
            ->subject('Your Password Has Been Changed')
            ->greeting('Hello, ID: ' . $this->userData['id'] . ' - Username: ' . $this->userData['username'])
            ->line('We wanted to let you know that your account password was successfully changed on ' . $formattedTime . '.')
            ->line('If you did not make this change, please contact our support team immediately.')
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray($notifiable): array
    {
        return [
            'change_time' => $this->changeTime,
        ];
    }
}
