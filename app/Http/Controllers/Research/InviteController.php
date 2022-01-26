<?php

namespace App\Http\Controllers\Research;

use App\Models\User;
use App\Models\Report;
use App\Models\Research;
use Illuminate\Http\Request;
use App\Models\ResearchInvite;
use App\Http\Controllers\Controller;

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
                            select('users.*')->
                            get();
        
        return view('research.invite-researchers.index', compact('coResearchers', 'allEmployees', 'research', 'involvement'));
    }

    public function add($research_id, Request $request){
        foreach($request->input('employees') as $row)
            ResearchInvite::create([
                'user_id' => $row,
                'sender_id' => auth()->id(),
                'research_id' => $research_id
            ]);

        return redirect()->route('research.invite.index', $research_id)->with('success', count($request->input('employees')).' people invited successfully');
    }

    public function confirm($research_id){
        return redirect()->route('research.code.create', $research_id);
    }
    
    public function cancel($research_id){
        ResearchInvite::where('research_id', $research_id)->where('user_id', auth()->id())->update([
            'status' => 0
        ]);

        return redirect()->route('research.index')->with('success', 'Invitation cancelled');
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

            $researchers = Research::select('users.first_name', 'users.last_name', 'users.middle_name')
                ->join('users',  'research.user_id', 'users.id')
                ->where('research.research_code', $research->research_code)->where('is_active_member', 1)
                ->get();

            $researcherNewName = '';
            foreach($researchers as $researcher){
                if(count($researchers) == 1)
                    $researcherNewName = $researcher->first_name.' '.(($researcher->middle_name == null) ? '' : $researcher->middle_name.' ').$researcher->last_name;
                else
                    $researcherNewName .= $researcher->first_name.' '.(($researcher->middle_name == null) ? '' : $researcher->middle_name.' ').$researcher->last_name.', ';
            }

            Research::where('research_code', $research->research_code)->update([
                'researchers' => $researcherNewName
            ]);

            ResearchInvite::where('research_id', $research_id)->where('user_id', $request->input('user_id'))->delete();

            return redirect()->route('research.invite.index', $research_id)->with('success', 'Action successful');

        }
        
        ResearchInvite::where('research_id', $research_id)->where('user_id', $request->input('user_id'))->delete();
        
        return redirect()->route('research.invite.index', $research_id)->with('success', 'Action successful');
    }
}
