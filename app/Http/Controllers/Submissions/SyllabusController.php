<?php

namespace App\Http\Controllers\Submissions;

use App\Models\Document;
use App\Models\Syllabus;
use App\Models\Department;
use App\Models\Submission;
use Illuminate\Http\Request;
use App\Models\TemporaryFile;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SyllabusController extends Controller
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
        
        return view('professors.submissions.syllabus.create', [
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
            'title' => 'required',
            'date' => 'required',
            'assigntask' => 'required',
            'documentdescription' => 'required'
        ]);

        $formId = DB::table('syllabi')->insertGetId([
            'department_id' => $request->input('department'),
            'title' => $request->input('title'),
            'date' => $request->input('date'),
            'assign_task' => $request->input('assigntask'),
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
                        'submission_type' => 'syllabus'
                    ]);
                }
            }
        }

        Submission::create([
            'user_id' => Auth::id(),
            'form_id' => $formId,
            'form_name' => 'syllabus',
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
    public function show(Syllabus $syllabu)
    {
        $department = Department::find($syllabu->department_id);
        $documents = Document::where('submission_id', $syllabu->id)
                        ->where('submission_type', 'syllabus')
                        ->where('deleted_at', NULL)->get();
        $submission = Submission::where('submissions.form_id', $syllabu->id)
                        ->where('submissions.form_name', 'syllabus')
                        ->join('users', 'users.id', '=', 'submissions.user_id')
                        ->select('submissions.status')->get();
                    
        return view('professors.submissions.syllabus.show', [
            'syllabus' => $syllabu,  
            'department' => $department,
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
    public function edit(Syllabus $syllabu)
    {
        $departments = Department::all();
        $documents = Document::where('submission_id', $syllabu->id)
                        ->where('submission_type', 'syllabus')
                        ->where('deleted_at', NULL)->get();

        return view('professors.submissions.syllabus.edit', [
            'syllabus' => $syllabu,
            'departments' => $departments,
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
    public function update(Request $request, Syllabus $syllabu)
    {
        $request->validate([
            'department' => 'required',
            'title' => 'required',
            'date' => 'required',
            'assigntask' => 'required',
            'documentdescription' => 'required'
        ]);

        $syllabu->update([
            'department_id' => $request->input('department'),
            'title' => $request->input('title'),
            'date' => $request->input('date'),
            'assign_task' => $request->input('assigntask'),
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
                        'submission_id' => $syllabu->id,
                        'submission_type' => 'syllabus'
                    ]);
                }
            }
        }

        Submission::where('form_name', 'syllabu')
                ->where('form_id', $syllabu->id)
                ->update(['status' => 1]);

        return redirect()->route('professor.submissions.syllabus.show', $syllabu->id)->with('success', 'Form updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Syllabus $syllabu)
    {
        Document::where('submission_id' ,$syllabu->id)
                ->where('submission_type', 'syllabus')
                ->where('deleted_at', NULL)->delete();
        Submission::where('form_id', $syllabu->id)->where('form_name', 'syllabus')->delete();
        $syllabu->delete();
        return redirect()->route('professor.submissions.index')->with('success_submission', 'Submission deleted successfully.');
    }

    public function removeFileInEdit(Syllabus $syllabu, Request $request){
        Document::where('filename', $request->input('filename'))->delete();
        Storage::delete('documents/'.$request->input('filename'));
        return redirect()->route('professor.submissions.syllabus.edit', $syllabu)->with('success', 'Document deleted successfully.');
    }
}
