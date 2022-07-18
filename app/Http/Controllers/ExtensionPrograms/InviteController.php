<?php

namespace App\Http\Controllers\ExtensionPrograms;

use App\Models\User;
use App\Models\Report;
use Illuminate\Http\Request;
use App\Models\ExtensionInvite;
use App\Models\ExtensionService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ExtensionInviteNotification;
use Illuminate\Support\Facades\DB;

class InviteController extends Controller
{
    public function index($id){

        $extension = ExtensionService::find($id);

        $coExtensionists = ExtensionInvite::
                    where('extension_invites.ext_code', $extension->ext_code)
                    ->join('users', 'users.id', 'extension_invites.user_id')
                    ->select('extension_invites.id as invite_id', 'extension_invites.status as extension_status','users.*', 'extension_invites.is_owner')
                    ->get();

        //get Nature of involvement
        $involvement = [];
        foreach($coExtensionists as $row){
            if($row->extension_status == "1"){
                $temp = ExtensionService::where('user_id', $row->id)
                            ->where('ext_code', $extension->ext_code)
                            ->pluck('nature_of_involvement')->first();
                $involvement[$row->id] = $temp;
            }
        }

        $allEmployees = User::whereNotIn('users.id', (ExtensionInvite::where('extension_service_id', $id)->pluck('user_id')->all()))->
                            where('users.id', '!=', auth()->id())->
                            select('users.*')->
                            get();
        
        return view('extension-programs.extension-services.invite.index', compact('coExtensionists', 'allEmployees', 'extension', 'involvement'));
    }

    public function add($id, Request $request){

        $count = 0;

        $extension = ExtensionService::where('id', $id)->first();
        foreach($request->input('employees') as $row){
            ExtensionInvite::create([
                'user_id' => $row,
                'sender_id' => auth()->id(),
                'extension_service_id' => $id,
                'ext_code' => $extension->ext_code
            ]);

            $user = User::find($row);
            $extension_title = "Extension";
            $sender = User::join('extension_services', 'extension_services.user_id', 'users.id')
                            ->where('extension_services.user_id', auth()->id())
                            ->where('extension_services.id', $id)
                            ->select('users.first_name', 'users.last_name', 'users.middle_name', 'users.suffix')->first();
            $url_accept = route('extension.invite.confirm', $id);
            $url_deny = route('extension.invite.cancel', $id);

            $notificationData = [
                'receiver' => $user->first_name,
                'title' => $extension_title,
                'sender' => $sender->first_name.' '.$sender->middle_name.' '.$sender->last_name.' '.$sender->suffix,
                'url_accept' => $url_accept,
                'url_deny' => $url_deny,
                'date' => date('F j, Y, g:i a'),
                'type' => 'ext-invite'
            ];

            Notification::send($user, new ExtensionInviteNotification($notificationData));
            $count++;
        }
        \LogActivity::addToLog('Had added '.$count.' extension partners in an extension program/project/activity.');

        return redirect()->route('extension.invite.index', $id)->with('success', count($request->input('employees')).' people invited as extension partner/s.');
    }

    public function confirm($id, Request $request){

        $user = User::find(auth()->id());

        \LogActivity::addToLog('Had confirmed as an extension partner in an extension program/project/activity.');

        $user->notifications->where('id', $request->get('id'))->markAsRead();
        
        return redirect()->route('extension.code.create', ['extension_service_id' => $id, 'id' => $request->get('id') ]);
    }
    
    public function cancel($id , Request $request){
        $user = User::find(auth()->id());

        ExtensionInvite::where('extension_service_id', $id)->where('user_id', auth()->id())->update([
            'status' => 0
        ]);

        $user->notifications->where('id', $request->get('id'))->markAsRead();
        DB::table('notifications')
            ->where('id', $request->get('id'))
            ->delete();
        
        \LogActivity::addToLog('Had denied as an extension partner in an extension program/project/activity.');

        return redirect()->route('extension-service.index')->with('success', 'Invitation cancelled.');
    }

    public function remove($id, Request $request){
        $extension = ExtensionService::find($id);

        if(ExtensionService::where('user_id', $request->input('user_id'))->where('ext_code', $extension->ext_code)->exists()){
            $coESID = ExtensionService::where('ext_code', $extension->ext_code)->where('user_id', $request->input('user_id'))->pluck('id')->first();
            if(Report::where('report_reference_id', $coESID)->where('report_category_id', 12)->where('user_id', $request->input('user_id'))->exists()){
                return redirect()->route('extension.invite.index', $id)->with('error', 'Cannot do this action');
            }

            ExtensionService::where('user_id', $request->input('user_id'))->where('ext_code', $extension->ext_code)->delete();

            ExtensionInvite::where('extension_service_id', $id)->where('user_id', $request->input('user_id'))->delete();

            return redirect()->route('extension.invite.index', $id)->with('success', 'Action successful.');

        }
        
        ExtensionInvite::where('extension_service_id', $id)->where('user_id', $request->input('user_id'))->delete();

        \LogActivity::addToLog('Extensionists removed.');

        
        return redirect()->route('extension.invite.index', $id)->with('success', 'Sending confirmation for extension partner has been cancelled.');
    }
}
