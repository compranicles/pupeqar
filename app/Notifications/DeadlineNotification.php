<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DeadlineNotification extends Notification
{
    use Queueable;
    private $notificationData;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($notificationData)
    {
        $this->notificationData = $notificationData;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('PUP eQAR | Submission Deadline Notification')
            ->greeting('Hello '.$this->notificationData['receiver'].'!')
            ->line('Please be informed that the deadline of submission of eQAR is on '.$this->notificationData['deadline_date'].'.')
            ->line('You only have '.$this->notificationData['days_remaining'].' day/s remaining to finalize.')
            ->action('Finalize eQAR', $this->notificationData['url']);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'sender' => $this->notificationData['sender'],
            'receiver' => $this->notificationData['receiver'],
            'url' => $this->notificationData['url'],
            'deadline_date' => $this->notificationData['deadline_date'],
            'user_id' => $this->notificationData['user_id'],
            'days_remaining' => $this->notificationData['days_remaining'],
            'type' => $this->notificationData['type'],
            'quarter' => $this->notificationData['quarter'],
            'year' => $this->notificationData['year'],
            'date' => date('F j, Y, g:i a'),
        ];
    }
}
