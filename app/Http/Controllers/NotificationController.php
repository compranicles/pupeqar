<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function getByUser(){
        $user = User::find(auth()->id());

        $notifications = collect();
        foreach ($user->notifications as $notification) {
            $notifications = $notifications->push($notification->data);
        }

       return $user->notifications;
    }

    public function markAsRead(){
        $user = User::find(auth()->id());

        $user->unreadNotifications()->update(['read_at' => now()]);
        
        return true;
    }
}
