<?php

namespace App\Http\Controllers\Submissions;

use App\Models\Document;
use App\Models\Submission;
use App\Models\RejectReason;
use Illuminate\Http\Request;
use App\Models\TemporaryFile;
use App\Models\ViableProject;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ViableProjectController extends Controller
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
        return view('professors.submissions.viableproject.create');
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
            'revenue' => ['required', 'numeric'],
            'cost' => ['required', 'numeric'],
            'datestarted' => 'required',
            'rate' => ['required', 'numeric'],
            'documentdescription' => 'required'
        ]);

        $formId = DB::table('viable_projects')->insertGetId([
            'name' => $request->input('name'),
            'revenue' => $request->input('revenue'),
            'cost' => $request->input('cost'),
            'date_started' => $request->input('datestarted'),
            'rate'  => $request->input('rate'),
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
                        'submission_type' => 'viableproject'
                    ]);
                }
            }
        }

        Submission::create([
            'user_id' => Auth::id(),
            'form_id' => $formId,
            'form_name' => 'viableproject',
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
    public function show(ViableProject $viableproject)
    {
        $documents = Document::where('submission_id', $viableproject->id)
                            ->where('submission_type', 'viableproject')
                            ->where('deleted_at', NULL)->get();
        $submission = Submission::where('submissions.form_id', $viableproject->id)
                            ->where('submissions.form_name', 'viableproject')
                            ->join('users', 'users.id', '=', 'submissions.user_id')
                            ->select('submissions.status')->get();
        
        
        //getting reason
        $reason = 'reason';
        if($submission[0]->status == 3){
            $reason = RejectReason::where('form_id', $viableproject->id)
                    ->where('form_name', 'viableproject')->latest()->first();
            
            if(is_null($reason)){
                $reason = 'Your submission was rejected';
            }
        }


        return view('professors.submissions.viableproject.show', [
            'documents' => $documents,
            'viableproject' => $viableproject,
            'submission' => $submission[0],
            'reason' => $reason,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(ViableProject $viableproject)
    {
        $submission = Submission::where('submissions.form_id', $viableproject->id)
        ->where('submissions.form_name', 'viableproject')
        ->get();

        if($submission[0]->status != 1){
            return redirect()->route('professor.submissions.viableproject.show', $viableproject->id)->with('error', 'Edit Submission cannot be accessed');
        }
        
        $documents = Document::where('submission_id', $viableproject->id)
        ->where('submission_type', 'viableproject')
        ->where('deleted_at', NULL)->get();

        return view('professors.submissions.viableproject.edit', [
            'documents' => $documents,
            'viableproject' => $viableproject
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ViableProject $viableproject)
    {
        $request->validate([
            'name' => 'required',
            'revenue' => ['required', 'numeric'],
            'cost' => ['required', 'numeric'],
            'datestarted' => 'required',
            'rate' => ['required', 'numeric'],
            'documentdescription' => 'required'
        ]);

        $viableproject->update([
            'name' => $request->input('name'),
            'revenue' => $request->input('revenue'),
            'cost' => $request->input('cost'),
            'date_started' => $request->input('datestarted'),
            'rate'  => $request->input('rate'),
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
                        'submission_id' => $viableproject->id,
                        'submission_type' => 'viableproject'
                    ]);
                }
            }
        }

        Submission::where('form_name', 'viableproject')
                ->where('form_id', $viableproject->id)
                ->update(['status' => 1]);

        return redirect()->route('professor.submissions.viableproject.show', $viableproject->id)->with('success', 'Form updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ViableProject $viableproject)
    {
        Document::where('submission_id' ,$viableproject->id)
                ->where('submission_type', 'viableproject')
                ->where('deleted_at', NULL)->delete();
        Submission::where('form_id', $viableproject->id)->where('form_name', 'viableproject')->delete();
        $viableproject->delete();
        return redirect()->route('professor.submissions.index')->with('success_submission', 'Submission deleted successfully.');
    }

    public function removeFileInEdit(ViableProject $viableproject, Request $request){
        Document::where('filename', $request->input('filename'))->delete();
        Storage::delete('documents/'.$request->input('filename'));
        return redirect()->route('professor.submissions.viableproject.edit', $viableproject)->with('success', 'Document deleted successfully.');
    }

    public function resubmit(ViableProject $viableproject){
        $documents = Document::where('submission_id', $viableproject->id)
        ->where('submission_type', 'viableproject')
        ->where('deleted_at', NULL)->get();

        return view('professors.submissions.viableproject.edit', [
            'documents' => $documents,
            'viableproject' => $viableproject
        ]);
    }
}
