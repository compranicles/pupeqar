<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ExtensionInviteNotification extends Notification
{
    use Queueable;

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
        // return ['mail', 'database'];
        return [ 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        if($this->notificationData['type'] == 'ext-invite')
            return (new MailMessage)
                ->subject('PUP eQAR | Extension Invitation Notification')
                ->greeting('Hello '.$this->notificationData['receiver'].'!')
                ->line('You are added by '.$this->notificationData['sender'].' as a part of Extension accomplishment.')
                ->action('Open Extension Tab', route('extension-service.index'));
        elseif($this->notificationData['type'] == 'ext-confirm')
            return (new MailMessage)
                ->subject('PUP eQAR | Extension Invitation Confirmation Notification')
                ->greeting('Hello '.$this->notificationData['receiver'].'!')
                ->line($this->notificationData['sender'].' confirmed a part of the Extension accomplishment.')
                ->action('Open Extension', $this->notificationData['url']);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        if($this->notificationData['type'] == 'ext-invite')
            return [
                'receiver' => $this->notificationData['receiver'],
                'title' => $this->notificationData['title'],
                'sender' =>  $this->notificationData['sender'],
                'url_accept' => $this->notificationData['url_accept'],
                'url_deny' =>  $this->notificationData['url_deny'],
                'date' => $this->notificationData['date'],
                'type' => $this->notificationData['type']
            ];
        elseif($this->notificationData['type'] == 'ext-confirm')
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
