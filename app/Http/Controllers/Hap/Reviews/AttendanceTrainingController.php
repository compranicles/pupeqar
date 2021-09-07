<?php

namespace App\Http\Controllers\Hap\Reviews;

use App\Models\Level;
use App\Models\Document;
use App\Models\Department;
use App\Models\Submission;
use App\Models\FundingType;
use Illuminate\Http\Request;
use App\Models\DevelopNature;
use App\Models\TemporaryFile;
use App\Models\TrainingClass;
use App\Models\AttendanceTraining;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class AttendanceTrainingController extends Controller
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
    public function show(AttendanceTraining $attendancetraining)
    {
        $header = 'Attendance in Training/s';
        $department = Department::find($attendancetraining->department_id);
        $developclass = TrainingClass::find($attendancetraining->develop_class_id);
        $developnature = DevelopNature::find($attendancetraining->develop_nature_id);
        $fundingtype = FundingType::find($attendancetraining->funding_type_id);
        $level = Level::find($attendancetraining->level_id);
        $documents = Document::where('submission_id', $attendancetraining->id)
                        ->where('submission_type', 'attendancetraining')
                        ->where('deleted_at', NULL)
                        ->get();

        return view('hap.review.attendance.show',[
            'header' => $header,
            'controller' => 'attendancetraining',
            'attendance' => $attendancetraining,
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
    public function edit(AttendanceTraining $attendancetraining)
    {
        $header = 'B.3.2. Attendance in Training/s > Edit';
        $departments = Department::orderBy('name')->get();
        $developclasses = TrainingClass::all();
        $developnatures = DevelopNature::all();
        $fundingtypes = FundingType::all();
        $levels = Level::all();
        $documents = Document::where('submission_id', $attendancetraining->id)
                        ->where('submission_type', 'attendancetraining')
                        ->where('deleted_at', NULL)
                        ->get();

        return view('hap.review.attendance.edit',[
            'header' => $header,
            'controller' => 'attendancetraining',
            'attendance' => $attendancetraining,
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
    public function update(Request $request, AttendanceTraining $attendancetraining)
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

        $attendancetraining->update([
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
                        'submission_id' => $attendancetraining->id,
                        'submission_type' => 'attendancetraining'
                    ]);
                }
            }
        }

        Submission::where('form_name', 'attendancetraining')
                ->where('form_id', $attendancetraining->id)
                ->update(['status' => 1]);

        return redirect()->route('hap.review.attendancetraining.show', $attendancetraining)->with('success', 'Form updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(AttendanceTraining $attendancetraining)
    {
        Document::where('submission_id' ,$attendancetraining->id)
                ->where('submission_type', 'attendancetraining')
                ->where('deleted_at', NULL)->delete();
        Submission::where('form_id', $attendancetraining->id)->where('form_name', 'attendancetraining')->delete();
        $attendancetraining->delete();
        return redirect()->route('hap.review.index')->with('success_submission', 'Submission deleted successfully.');
    }

    public function removeFileInEdit(AttendanceTraining $attendancetraining, Request $request){
        Document::where('filename', $request->input('filename'))->delete();
        Storage::delete('documents/'.$request->input('filename'));
        return redirect()->route('hap.review.attendancetraining.edit', $attendancetraining)->with('success', 'Document deleted successfully.');
    }
}
