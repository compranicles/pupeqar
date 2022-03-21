<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResearchInviteNotification extends Notification
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
        if($this->notificationData['type'] == 'invite')
            return (new MailMessage)
                ->subject('PUP eQAR | Research Invitation Notification')
                ->greeting('Hello '.$this->notificationData['receiver'].'!')
                ->line('You are invited by '.$this->notificationData['sender'].' to be part of their Research titled: ')
                ->line($this->notificationData['title'])
                ->action('Open Research Tab', route('research.index'));
        elseif($this->notificationData['type'] == 'confirm')
            return (new MailMessage)
                ->subject('PUP eQAR | Research Invitation Confirmation Notification')
                ->greeting('Hello '.$this->notificationData['receiver'].'!')
                ->line($this->notificationData['sender'].' confirmed to be part of your Research titled: ')
                ->line($this->notificationData['title'])
                ->action('Open Research', $this->notificationData['url']);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {   
        if($this->notificationData['type'] == 'invite')
            return [
                'receiver' => $this->notificationData['receiver'],
                'title' => $this->notificationData['title'],
                'sender' =>  $this->notificationData['sender'],
                'url_accept' => urlencode($this->notificationData['url_accept']),
                'url_deny' =>  urlencode($this->notificationData['url_deny']),
                'date' => $this->notificationData['date'],
                'type' => $this->notificationData['type']
            ];
        elseif($this->notificationData['type'] == 'confirm')
            return [
                'receiver' => $this->notificationData['receiver'],
                'title' => $this->notificationData['title'],
                'sender' =>  $this->notificationData['sender'],
                'url' =>  urlencode($this->notificationData['url']),
                'date' => $this->notificationData['date'],
                'type' => $this->notificationData['type']
            ];
    }
}
