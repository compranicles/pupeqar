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
            case 'officership':
                    return redirect()->route('professor.submissions.officership.create');
                break;
            case 'attendanceconference':
                    return redirect()->route('professor.submissions.attendanceconference.create');
                break;
            case 'attendancetraining':
                    return redirect()->route('professor.submissions.attendancetraining.create');
                break;
            case 'research':
                    return redirect()->route('professor.submissions.research.create');
                break;
            case 'researchpublication':
                    return redirect()->route('professor.submissions.researchpublication.create');
                break;
            case 'researchpresentation':
                    return redirect()->route('professor.submissions.researchpresentation.create');
                break;
            case 'researchcitation': 
                    return redirect()->route('professor.submissions.researchcitation.create');
                break;
            case 'researchutilization': 
                    return redirect()->route('professor.submissions.researchutilization.create');
                break;
            case 'researchcopyright': 
                    return redirect()->route('professor.submissions.researchcopyright.create');
                break;
            case 'invention':
                    return redirect()->route('professor.submissions.invention.create');
                break;
            case 'expertconsultant':
                    return redirect()->route('professor.submissions.expertconsultant.create');
                break;
            case 'expertconference':
                    return redirect()->route('professor.submissions.expertconference.create');
                break;
            case 'expertjournal':
                    return redirect()->route('professor.submissions.expertjournal.create');
                break;
            case 'extensionprogram':
                    return redirect()->route('professor.submissions.extensionprogram.create');
                break;
            case 'partnership':
                    return redirect()->route('professor.submissions.partnership.create');
                break;
            case 'facultyintercountry':
                    return redirect()->route('professor.submissions.facultyintercountry.create');
                break;
            case 'material':
                    return redirect()->route('professor.submissions.material.create');
                break;
            case 'syllabus':
                    return redirect()->route('professor.submissions.syllabus.create');
                break;
        }

        return redirect()->route('professor.submissions.index');
    }

}
