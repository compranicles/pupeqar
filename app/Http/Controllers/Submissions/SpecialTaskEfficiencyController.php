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
use App\Models\SpecialTaskEfficiency;
use Illuminate\Support\Facades\Storage;

class SpecialTaskEfficiencyController extends Controller
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
        $header = 'III. Special Tasks - Commitment Measurable by Efficiency > Create';
        $route = 'specialtaskefficiency';
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

        $formId = DB::table('special_task_efficiencies')->insertGetId([
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
                        'submission_type' => 'specialtaskefficiency'
                    ]);
                }
            }
        }

        Submission::create([
            'user_id' => Auth::id(),
            'form_id' => $formId,
            'form_name' => 'specialtaskefficiency',
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
    public function show(SpecialTaskEfficiency $specialtaskefficiency)
    {
        $header = 'Special Tasks';
        $route = 'specialtaskefficiency';
        $department = Department::find($specialtaskefficiency->department_id);
        $documents = Document::where('submission_id', $specialtaskefficiency->id)
                        ->where('submission_type', 'specialtaskefficiency')
                        ->where('deleted_at', NULL)
                        ->get();
        $submission = Submission::where('submissions.form_id', $specialtaskefficiency->id)
                        ->where('submissions.form_name', 'specialtaskefficiency')
                        ->join('users', 'users.id', '=', 'submissions.user_id')
                        ->select('submissions.status')->get();

        return view('professors.submissions.specialtask.show', [
            'specialtask' => $specialtaskefficiency,
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
    public function edit(SpecialTaskEfficiency $specialtaskefficiency)
    {
        $header = 'III. Special Tasks - Commitment Measurable by Efficiency > Edit';
        $route = 'specialtaskefficiency';
        $departments = Department::all();
        $documents = Document::where('submission_id', $specialtaskefficiency->id)
                        ->where('submission_type', 'specialtaskefficiency')
                        ->where('deleted_at', NULL)
                        ->get();

        return view('professors.submissions.specialtask.edit', [
            'specialtask' => $specialtaskefficiency,
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
    public function update(Request $request, SpecialTaskEfficiency $specialtaskefficiency)
    {
        $request->validate([
            'department' => 'required',
            'output' => 'required',
            'target' => 'required',
            'actual' => 'required',
            'documentdescription' => 'required',
        ]);

        $specialtaskefficiency->update([
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
                        'submission_id' => $specialtaskefficiency->id,
                        'submission_type' => 'specialtaskefficiency'
                    ]);
                }
            }
        }

        Submission::where('form_name', 'specialtaskefficiency')
                ->where('form_id', $specialtaskefficiency->id)
                ->update(['status' => 1]);

        return redirect()->route('professor.submissions.specialtaskefficiency.show', $specialtaskefficiency)->with('success', 'Form updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(SpecialTaskEfficiency $specialtaskefficiency)
    {
        Document::where('submission_id' ,$specialtaskefficiency->id)
        ->where('submission_type', 'specialtaskefficiency')
        ->where('deleted_at', NULL)->delete();
        Submission::where('form_id', $specialtaskefficiency->id)->where('form_name', 'specialtaskefficiency')->delete();
        $specialtaskefficiency->delete();
        return redirect()->route('professor.submissions.index')->with('success_submission', 'Submission deleted successfully.');
    }

    public function removeFileInEdit(SpecialTaskEfficiency $specialtaskefficiency, Request $request){
        Document::where('filename', $request->input('filename'))->delete();
        Storage::delete('documents/'.$request->input('filename'));
        return redirect()->route('professor.submissions.specialtaskefficiency.edit', $specialtaskefficiency)->with('success', 'Document deleted successfully.');
    }
}
