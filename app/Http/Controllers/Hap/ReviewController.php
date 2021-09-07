<?php

namespace App\Http\Controllers\Hap;

use App\Models\Submission;
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
            $submissions = Submission::where('status', 2)->get();
        }
        elseif($keyword == "rejected"){
            $submissions = Submission::where('status', 3)->get();
        }
        return view('hap.review.index', [
            'keyword' => $keyword,
            'submissions' => $submissions
        ]);
    }

    public function accept(Request $request){
        $form_id = $request->input('formId');
        $form_name = $request->input('formname');

        
    }
}
