<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResearchNotification extends Notification
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
            ->subject('PUP eQAR | Research Deadline Notification')
            ->greeting('Hello '.$this->notificationData['receiver'].'!')
            ->line('Please be informed that your research "'.
                        $this->notificationData['research_title'].'" with code "'.
                        $this->notificationData['research_code'].'" will be due on '.$this->notificationData['target_date'].'.')
            ->line('That will be '.$this->notificationData['days_remaining'].' day/s remaining.')
            ->action('View Research', $this->notificationData['url']);
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
            'research_title' => $this->notificationData['research_title'],
            'research_code' => $this->notificationData['research_code'],
            'target_date' => $this->notificationData['target_date'],
            'user_id' => $this->notificationData['user_id'],
            'days_remaining' => $this->notificationData['days_remaining'],
            'type' => $this->notificationData['type'],
            'date' => $this->notificationData['date'],
        ];
    }
}
