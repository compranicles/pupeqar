<?php

namespace App\Http\Controllers\Research;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use App\Models\{
    Report,
    Research,
    ResearchInvite,
    User,
};
use App\Notifications\ResearchInviteNotification;

class InviteController extends Controller
{
    public function index($research_id){
        $research = Research::find($research_id);
        $coResearchers = ResearchInvite::
                            where('research_invites.research_id', $research_id)
                            ->join('users', 'users.id', 'research_invites.user_id')
                            ->select('research_invites.id as invite_id', 'research_invites.status as research_status','users.*')
                            ->get();
        //get Nature of involvement
        $involvement = [];
        foreach($coResearchers as $row){
            if($row->research_status == "1"){
                $temp = Research::where('user_id', $row->id)
                            ->where('research_code', $research->research_code)
                            ->pluck('nature_of_involvement')->first();
                $involvement[$row->id] = $temp;
            }
        }
        
        $allEmployees = User::whereNotIn('users.id', (ResearchInvite::where('research_id', $research_id)->pluck('user_id')->all()))->
                            where('users.id', '!=', auth()->id())->
                            join('user_roles', 'user_roles.user_id', 'users.id')->
                            whereIn('user_roles.role_id', [1,3])->
                            select('users.*')->
                            get();
        
        return view('research.invite-researchers.index', compact('coResearchers', 'allEmployees', 'research', 'involvement'));
    }

    public function add($research_id, Request $request){

        $count = 0;
        foreach($request->input('employees') as $row){
            ResearchInvite::create([
                'user_id' => $row,
                'sender_id' => auth()->id(),
                'research_id' => $research_id
            ]);

            $user = User::find($row);
            $research_title = Research::where('id', $research_id)->pluck('title')->first();
            $sender = User::join('research', 'research.user_id', 'users.id')
                            ->where('research.user_id', auth()->id())
                            ->where('research.id', $research_id)
                            ->select('users.first_name', 'users.last_name', 'users.middle_name', 'users.suffix')->first();
            $url_accept = route('research.invite.confirm', $research_id);
            $url_deny = route('research.invite.cancel', $research_id);

            $notificationData = [
                'receiver' => $user->first_name,
                'title' => $research_title,
                'sender' => $sender->first_name.' '.$sender->middle_name.' '.$sender->last_name.' '.$sender->suffix,
                'url_accept' => $url_accept,
                'url_deny' => $url_deny,
                'date' => date('F j, Y, g:i a'),
                'type' => 'res-invite'
            ];

            Notification::send($user, new ResearchInviteNotification($notificationData));
            $count++;
        }
        \LogActivity::addToLog('Had added '.$count.' co-researcher/s in the research "'.$research_title.'".');

        return redirect()->route('research.invite.index', $research_id)->with('success', count($request->input('employees')).' people invited as co-researcher/s.');
    }

    public function confirm($research_id, Request $request){

        $user = User::find(auth()->id());

        \LogActivity::addToLog('Had confirmed as a co-researcher of a research.');

        $user->notifications->where('id', $request->get('id'))->markAsRead();
        
        return redirect()->route('research.code.create', ['research_id' => $research_id, 'id' => $request->get('id') ]);
    }
    
    public function cancel($research_id , Request $request){
        $user = User::find(auth()->id());

        ResearchInvite::where('research_id', $research_id)->where('user_id', auth()->id())->update([
            'status' => 0
        ]);

        $user->notifications->where('id', $request->get('id'))->markAsRead();
        DB::table('notifications')
            ->where('id', $request->get('id'))
            ->delete();
        
        \LogActivity::addToLog('Had denied as a co-researcher of a research.');

        return redirect()->route('research.index')->with('success', 'Invitation cancelled.');
    }

    public function remove($research_id, Request $request){
        $research = Research::find($research_id);

        if(Research::where('user_id', $request->input('user_id'))->where('research_code', $research->research_code)->exists()){
            $coResearchID = Research::where('research_code', $research->research_code)->where('user_id', $request->input('user_id'))->pluck('id')->first();
            if(Report::where('report_reference_id', $coResearchID)->where('report_category_id', 1)->where('user_id', $request->input('user_id'))->exists()){
                return redirect()->route('research.invite.index', $research_id)->with('error', 'Cannot do this action');
            }
            Research::where('research_code', $research->research_code)->where('user_id', $request->input('user_id'))->update([
                'is_active_member' => 0
            ]);

            $researchers = Research::where('research.research_code', $research->research_code)
            ->pluck('researchers')
            ->first();
            $researchersExplode = explode("/", $researchers);

            $researcherToRemove = User::select('users.first_name', 'users.last_name', 'users.middle_name')
                ->where('users.id', $request->input('user_id'))
                ->first();
            
            $middle = '';
            if ($researcherToRemove['middle_name'] != '') {
                $middle = substr($researcherToRemove['middle_name'],0,1).'.';
            }
            $researcherToRemove = ucwords(strtolower($researcherToRemove->last_name.', '.$researcherToRemove->first_name.' '.$middle));
            

            foreach($researchersExplode as $key => $researcher){
                if ($researcher == $researcherToRemove) {
                        unset($researchersExplode[$key]); 
                }
            }

            Research::where('research_code', $research->research_code)->update([
                'researchers' => implode("/", $researchersExplode)
            ]);

            ResearchInvite::where('research_id', $research_id)->where('user_id', $request->input('user_id'))->delete();

            return redirect()->route('research.invite.index', $research_id)->with('success', 'Action successful.');

        }
        
        ResearchInvite::where('research_id', $research_id)->where('user_id', $request->input('user_id'))->delete();

        \LogActivity::addToLog('Research Involvement removed.');
        
        return redirect()->route('research.invite.index', $research_id)->with('success', 'Sending invitation for co-researcher has been cancelled.');
    }
}
