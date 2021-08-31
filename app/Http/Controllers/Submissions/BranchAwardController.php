<?php

namespace App\Http\Controllers\Submissions;

use App\Models\Document;
use App\Models\Submission;
use App\Models\BranchAward;
use Illuminate\Http\Request;
use App\Models\TemporaryFile;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BranchAwardController extends Controller
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
        return view('professors.submissions.branchaward.create');
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
            'name' => 'required',
            'certifyingbody' => 'required',
            'place' => 'required',
            'date' => 'required',
            'level' => 'required',
            'documentdescription' => 'required'
        ]);

        $formId = DB::table('branch_awards')->insertGetId([
            'name' => $request->input('name'),
            'certifying_body' => $request->input('certifyingbody'),
            'place' => $request->input('place'), 
            'date' => $request->input('date'),
            'level'=> $request->input('level'),
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
                        'submission_type' => 'branchaward'
                    ]);
                }
            }
        }

        
        Submission::create([
            'user_id' => Auth::id(),
            'form_id' => $formId,
            'form_name' => 'branchaward',
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
    public function show(BranchAward $branchaward)
    {
        $documents = Document::where('submission_id', $branchaward->id)
        ->where('submission_type', 'branchaward')
        ->where('deleted_at', NULL)->get();

        return view('professors.submissions.branchaward.show', [
            'documents' => $documents,
            'branchaward' => $branchaward
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(BranchAward $branchaward)
    {
        $documents = Document::where('submission_id', $branchaward->id)
        ->where('submission_type', 'branchaward')
        ->where('deleted_at', NULL)->get();

        return view('professors.submissions.branchaward.edit', [
            'documents' => $documents,
            'branchaward' => $branchaward
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BranchAward $branchaward)
    {
        $request->validate([
            'name' => 'required',
            'certifyingbody' => 'required',
            'place' => 'required',
            'date' => 'required',
            'level' => 'required',
            'documentdescription' => 'required'
        ]);

        $branchaward->update([
            'name' => $request->input('name'),
            'certifying_body' => $request->input('certifyingbody'),
            'place' => $request->input('place'), 
            'date' => $request->input('date'),
            'level'=> $request->input('level'),
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
                        'submission_id' => $branchaward->id,
                        'submission_type' => 'branchaward'
                    ]);
                }
            }
        }
        return redirect()->route('professor.submissions.branchaward.show', $branchaward)->with('success', 'Form updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(BranchAward $branchaward)
    {
        Document::where('submission_id' ,$branchaward->id)
                ->where('submission_type', 'branchaward')
                ->where('deleted_at', NULL)->delete();
        Submission::where('form_id', $branchaward->id)->where('form_name', 'branchaward')->delete();
        $branchaward->delete();
        return redirect()->route('professor.submissions.index')->with('success_submission', 'Submission deleted successfully.');
    }

    public function removeFileInEdit(BranchAward $branchaward, Request $request){
        Document::where('filename', $request->input('filename'))->delete();
        Storage::delete('documents/'.$request->input('filename'));
        return redirect()->route('professor.submissions.branchaward.edit', $branchaward)->with('success', 'Document deleted successfully.');
    }

}
