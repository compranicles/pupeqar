<?php

namespace App\Http\Controllers\Submissions;

use App\Models\Level;
use App\Models\Document;
use App\Models\Department;
use App\Models\Submission;
use App\Models\Officership;
use Illuminate\Http\Request;
use App\Models\TemporaryFile;
use App\Models\FacultyOfficer;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
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
  
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $departments = Department::orderBy('name')->get();
        $facultyofficers = FacultyOfficer::all();
        $levels = Level::all();
        return view('professors.submissions.officership.create', [
            'departments' => $departments,
            'facultyofficers' => $facultyofficers,
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

        $formId = DB::table('officerships')->insertGetId([
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
                        'submission_id' => $formId,
                        'submission_type' => 'officership'
                    ]);
                }
            }
        }

        Submission::create([
            'user_id' => Auth::id(),
            'form_id' => $formId,
            'form_name' => 'officership',
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
    public function show(Officership $officership)
    {
        $department = Department::find($officership->department_id);
        $facultyofficer = FacultyOfficer::find($officership->faculty_officer_id);
        $level = Level::find($officership->level_id);
        $documents = Document::where('submission_id' ,$officership->id)
                        ->where('submission_type', 'officership')
                        ->where('deleted_at', NULL)->get();
        $submission = Submission::where('submissions.form_id', $officership->id)
                        ->where('submissions.form_name', 'officership')
                        ->join('users', 'users.id', '=', 'submissions.user_id')
                        ->select('submissions.status')->get();

        return view('professors.submissions.officership.show', [
            'officership' => $officership,
            'department' => $department,
            'facultyofficer' => $facultyofficer,
            'level' => $level,
            'documents' => $documents,
            'submission' => $submission[0]
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
        $departments = Department::orderBy('name')->get();
        $facultyofficers = FacultyOfficer::all();
        $levels = Level::all();
        $documents = Document::where('submission_id' ,$officership->id)
                        ->where('submission_type', 'officership')
                        ->where('deleted_at', NULL)->get();

        return view('professors.submissions.officership.edit', [
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

        return redirect()->route('professor.submissions.officership.show', $officership->id)->with('success', 'Form updated successfully.');
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
        return redirect()->route('professor.submissions.index')->with('success_submission', 'Submission deleted successfully.');
    }

    public function removeFileInEdit(Officership $officership, Request $request){
        Document::where('filename', $request->input('filename'))->delete();
        Storage::delete('documents/'.$request->input('filename'));
        return redirect()->route('professor.submissions.officership.edit', $officership)->with('success', 'Document deleted successfully.');
    }
}
