<?php

namespace App\Http\Controllers\Hap\Reviews;

use App\Models\Level;
use App\Models\Document;
use App\Models\Department;
use App\Models\Submission;
use Illuminate\Http\Request;
use App\Models\ExpertJournal;
use App\Models\IndexPlatform;
use App\Models\ServiceNature;
use App\Models\TemporaryFile;
use App\Models\ServiceJournal;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ExpertJournalController extends Controller
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
    public function show(ExpertJournal $expertjournal)
    {
        $department = Department::find($expertjournal->department_id);
        $servicejournal = ServiceJournal::find($expertjournal->service_journal_id);
        $servicenature = ServiceNature::find($expertjournal->service_nature_id);
        $indexplatform = IndexPlatform::find($expertjournal->index_platform_id);
        $level = Level::find($expertjournal->level);
        $documents = Document::where('submission_id', $expertjournal->id)
                        ->where('submission_type', 'expertjournal')
                        ->where('deleted_at', NULL)->get();

        return view('hap.review.expertjournal.show', [
            'expertjournal' => $expertjournal,
            'department' => $department,
            'servicejournal' => $servicejournal,
            'servicenature' => $servicenature,
            'indexplatform' => $indexplatform,
            'level' => $level,
            'documents' => $documents
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(ExpertJournal $expertjournal)
    {
        $departments = Department::orderBy('name')->get();
        $servicejournals = ServiceJournal::all();
        $servicenatures = ServiceNature::all();
        $indexplatforms = IndexPlatform::all();
        $levels = Level::all();
        $documents = Document::where('submission_id', $expertjournal->id)
                        ->where('submission_type', 'expertjournal')
                        ->where('deleted_at', NULL)->get();

        return view('hap.review.expertjournal.edit', [
            'expertjournal' => $expertjournal,
            'departments' => $departments,
            'servicejournals' => $servicejournals,
            'servicenatures' => $servicenatures,
            'indexplatforms' => $indexplatforms,
            'levels' => $levels,
            'documents' => $documents,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ExpertJournal $expertjournal)
    {
        $request->validate([
            'department' => 'required',
            'servicejournal' => 'required',
            'servicenature' => 'required',
            'production' => 'required',
            'indexplatform' => 'required',
            'level' => 'required',
            'documentdescription' => 'required'
        ]);

        $expertjournal->update([
            'department_id' => $request->input('department'),
            'service_journal_id' => $request->input('servicejournal'),
            'service_nature_id' => $request->input('servicenature'),
            'production' => $request->input('production'),
            'index_platform_id' => $request->input('indexplatform'),
            'isbn' => $request->input('isbn'),
            'level' => $request->input('level'),
            'document_description' => $request->input('documentdescription'),
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
                        'submission_id' => $expertjournal->id,
                        'submission_type' => 'expertjournal'
                    ]);
                }
            }
        }

        Submission::where('form_name', 'expertjournal')
                ->where('form_id', $expertjournal->id)
                ->update(['status' => 1]);

        return redirect()->route('hap.review.expertjournal.show', $expertjournal->id)->with('success', 'Form updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ExpertJournal $expertjournal)
    {
        Document::where('submission_id' ,$expertjournal->id)
                ->where('submission_type', 'expertjournal')
                ->where('deleted_at', NULL)->delete();
        Submission::where('form_id', $expertjournal->id)->where('form_name', 'expertjournal')->delete();
        $expertjournal->delete();
        return redirect()->route('hap.review.index')->with('success_submission', 'Submission deleted successfully.');
    }

    public function removeFileInEdit(ExpertJournal $expertjournal, Request $request){
        Document::where('filename', $request->input('filename'))->delete();
        Storage::delete('documents/'.$request->input('filename'));
        return redirect()->route('hap.review.expertjournal.edit', $expertjournal)->with('success', 'Document deleted successfully.');
    }
}
