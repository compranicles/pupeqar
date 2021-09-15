<?php

namespace App\Http\Controllers\Submissions;

use App\Models\Level;
use App\Models\Document;
use App\Models\Material;
use App\Models\Department;
use App\Models\Submission;
use App\Models\RejectReason;
use Illuminate\Http\Request;
use App\Models\TemporaryFile;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
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
        $levels = Level::all();

        return view('professors.submissions.material.create', [
            'departments' => $departments,
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
            'level' => 'required',
            'title' => 'required',
            'datestarted' => 'required',
            'datecompleted' => 'required',
            'documentdescription' => 'required'
        ]);

        $formId = DB::table('materials')->insertGetId([
            'department_id' => $request->input('department'),
            'category' => $request->input('category') ?? null,
            'level_id' => $request->input('level'),
            'title' => $request->input('title'),
            'author' => $request->input('author') ?? null,
            'editor_name' => $request->input('editorname') ?? null,
            'editor_profession' => $request->input('editorprofession') ?? null,
            'volume' => $request->input('volume') ?? null,
            'issue' => $request->input('issue') ?? null,
            'date_publication' => $request->input('datepublication') ?? null,
            'copyright' => $request->input('copyright') ?? null,
            'date_started' => $request->input('datestarted'),
            'date_completed' => $request->input('datecompleted'),
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
                        'submission_type' => 'material'
                    ]);
                }
            }
        }

        Submission::create([
            'user_id' => Auth::id(),
            'form_id' => $formId,
            'form_name' => 'material',
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
    public function show(Material $material)
    {
        $submission = Submission::where('submissions.form_id', $material->id)
                        ->where('submissions.form_name', 'material')
                        ->join('users', 'users.id', '=', 'submissions.user_id')
                        ->select('submissions.status')->get();
        
         //getting reason
        $reason = 'reason';
        if($submission[0]->status == 3){
            $reason = RejectReason::where('form_id', $material->id)
                    ->where('form_name', 'material')->latest()->first();
            
            if(is_null($reason)){
                $reason = 'Your submission was rejected';
            }
        }               

        $department = Department::find($material->department_id);
        $level = Level::find($material->level_id);
        $documents = Document::where('submission_id', $material->id)
                        ->where('submission_type', 'material')
                        ->where('deleted_at', NULL)->get();
            
        return view('professors.submissions.material.show', [
            'material' => $material,
            'department' => $department,
            'level' => $level,
            'documents' => $documents,
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
    public function edit(Material $material)
    {
        $submission = Submission::where('submissions.form_id', $material->id)
                    ->where('submissions.form_name', 'material')
                    ->get();

        if($submission[0]->status != 1){
            return redirect()->route('professor.submissions.material.show', $material->id)->with('error', 'Edit Submission cannot be accessed');
        }
            

        $departments = Department::all();
        $levels = Level::all();
        $documents = Document::where('submission_id', $material->id)
                        ->where('submission_type', 'material')
                        ->where('deleted_at', NULL)->get();

        return view('professors.submissions.material.edit', [
            'material' => $material,
            'departments' => $departments,
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
    public function update(Request $request, Material $material)
    {
        $request->validate([
            'department' => 'required',
            'level' => 'required',
            'title' => 'required',
            'datestarted' => 'required',
            'datecompleted' => 'required',
            'documentdescription' => 'required'
        ]);

        $material->update([
            'department_id' => $request->input('department'),
            'category' => $request->input('category') ?? null,
            'level_id' => $request->input('level'),
            'title' => $request->input('title'),
            'author' => $request->input('author') ?? null,
            'editor_name' => $request->input('editorname') ?? null,
            'editor_profession' => $request->input('editorprofession') ?? null,
            'volume' => $request->input('volume') ?? null,
            'issue' => $request->input('issue') ?? null,
            'date_publication' => $request->input('datepublication') ?? null,
            'copyright' => $request->input('copyright') ?? null,
            'date_started' => $request->input('datestarted'),
            'date_completed' => $request->input('datecompleted'),
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
                        'submission_id' => $material->id,
                        'submission_type' => 'material'
                    ]);
                }
            }
        }

        Submission::where('form_name', 'material')
                ->where('form_id', $material->id)
                ->update(['status' => 1]);

        return redirect()->route('professor.submissions.material.show', $material->id)->with('success', 'Form updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Material $material)
    {
        Document::where('submission_id' ,$material->id)
                ->where('submission_type', 'material')
                ->where('deleted_at', NULL)->delete();
        Submission::where('form_id', $material->id)->where('form_name', 'material')->delete();
        $material->delete();
        return redirect()->route('professor.submissions.index')->with('success_submission', 'Submission deleted successfully.');
    }

    public function removeFileInEdit(Material $material, Request $request){
        Document::where('filename', $request->input('filename'))->delete();
        Storage::delete('documents/'.$request->input('filename'));

        $submission = Submission::where('form_id', $material->id)
        ->where('form_name', 'material')
        ->get();
        
        if($submission[0]->status != 1){
            return redirect()->route('professor.material.resubmit', $material->id)->with('success', 'Document deleted successfully.');
        }


        return redirect()->route('professor.submissions.material.edit', $material)->with('success', 'Document deleted successfully.');
    }

    public function resubmit(Material $material){
        $departments = Department::all();
        $levels = Level::all();
        $documents = Document::where('submission_id', $material->id)
                        ->where('submission_type', 'material')
                        ->where('deleted_at', NULL)->get();

        return view('professors.submissions.material.edit', [
            'material' => $material,
            'departments' => $departments,
            'levels' => $levels,
            'documents' => $documents
        ]);
    }
}
