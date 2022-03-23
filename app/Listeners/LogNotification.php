<?php

namespace App\Listeners;

use App\Models\NotificationCounter;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Events\NotificationSent;

class LogNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \Illuminate\Notifications\Events\NotificationSent  $event
     * @return void
     */
    public function handle(NotificationSent $event)
    {
        if($event->channel == 'database'){
            $notifiable = $event->notifiable;

            if(NotificationCounter::where('user_id', $notifiable->id)->exists()){
                $notification = NotificationCounter::where('user_id', $notifiable->id)->first();
                $count = $notification->count + 1;
                $notification->update([
                    'count' => $count
                ]);
            }
            else{
                NotificationCounter::create([
                    'user_id' => $notifiable->id,
                    'count' => 1
                ]);
            }

        }
    }
}
