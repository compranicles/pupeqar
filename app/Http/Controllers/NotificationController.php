<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Notification\ReturnNotification;
use Illuminate\Support\Facades\DB;

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

    public function markAsRead(Request $request){
        $user = User::find(auth()->id());

        $urlDecoded  = urldecode($request->get('u'));

        $user->notifications->where('id', $request->get('v'))->markAsRead();
        
        return redirect()->away($urlDecoded);
    }

    public function seeAll() {
        return view('notification-see-all');
    }
}
