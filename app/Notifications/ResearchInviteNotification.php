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
        return ['database'];
        // return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        if($this->notificationData['type'] == 'res-invite')
            return (new MailMessage)
                ->subject('PUP eQAR | Research/Book Chapter Tagging Notification')
                ->greeting('Hello '.$this->notificationData['receiver'].'!')
                ->line('You are tagged as a co-researcher by '.$this->notificationData['sender'].' in a research with the title: "'.$this->notificationData['title'].'."')
                ->line('For confirmation:')
                ->line('1. Click the button "Go to Research/Book Chapter" in this message.')
                ->line('2. Click the button "Research to Add (Tagged by your Lead)".')
                ->line('3. From the list, add the extension where you are tagged and save.')
                ->action('Go to Research/Book Chapter', route('research.index'))
                ->line('Thank you for using our application!');
        elseif($this->notificationData['type'] == 'res-confirm')
            return (new MailMessage)
                ->subject('PUP eQAR | Research/Book Chapter Tagging Confirmation Notification')
                ->greeting('Hello '.$this->notificationData['receiver'].'!')
                ->line($this->notificationData['sender'].' confirmed your tagged Research/Book Chapter with the title: "'.$this->notificationData['title'].'."')
                ->action('Go to Research/Book Chapter', $this->notificationData['url'])
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
        if($this->notificationData['type'] == 'res-invite')
            return [
                'receiver' => $this->notificationData['receiver'],
                'title' => $this->notificationData['title'],
                'sender' =>  $this->notificationData['sender'],
                'url_accept' => $this->notificationData['url_accept'],
                'url_deny' =>  $this->notificationData['url_deny'],
                'date' => $this->notificationData['date'],
                'type' => $this->notificationData['type']
            ];
        elseif($this->notificationData['type'] == 'res-confirm')
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
