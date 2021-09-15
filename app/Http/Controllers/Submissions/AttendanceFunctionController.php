<?php

namespace App\Http\Controllers\Submissions;

use App\Models\Document;
use App\Models\Department;
use App\Models\Submission;
use App\Models\RejectReason;
use Illuminate\Http\Request;
use App\Models\TemporaryFile;
use App\Models\AttendanceFunction;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AttendanceFunctionController extends Controller
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
        $departments = Department::all();

        return view('professors.submissions.attendancefunction.create', [
            'departments' => $departments
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
            'activity' => 'required',
            'datestarted' => 'required',
            'datecompleted' => 'required',
            'status' => 'required'
        ]);

        $formId = DB::table('attendance_functions')->insertGetId([
            'department_id' => $request->input('department'),
            'activity' => $request->input('activity'),
            'date_started' => $request->input('datestarted'),
            'date_completed' => $request->input('datecompleted'),
            'status' => $request->input('status'),
            'proof' => $request->input('proof') ?? null
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
                        'submission_type' => 'attendancefunction'
                    ]);
                }
            }
        }

        Submission::create([
            'user_id' => Auth::id(),
            'form_id' => $formId,
            'form_name' => 'attendancefunction',
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
    public function show(AttendanceFunction $attendancefunction)
    {
        $submission = Submission::where('submissions.form_id', $attendancefunction->id)
                                ->where('submissions.form_name', 'attendancefunction')
                                ->join('users', 'users.id', '=', 'submissions.user_id')
                                ->select('submissions.status')->get();

        //getting reason
        $reason = 'reason';
        if($submission[0]->status == 3){
            $reason = RejectReason::where('form_id', $attendancefunction->id)
                    ->where('form_name', 'attendancefunction')->latest()->first();
            
            if(is_null($reason)){
                $reason = 'Your submission was rejected';
            }
        }

        $department = Department::find($attendancefunction->department_id);
        $documents = Document::where('submission_id', $attendancefunction->id)
                                ->where('submission_type', 'attendancefunction')
                                ->where('deleted_at', NULL)->get();

        return view('professors.submissions.attendancefunction.show', [
            'documents' => $documents,
            'department' => $department,
            'attendancefunction' => $attendancefunction,
            'submission' => $submission[0],
            'reason' => $reason
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(AttendanceFunction $attendancefunction)
    {
        $submission = Submission::where('form_id', $attendancefunction->id)
        ->where('form_name', 'attendancefunction')
        ->get();

        if($submission[0]->status != 1){
            return redirect()->route('professor.submissions.attendancefunction.show', $attendancefunction->id)->with('error', 'Edit Submission cannot be accessed');
        }

        $departments = Department::all();
        $documents = Document::where('submission_id', $attendancefunction->id)
        ->where('submission_type', 'attendancefunction')
        ->where('deleted_at', NULL)->get();

        return view('professors.submissions.attendancefunction.edit', [
            'departments' => $departments,
            'documents' => $documents,
            'attendancefunction' => $attendancefunction
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AttendanceFunction $attendancefunction)
    {
        $request->validate([
            'department' => 'required',
            'activity' => 'required',
            'datestarted' => 'required',
            'datecompleted' => 'required',
            'status' => 'required'
        ]);

        $attendancefunction->update([
            'department_id' => $request->input('department'),
            'activity' => $request->input('activity'),
            'date_started' => $request->input('datestarted'),
            'date_completed' => $request->input('datecompleted'),
            'status' => $request->input('status'),
            'proof' => $request->input('proof') ?? null
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
                        'submission_id' => $attendancefunction->id,
                        'submission_type' => 'attendancefunction'
                    ]);
                }
            }
        }
        
        Submission::where('form_name', 'attendancefunction')
                ->where('form_id', $attendancefunction->id)
                ->update(['status' => 1]);

        return redirect()->route('professor.submissions.attendancefunction.show', $attendancefunction->id)->with('success', 'Form updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(AttendanceFunction $attendancefunction)
    {
        Document::where('submission_id' ,$attendancefunction->id)
                ->where('submission_type', 'attendancefunction')
                ->where('deleted_at', NULL)->delete();
        Submission::where('form_id', $attendancefunction->id)->where('form_name', 'attendancefunction')->delete();
        $attendancefunction->delete();
        return redirect()->route('professor.submissions.index')->with('success_submission', 'Submission deleted successfully.');
    }
    
    public function removeFileInEdit(AttendanceFunction $attendancefunction, Request $request){
        Document::where('filename', $request->input('filename'))->delete();
        Storage::delete('documents/'.$request->input('filename'));

        
        $submission = Submission::where('form_id', $attendancefunction->id)
        ->where('form_name', 'attendancefunction')
        ->get();
        
        if($submission[0]->status != 1){
            return redirect()->route('professor.attendancefunction.resubmit', $attendancefunction->id)->with('success', 'Document deleted successfully.');
        }

        return redirect()->route('professor.submissions.attendancefunction.edit', $attendancefunction)->with('success', 'Document deleted successfully.');
    }

    public function resubmit(AttendanceFunction $attendancefunction){
        $departments = Department::all();
        $documents = Document::where('submission_id', $attendancefunction->id)
        ->where('submission_type', 'attendancefunction')
        ->where('deleted_at', NULL)->get();

        return view('professors.submissions.attendancefunction.edit', [
            'departments' => $departments,
            'documents' => $documents,
            'attendancefunction' => $attendancefunction
        ]);
    }
}
