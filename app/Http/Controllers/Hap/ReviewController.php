<?php

namespace App\Http\Controllers\Hap;

use App\Models\User;
use App\Models\Submission;
use App\Models\RejectReason;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReviewController extends Controller
{
    public function index(Request $request){
        $users = User::where('role_id', '!=', 1)->orderBy('last_name', 'asc')->get();
        $ongoingstudies = Submission::where('submissions.form_name', 'ongoingadvanced')
                        ->join('users', 'users.id', '=', 'submissions.user_id')
                        ->join('ongoing_advanceds', 'submissions.form_id', '=', 'ongoing_advanceds.id')
                        ->join('departments', 'departments.id', '=', 'ongoing_advanceds.department_id')
                        ->select('users.last_name', 'users.first_name', 'users.last_name', 'ongoing_advanceds.*', 'submissions.*')
                        ->get();
        
        return view('hap.review.index', [
            'users' => $users,
            'ongoingstudies' => $ongoingstudies,
        ]);
    }

    // public function accept(Request $request){
    //     $form_id = $request->input('formId');
    //     $form_name = $request->input('formname');

    //     Submission::where('form_name', $form_name)
    //                 ->where('form_id', $form_id)
    //                 ->update(['status' => 2]);
    //     return redirect()->route('hap.review.'.$form_name.'.show', $form_id)->with('success', 'Submission accepted successfully');
    // }

    // public function reject(Request $request){
    //     $form_id = $request->input('formId');
    //     $form_name = $request->input('formname');
    //     $comment = $request->input('comment');

    //     Submission::where('form_name', $form_name)
    //                 ->where('form_id', $form_id)
    //                 ->update(['status' => 3]);

    //     RejectReason::create([
    //        'form_id' => $form_id,
    //        'form_name' => $form_name,
    //        'reason' => $comment
    //     ]);
    //     return redirect()->route('hap.review.'.$form_name.'.show', $form_id)->with('success', 'Submission rejected successfully');
    // }
}
