<?php

namespace App\Http\Controllers\Submissions;

use App\Models\Document;
use App\Models\Department;
use App\Models\Submission;
use Illuminate\Http\Request;
use App\Models\TemporaryFile;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\SpecialTaskTimeliness;
use Illuminate\Support\Facades\Storage;

class SpecialTaskTimelinessController extends Controller
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
        $header = 'III. Special Tasks - Commitment Measurable by Timeliness > Create';
        $route = 'specialtasktimeliness';
        $departments = Department::all();

        return view('professors.submissions.specialtask.create', [
            'header' => $header,
            'route' => $route,
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
            'output' => 'required',
            'target' => 'required',
            'actual' => 'required',
            'documentdescription' => 'required',
        ]);

        $formId = DB::table('special_task_timelinesses')->insertGetId([
            'department_id' => $request->input('department'),
            'output' => $request->input('output'),
            'target' => $request->input('target'),
            'actual' => $request->input('actual'),
            'accomplishment' => $request->input('accomplishment') ?? null,
            'status' => $request->input('status')  ?? null,
            'remarks' => $request->input('remarks')  ?? null,
            'document_description'  => $request->input('documentdescription') 
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
                        'submission_type' => 'specialtasktimeliness'
                    ]);
                }
            }
        }

        Submission::create([
            'user_id' => Auth::id(),
            'form_id' => $formId,
            'form_name' => 'specialtasktimeliness',
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
    public function show(SpecialTaskTimeliness $specialtasktimeliness)
    {
        $header = 'Special Tasks';
        $route = 'specialtasktimeliness';
        $department = Department::find($specialtasktimeliness->department_id);
        $documents = Document::where('submission_id', $specialtasktimeliness->id)
                        ->where('submission_type', 'specialtasktimeliness')
                        ->where('deleted_at', NULL)
                        ->get();
        $submission = Submission::where('submissions.form_id', $specialtasktimeliness->id)
                        ->where('submissions.form_name', 'specialtasktimeliness')
                        ->join('users', 'users.id', '=', 'submissions.user_id')
                        ->select('submissions.status')->get();

        return view('professors.submissions.specialtask.show', [
            'specialtask' => $specialtasktimeliness,
            'header' => $header,
            'route' => $route,
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
    public function edit(SpecialTaskTimeliness $specialtasktimeliness)
    {
        $header = 'III. Special Tasks - Commitment Measurable by Timeliness > Edit';
        $route = 'specialtasktimeliness';
        $departments = Department::all();
        $documents = Document::where('submission_id', $specialtasktimeliness->id)
                        ->where('submission_type', 'specialtasktimeliness')
                        ->where('deleted_at', NULL)
                        ->get();

        return view('professors.submissions.specialtask.edit', [
            'specialtask' => $specialtasktimeliness,
            'header' => $header,
            'route' => $route,
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
    public function update(Request $request, SpecialTaskTimeliness $specialtasktimeliness)
    {
        $request->validate([
            'department' => 'required',
            'output' => 'required',
            'target' => 'required',
            'actual' => 'required',
            'documentdescription' => 'required',
        ]);

        $specialtasktimeliness->update([
            'department_id' => $request->input('department'),
            'output' => $request->input('output'),
            'target' => $request->input('target'),
            'actual' => $request->input('actual'),
            'accomplishment' => $request->input('accomplishment') ?? null,
            'status' => $request->input('status')  ?? null,
            'remarks' => $request->input('remarks')  ?? null,
            'document_description'  => $request->input('documentdescription') 
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
                        'submission_id' => $specialtasktimeliness->id,
                        'submission_type' => 'specialtasktimeliness'
                    ]);
                }
            }
        }

        Submission::where('form_name', 'specialtasktimeliness')
                ->where('form_id', $specialtasktimeliness->id)
                ->update(['status' => 1]);

        return redirect()->route('professor.submissions.specialtasktimeliness.show', $specialtasktimeliness)->with('success', 'Form updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(SpecialTaskTimeliness $specialtasktimeliness)
    {
        Document::where('submission_id' ,$specialtasktimeliness->id)
        ->where('submission_type', 'specialtasktimeliness')
        ->where('deleted_at', NULL)->delete();
        Submission::where('form_id', $specialtasktimeliness->id)->where('form_name', 'specialtasktimeliness')->delete();
        $specialtasktimeliness->delete();
        return redirect()->route('professor.submissions.index')->with('success_submission', 'Submission deleted successfully.');
    }

    public function removeFileInEdit(SpecialTaskTimeliness $specialtasktimeliness, Request $request){
        Document::where('filename', $request->input('filename'))->delete();
        Storage::delete('documents/'.$request->input('filename'));
        return redirect()->route('professor.submissions.specialtasktimeliness.edit', $specialtasktimeliness)->with('success', 'Document deleted successfully.');
    }
}
