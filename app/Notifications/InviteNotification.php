<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InviteNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @param $notifiable
     * @return array
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * @param $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
                    ->from('ahmetkorkmaz3453@gmail.com', env('MAIL_FROM_NAME'))
                    ->greeting('Test')
                    ->line('You are invite to team')
                    ->action('Accept invite', url($notifiable->sharedUrl))
                    ->line('Thank you for using our application!');
    }
}
