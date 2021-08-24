<?php

namespace App\Http\Controllers\Professors;

use App\Models\Submission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SubmissionController extends Controller
{ 
    public function index()
    {
        $submissions = Submission::orderBy('created_at', 'desc')->where('deleted_at', NULL)->get();

        return view('professors.submissions.index', compact('submissions'));
    }
    
    public function formselect(Request $request)
    {
        $request->validate([
            'form_type' => ['required']
        ]);

        switch($request->input('form_type')){
            case 'ongoingadvanced': 
                    return redirect()->route('professor.submissions.ongoingadvanced.create');
                break;
            case 'facultyaward':
                    return redirect()->route('professor.submissions.facultyaward.create');
                break;
        }

        return redirect()->route('professor.submissions.index');
    }

}
