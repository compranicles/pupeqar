<?php

namespace App\Http\Controllers\Hap\Reviews;

use App\Models\Level;
use App\Models\Document;
use App\Models\Department;
use App\Models\Submission;
use Illuminate\Http\Request;
use App\Models\TemporaryFile;
use App\Models\ExpertConsultant;
use App\Models\ServiceConsultant;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ExpertConsultantController extends Controller
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
    public function show(ExpertConsultant $expertconsultant)
    {
        $submission = Submission::where('submissions.form_id', $expertconsultant->id)
                    ->where('submissions.form_name', 'expertconsultant')
                    ->join('users', 'users.id', '=', 'submissions.user_id')
                    ->select('submissions.status', 'users.first_name', 'users.last_name', 'users.middle_name')->get();
        $department = Department::find($expertconsultant->department_id);
        $serviceconsultant = ServiceConsultant::find($expertconsultant->service_consultant_id);
        $level = Level::find($expertconsultant->level_id);
        $documents = Document::where('submission_id', $expertconsultant->id)
                        ->where('submission_type', 'expertconsultant')
                        ->where('deleted_at', NULL)->get();

        return view('hap.review.expertconsultant.show', [
            'submission' => $submission[0],
            'expertconsultant' => $expertconsultant,
            'department' => $department,
            'serviceconsultant' => $serviceconsultant,
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
    public function edit(ExpertConsultant $expertconsultant)
    {
        $submission = Submission::where('submissions.form_id', $expertconsultant->id)
                    ->where('submissions.form_name', 'expertconsultant')
                    ->join('users', 'users.id', '=', 'submissions.user_id')
                    ->select('submissions.status', 'users.first_name', 'users.last_name', 'users.middle_name')->get();

        if($submission[0]->status != 1){
            return redirect()->route('hap.review.expertconsultant.show', $expertconsultant->id)->with('error', 'Edit Submission cannot be accessed');
        }

        $departments = Department::orderBy('name')->get();
        $serviceconsultants = ServiceConsultant::all();
        $levels = Level::all();
        $documents = Document::where('submission_id', $expertconsultant->id)
                ->where('submission_type', 'expertconsultant')
                ->where('deleted_at', NULL)->get();

        return view('hap.review.expertconsultant.edit', [
            'submission' => $submission[0],
            'expertconsultant' => $expertconsultant,
            'departments' => $departments,
            'serviceconsultants' => $serviceconsultants,
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
    public function update(Request $request, ExpertConsultant $expertconsultant)
    {
        $request->validate([
            'department' => 'required',
            'serviceconsultant' => 'required',
            'servicetitle' => 'required',
            'datestarted' => 'required',
            'level' => 'required',
            'documentdescription' => 'required'
        ]);

        if(!$request->has('present')){
            $request->validate([
                'dateended' => ['required'],
            ]);
        }

        $expertconsultant->update([
            'department_id' => $request->input('department'),
            'service_consultant_id' => $request->input('serviceconsultant'),
            'service_title' => $request->input('servicetitle'),
            'service_category' => $request->input('servicecategory') ?? null,
            'partner_agency' => $request->input('partneragency') ?? null,
            'venue' => $request->input('venue') ?? null,
            'date_started' => $request->input('datestarted'),
            'date_ended' => $request->input('dateended') ?? null,
            'present' => $request->input('present') ?? null,
            'level_id' => $request->input('level'),
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
                        'submission_id' => $expertconsultant->id,
                        'submission_type' => 'expertconsultant'
                    ]);
                }
            }
        }

        Submission::where('form_name', 'expertconsultant')
                ->where('form_id', $expertconsultant->id)
                ->update(['status' => 1]);
        
        return redirect()->route('hap.review.expertconsultant.show', $expertconsultant->id)->with('success', 'Form updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ExpertConsultant $expertconsultant)
    {
        Document::where('submission_id' ,$expertconsultant->id)
                ->where('submission_type', 'expertconsultant')
                ->where('deleted_at', NULL)->delete();
        Submission::where('form_id', $expertconsultant->id)->where('form_name', 'expertconsultant')->delete();
        $expertconsultant->delete();
        return redirect()->route('hap.review.index')->with('success_submission', 'Submission deleted successfully.');
    }

    public function removeFileInEdit(ExpertConsultant $expertconsultant, Request $request){
        Document::where('filename', $request->input('filename'))->delete();
        Storage::delete('documents/'.$request->input('filename'));
        return redirect()->route('hap.review.expertconsultant.edit', $expertconsultant)->with('success', 'Document deleted successfully.');
    }
}
