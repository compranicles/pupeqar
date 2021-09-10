<?php

namespace App\Http\Controllers\Hap;

use App\Models\Submission;
use App\Models\RejectReason;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReviewController extends Controller
{
    public function index(Request $request){
        $keyword = $request->input('status');
        $submissions = [];
        if($keyword == ''){
            $submissions = Submission::where('status', 1)
                ->join('users', 'submissions.user_id', '=', 'users.id')
                ->select('submissions.*', 'users.first_name', 'users.middle_name', 'users.last_name')    
                ->get();
        }
        elseif($keyword == "accepted"){
            $submissions = Submission::where('status', 2)
            ->join('users', 'submissions.user_id', '=', 'users.id')
            ->select('submissions.*', 'users.first_name', 'users.middle_name', 'users.last_name')    
            ->get();
        }
        elseif($keyword == "rejected"){
            $submissions = Submission::where('status', 3)
            ->join('users', 'submissions.user_id', '=', 'users.id')
            ->select('submissions.*', 'users.first_name', 'users.middle_name', 'users.last_name')    
            ->get();
        }
        return view('hap.review.index', [
            'keyword' => $keyword,
            'submissions' => $submissions
        ]);
    }

    public function accept(Request $request){
        $form_id = $request->input('formId');
        $form_name = $request->input('formname');

        Submission::where('form_name', $form_name)
                    ->where('form_id', $form_id)
                    ->update(['status' => 2]);
        return redirect()->route('hap.review.'.$form_name.'.show', $form_id)->with('success', 'Submission accepted successfully');
    }

    public function reject(Request $request){
        $form_id = $request->input('formId');
        $form_name = $request->input('formname');
        $comment = $request->input('comment');

        Submission::where('form_name', $form_name)
                    ->where('form_id', $form_id)
                    ->update(['status' => 3]);

        RejectReason::create([
           'form_id' => $form_id,
           'form_name' => $form_name,
           'reason' => $comment
        ]);
        return redirect()->route('hap.review.'.$form_name.'.show', $form_id)->with('success', 'Submission rejected successfully');
    }
}
