<?php

namespace App\Http\Controllers\Hap\Reviews;

use App\Models\Level;
use App\Models\Document;
use App\Models\Department;
use App\Models\Submission;
use App\Models\Officership;
use App\Models\RejectReason;
use Illuminate\Http\Request;
use App\Models\TemporaryFile;
use App\Models\FacultyOfficer;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class OfficershipController extends Controller
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
    public function show(Officership $officership)
    {
        $submission = Submission::where('submissions.form_id', $officership->id)
                    ->where('submissions.form_name', 'officership')
                    ->join('users', 'users.id', '=', 'submissions.user_id')
                    ->select('submissions.status', 'users.first_name', 'users.last_name', 'users.middle_name')->get();

        //getting reason
        $reason = 'reason';
        if($submission[0]->status == 3){
            $reason = RejectReason::where('form_id', $officership->id)
                    ->where('form_name', 'officership')->first();
            
            if(is_null($reason)){
                $reason = 'Your submission was rejected';
            }
        }

        $department = Department::find($officership->department_id);
        $facultyofficer = FacultyOfficer::find($officership->faculty_officer_id);
        $level = Level::find($officership->level_id);
        $documents = Document::where('submission_id' ,$officership->id)
                        ->where('submission_type', 'officership')
                        ->where('deleted_at', NULL)->get();

        return view('hap.review.officership.show', [
            'submission' => $submission[0],
            'officership' => $officership,
            'department' => $department,
            'facultyofficer' => $facultyofficer,
            'level' => $level,
            'documents' => $documents,
            'reason' => $reason
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Officership $officership)
    {
        $submission = Submission::where('submissions.form_id', $officership->id)
                    ->where('submissions.form_name', 'officership')
                    ->join('users', 'users.id', '=', 'submissions.user_id')
                    ->select('submissions.status', 'users.first_name', 'users.last_name', 'users.middle_name')->get();

        if($submission[0]->status != 1){
            return redirect()->route('hap.review.officership.show', $officership->id)->with('error', 'Edit Submission cannot be accessed');
        }

        $departments = Department::orderBy('name')->get();
        $facultyofficers = FacultyOfficer::all();
        $levels = Level::all();
        $documents = Document::where('submission_id' ,$officership->id)
                        ->where('submission_type', 'officership')
                        ->where('deleted_at', NULL)->get();

        return view('hap.review.officership.edit', [
            'submission' => $submission[0],
            'officership' => $officership,
            'departments' => $departments,
            'facultyofficers' => $facultyofficers,
            'levels' => $levels,
            'documents' => $documents
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Officership $officership)
    {
        $request->validate([
            'department' => 'required',
            'organization' => 'required',
            'facultyofficer' => 'required',
            'position' => 'required',
            'level' => 'required',
            'organizationaddress'=> 'required',
            'date_started' => 'required',
            'document' => 'required',
            'documentdescription' => 'required'
        ]);

        if(!$request->has('present')){
            $request->validate([
                'date_ended' => ['required'],
            ]);
        }

        $officership->update([
            'department_id' => $request->input('department'), 
            'organization' => $request->input('organization'),
            'faculty_officer_id' => $request->input('facultyofficer'),
            'position' => $request->input('position'),
            'level_id' => $request->input('level'),
            'organization_address' => $request->input('organizationaddress'),
            'date_started' => $request->input('date_started'),
            'date_ended' => $request->input('date_ended') ?? null,
            'present' => $request->input('present') ?? null,
            'documentdescription' => $request->input('documentdescription'),
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
                        'submission_id' => $officership->id,
                        'submission_type' => 'officership'
                    ]);
                }
            }
        }

        Submission::where('form_name', 'officership')
                ->where('form_id', $officership->id)
                ->update(['status' => 1]);

        return redirect()->route('hap.review.officership.show', $officership->id)->with('success', 'Form updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Officership $officership)
    {
        Document::where('submission_id' ,$officership->id)
                ->where('submission_type', 'officership')
                ->where('deleted_at', NULL)->delete();
        Submission::where('form_id', $officership->id)->where('form_name', 'officership')->delete();
        $officership->delete();
        return redirect()->route('hap.review.index')->with('success_submission', 'Submission deleted successfully.');
    }

    public function removeFileInEdit(Officership $officership, Request $request){
        Document::where('filename', $request->input('filename'))->delete();
        Storage::delete('documents/'.$request->input('filename'));
        return redirect()->route('hap.review.officership.edit', $officership)->with('success', 'Document deleted successfully.');
    }
}
