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
        return ['mail', 'database'];
        // return [ 'database'];
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
                ->subject('PUP eQAR | Extension Program/Project/Activity Tagging Notification')
                ->greeting('Hello '.$this->notificationData['receiver'].'!')
                ->line('You are tagged as a partner by '.$this->notificationData['sender'].' in an Extension Program/Project/Activity.')
                ->line('For confirmation:')
                ->line('1. Click the button "Go to Extension Program/Project Activity" in this message.')
                ->line('2. Click the button "Extensions to Add (Tagged by your Partner)".')
                ->line('3. From the list, add the extension where you are tagged and save.')
                ->action('Go to Extension Program/Project Activity', route('extension-service.index'))
                ->line('Thank you for using our application!');
        elseif($this->notificationData['type'] == 'ext-confirm')
            return (new MailMessage)
                ->subject('PUP eQAR | Extension Program/Project/Activity Tagging Confirmation Notification')
                ->greeting('Hello '.$this->notificationData['receiver'].'!')
                ->line($this->notificationData['sender'].' confirmed your tagged Extension Program/Project/Activity.')
                ->action('Go to Extension Program/Project Activity', $this->notificationData['url'])
                ->line('Thank you for using our application!');
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
