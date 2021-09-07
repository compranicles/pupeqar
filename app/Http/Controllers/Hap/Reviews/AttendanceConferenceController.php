<?php

namespace App\Http\Controllers\Hap\Reviews;

use App\Models\Level;
use App\Models\Document;
use App\Models\Department;
use App\Models\Submission;
use App\Models\FundingType;
use App\Models\DevelopClass;
use Illuminate\Http\Request;
use App\Models\DevelopNature;
use App\Models\TemporaryFile;
use App\Http\Controllers\Controller;
use App\Models\AttendanceConference;
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
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(AttendanceConference $attendanceconference)
    {
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

        return view('hap.review.attendance.show',[
            'header' => $header,
            'controller' => 'attendanceconference',
            'attendance' => $attendanceconference,
            'department' => $department,
            'developclass' => $developclass,
            'developnature' => $developnature,
            'fundingtype' => $fundingtype,
            'level' => $level,
            'documents' => $documents,
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

        return view('hap.review.attendance.edit',[
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

        return redirect()->route('hap.review.attendanceconference.show', $attendanceconference)->with('success', 'Form updated successfully.');

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
        return redirect()->route('hap.review.index')->with('success_submission', 'Submission deleted successfully.');
    }

    public function removeFileInEdit(AttendanceConference $attendanceconference, Request $request){
        Document::where('filename', $request->input('filename'))->delete();
        Storage::delete('documents/'.$request->input('filename'));
        return redirect()->route('hap.review.attendanceconference.edit', $attendanceconference)->with('success', 'Document deleted successfully.');
    }
}
