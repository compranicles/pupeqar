<?php

namespace App\Http\Controllers\Submissions;

use App\Models\Level;
use App\Models\Document;
use App\Models\Department;
use App\Models\Submission;
use App\Models\FundingType;
use App\Models\DevelopClass;
use App\Models\RejectReason;
use Illuminate\Http\Request;
use App\Models\DevelopNature;
use App\Models\TemporaryFile;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\AttendanceConference;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AttendanceConferenceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $header = 'B.3.1 Attendance in Relevant Faculty Development Program (Seminars/Webinars, Fora/Conferences) > Create';
        $departments = Department::orderBy('name')->get();
        $developclasses = DevelopClass::all();
        $developnatures = DevelopNature::all();
        $fundingtypes = FundingType::all();
        $levels = Level::all();

        return view('professors.submissions.attendance.create', [
            'header' => $header,
            'controller' => 'attendanceconference',
            'departments' => $departments,
            'developclasses' => $developclasses,
            'developnatures' => $developnatures,
            'fundingtypes' => $fundingtypes,
            'levels' => $levels
        ]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'department' => 'required', 
            'title'  => 'required',
            'developclass'  => 'required',
            'developnature'  => 'required',
            'budget' => ['numeric', 'required'],
            'fundingtype' => 'required',
            'organizer'  => 'required',
            'level'  => 'required',
            'venue'  => 'required',
            'date_started'  => 'required',
            'date_ended'  => 'required',
            'totalhours' => ['numeric', 'required'],
            'document' => 'required',
            'documentdescription'  => 'required',
        ]);

        $formId = DB::table('attendance_conferences')->insertGetId([
            'department_id' => $request->input('department'),
            'title' => $request->input('title'),
            'develop_class_id' => $request->input('developclass'),
            'develop_nature_id' => $request->input('developnature'),
            'budget' => $request->input('budget'),
            'funding_type_id' => $request->input('fundingtype'),
            'organizer' => $request->input('organizer'),
            'level_id' => $request->input('level'),
            'venue' => $request->input('venue'),
            'date_started' => $request->input('date_started'),
            'date_ended' => $request->input('date_ended'),
            'total_hours' => $request->input('totalhours'),
            'document_description' => $request->input('documentdescription')
        ]);

        if($request->has('document')){
            
            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $newPath = "documents/".$temporaryFile->filename;
                    $fileName = $temporaryFile->filename;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    Document::create([
                        'filename' => $fileName,
                        'submission_id' => $formId,
                        'submission_type' => 'attendanceconference'
                    ]);
                }
            }
        }

        Submission::create([
            'user_id' => Auth::id(),
            'form_id' => $formId,
            'form_name' => 'attendanceconference',
            'status' => 1
        ]);

        return redirect()->route('professor.submissions.index')->with('success_submission', 'Submission added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(AttendanceConference $attendanceconference)
    {
        $submission = Submission::where('submissions.form_id', $attendanceconference->id)
                        ->where('submissions.form_name', 'attendanceconference')
                        ->join('users', 'users.id', '=', 'submissions.user_id')
                        ->select('submissions.status')->get();

        //getting reason
        $reason = 'reason';
        if($submission[0]->status == 3){
            $reason = RejectReason::where('form_id', $attendanceconference->id)
                    ->where('form_name', 'attendanceconference')->latest()->first();
            
            if(is_null($reason)){
                $reason = 'Your submission was rejected';
            }
        }

        $header = 'Attendance in Relevant Faculty Development Program (Seminars/Webinars, Fora/Conferences)';
        $department = Department::find($attendanceconference->department_id);
        $developclass = DevelopClass::find($attendanceconference->develop_class_id);
        $developnature = DevelopNature::find($attendanceconference->develop_nature_id);
        $fundingtype = FundingType::find($attendanceconference->funding_type_id);
        $level = Level::find($attendanceconference->level_id);
        $documents = Document::where('submission_id', $attendanceconference->id)
                        ->where('submission_type', 'attendanceconference')
                        ->where('deleted_at', NULL)
                        ->get();
                
        return view('professors.submissions.attendance.show',[
            'header' => $header,
            'controller' => 'attendanceconference',
            'attendance' => $attendanceconference,
            'department' => $department,
            'developclass' => $developclass,
            'developnature' => $developnature,
            'fundingtype' => $fundingtype,
            'level' => $level,
            'documents' => $documents,
            'submission' => $submission[0],
            'reason' => $reason,
        ]);
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(AttendanceConference $attendanceconference)
    {
        $submission = Submission::where('form_id', $attendanceconference->id)
                    ->where('form_name', 'attendanceconference')
                    ->get();
                    
        if($submission[0]->status != 1){
            return redirect()->route('professor.submissions.attendanceconference.show', $attendanceconference->id)->with('error', 'Edit Submission cannot be accessed');
        }
        $header = 'B.3.1. Attendance in Relevant Faculty Development Program (Seminars/Webinars, Fora/Conferences) > Edit';
        $departments = Department::orderBy('name')->get();
        $developclasses = DevelopClass::all();
        $developnatures = DevelopNature::all();
        $fundingtypes = FundingType::all();
        $levels = Level::all();
        $documents = Document::where('submission_id', $attendanceconference->id)
                        ->where('submission_type', 'attendanceconference')
                        ->where('deleted_at', NULL)
                        ->get();


        return view('professors.submissions.attendance.edit',[
            'header' => $header,
            'controller' => 'attendanceconference',
            'attendance' => $attendanceconference,
            'departments' => $departments,
            'developclasses' => $developclasses,
            'developnatures' => $developnatures,
            'fundingtypes' => $fundingtypes,
            'levels' => $levels,
            'documents' => $documents,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AttendanceConference $attendanceconference)
    {
        $request->validate([
            'department' => 'required', 
            'title'  => 'required',
            'developclass'  => 'required',
            'developnature'  => 'required',
            'budget' => ['numeric', 'required'],
            'fundingtype' => 'required',
            'organizer'  => 'required',
            'level'  => 'required',
            'venue'  => 'required',
            'date_started'  => 'required',
            'date_ended'  => 'required',
            'totalhours' => ['numeric', 'required'],
            'document' => 'required',
            'documentdescription'  => 'required',
        ]);

        $attendanceconference->update([
            'department_id' => $request->input('department'),
            'title' => $request->input('title'),
            'develop_class_id' => $request->input('developclass'),
            'develop_nature_id' => $request->input('developnature'),
            'budget' => $request->input('budget'),
            'funding_type_id' => $request->input('fundingtype'),
            'organizer' => $request->input('organizer'),
            'level_id' => $request->input('level'),
            'venue' => $request->input('venue'),
            'date_started' => $request->input('date_started'),
            'date_ended' => $request->input('date_ended'),
            'total_hours' => $request->input('totalhours'),
            'document_description' => $request->input('documentdescription')
        ]);

        if($request->has('document')){
            
            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $newPath = "documents/".$temporaryFile->filename;
                    $fileName = $temporaryFile->filename;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    Document::create([
                        'filename' => $fileName,
                        'submission_id' => $attendanceconference->id,
                        'submission_type' => 'attendanceconference'
                    ]);
                }
            }
        }

        Submission::where('form_name', 'attendanceconference')
                ->where('form_id', $attendanceconference->id)
                ->update(['status' => 1]);

        return redirect()->route('professor.submissions.attendanceconference.show', $attendanceconference)->with('success', 'Form updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(AttendanceConference $attendanceconference)
    {
        Document::where('submission_id' ,$attendanceconference->id)
                ->where('submission_type', 'attendanceconference')
                ->where('deleted_at', NULL)->delete();
        Submission::where('form_id', $attendanceconference->id)->where('form_name', 'attendanceconference')->delete();
        $attendanceconference->delete();
        return redirect()->route('professor.submissions.index')->with('success_submission', 'Submission deleted successfully.');
    }

    public function removeFileInEdit(AttendanceConference $attendanceconference, Request $request){
        Document::where('filename', $request->input('filename'))->delete();
        Storage::delete('documents/'.$request->input('filename'));

        $submission = Submission::where('form_id', $attendanceconference->id)
                    ->where('form_name', 'attendanceconference')
                    ->get();
                    
        if($submission[0]->status != 1){
            return redirect()->route('professor.attendanceconference.resubmit', $attendanceconference->id)->with('success', 'Document deleted successfully.');
        }

        return redirect()->route('professor.submissions.attendanceconference.edit', $attendanceconference)->with('success', 'Document deleted successfully.');
    }

    public function resubmit(AttendanceConference $attendanceconference){
        $header = 'B.3.1. Attendance in Relevant Faculty Development Program (Seminars/Webinars, Fora/Conferences) > Resubmission';
        $departments = Department::orderBy('name')->get();
        $developclasses = DevelopClass::all();
        $developnatures = DevelopNature::all();
        $fundingtypes = FundingType::all();
        $levels = Level::all();
        $documents = Document::where('submission_id', $attendanceconference->id)
                        ->where('submission_type', 'attendanceconference')
                        ->where('deleted_at', NULL)
                        ->get();

        return view('professors.submissions.attendance.edit',[
            'header' => $header,
            'controller' => 'attendanceconference',
            'attendance' => $attendanceconference,
            'departments' => $departments,
            'developclasses' => $developclasses,
            'developnatures' => $developnatures,
            'fundingtypes' => $fundingtypes,
            'levels' => $levels,
            'documents' => $documents,
        ]);
    }
}
