<?php

namespace App\Http\Controllers\Hap\Reviews;

use App\Models\Document;
use App\Models\Department;
use App\Models\Submission;
use Illuminate\Http\Request;
use App\Models\TemporaryFile;
use App\Models\AttendanceFunction;
use App\Http\Controllers\Controller;
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
    public function show(AttendanceFunction $attendancefunction)
    {
        $submission = Submission::where('submissions.form_id', $attendancefunction->id)
        ->where('submissions.form_name', 'attendancefunction')
        ->join('users', 'users.id', '=', 'submissions.user_id')
        ->select('submissions.status', 'users.first_name', 'users.last_name', 'users.middle_name')->get();
        $department = Department::find($attendancefunction->department_id);
        $documents = Document::where('submission_id', $attendancefunction->id)
        ->where('submission_type', 'attendancefunction')
        ->where('deleted_at', NULL)->get();

        return view('hap.review.attendancefunction.show', [
            'submission' => $submission[0],
            'documents' => $documents,
            'department' => $department,
            'attendancefunction' => $attendancefunction
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
        $submission = Submission::where('submissions.form_id', $attendancefunction->id)
        ->where('submissions.form_name', 'attendancefunction')
        ->join('users', 'users.id', '=', 'submissions.user_id')
        ->select('submissions.status', 'users.first_name', 'users.last_name', 'users.middle_name')->get();
        $departments = Department::all();
        $documents = Document::where('submission_id', $attendancefunction->id)
        ->where('submission_type', 'attendancefunction')
        ->where('deleted_at', NULL)->get();

        return view('hap.review.attendancefunction.edit', [
            'submission' => $submission[0],
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

        return redirect()->route('hap.review.attendancefunction.show', $attendancefunction->id)->with('success', 'Form updated successfully.');

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
        return redirect()->route('hap.review.index')->with('success_submission', 'Submission deleted successfully.');
    }
    
    public function removeFileInEdit(AttendanceFunction $attendancefunction, Request $request){
        Document::where('filename', $request->input('filename'))->delete();
        Storage::delete('documents/'.$request->input('filename'));
        return redirect()->route('hap.review.attendancefunction.edit', $attendancefunction)->with('success', 'Document deleted successfully.');
    }
}
