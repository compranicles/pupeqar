<?php

namespace App\Http\Controllers\Hap\Reviews;

use App\Models\Level;
use App\Models\Document;
use App\Models\Department;
use App\Models\Submission;
use App\Models\FundingType;
use Illuminate\Http\Request;
use App\Models\TemporaryFile;
use App\Models\ExtensionClass;
use App\Models\ExtensionNature;
use App\Models\ExtensionStatus;
use App\Models\ExtensionProgram;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ExtensionProgramController extends Controller
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
    public function show(ExtensionProgram $extensionprogram)
    {
        $submission = Submission::where('submissions.form_id', $extensionprogram->id)
                    ->where('submissions.form_name', 'extensionprogram')
                    ->join('users', 'users.id', '=', 'submissions.user_id')
                    ->select('submissions.status', 'users.first_name', 'users.last_name', 'users.middle_name')->get();
        $department = Department::find($extensionprogram->department_id);
        $extensionnature = ExtensionNature::find($extensionprogram->extension_nature_id);
        $fundingtype = FundingType::find($extensionprogram->funding_type_id);
        $extensionclass = ExtensionClass::find($extensionprogram->extension_class_id);
        $level = Level::find($extensionprogram->level_id);
        $status = ExtensionStatus::find($extensionprogram->status_id);
        $documents = Document::where('submission_id', $extensionprogram->id)
                        ->where('submission_type', 'extensionprogram')
                        ->where('deleted_at', NULL)->get();

        return view('hap.review.extensionprogram.show', [
            'submission' => $submission[0],
            'extensionprogram' => $extensionprogram,
            'department' => $department,
            'extensionnature' => $extensionnature,
            'fundingtype' => $fundingtype,
            'extensionclass' => $extensionclass,
            'level' => $level,
            'status' => $status,
            'documents' => $documents,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(ExtensionProgram $extensionprogram)
    {
        $submission = Submission::where('submissions.form_id', $extensionprogram->id)
                    ->where('submissions.form_name', 'extensionprogram')
                    ->join('users', 'users.id', '=', 'submissions.user_id')
                    ->select('submissions.status', 'users.first_name', 'users.last_name', 'users.middle_name')->get();

        if($submission[0]->status != 1){
            return redirect()->route('hap.review.extensionprogram.show', $extensionprogram->id)->with('error', 'Edit Submission cannot be accessed');
        }

        $departments = Department::orderBy('name')->get();
        $extensionnatures = ExtensionNature::all();
        $fundingtypes = FundingType::all();
        $extensionclasses = ExtensionClass::all();
        $levels = Level::all();
        $statuses = ExtensionStatus::all();
        $documents = Document::where('submission_id', $extensionprogram->id)
        ->where('submission_type', 'extensionprogram')
        ->where('deleted_at', NULL)->get();

        return view('hap.review.extensionprogram.edit', [
            'submission' => $submission[0],
            'extensionprogram' => $extensionprogram,
            'departments' => $departments,
            'extensionnatures' => $extensionnatures,
            'fundingtypes' => $fundingtypes,
            'extensionclasses' => $extensionclasses,
            'levels' => $levels,
            'statuses' => $statuses,
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
    public function update(Request $request, ExtensionProgram $extensionprogram)
    {
        $request->validate([
            'department' => 'required',
            'extensionnature' => 'required',
            'fundingtype' => 'required',
            'fundingamount' => 'numeric',
            'extensionclass' => 'required',
            'level' => 'required',
            'datestarted' => 'required',
            'status' => 'required',
            'trainees' => 'numeric',
            'hours' => 'required',
            'documentdescription' => 'required'
        ]);

        if(!$request->has('present')){
            $request->validate([
                'dateended' => ['required'],
            ]);
        }

        $extensionprogram->update([
            'department_id' => $request->input('department'),
            'program' => $request->input('programtitle') ?? null,
            'project' => $request->input('projecttitle') ?? null,
            'activity' => $request->input('activitytitle') ?? null,
            'extension_nature_id' => $request->input('extensionnature'),
            'funding_type_id' => $request->input('fundingtype'),
            'funding_amount' => $request->input('fundingamount') ?? null,
            'extension_class_id' => $request->input('extensionclass'),
            'others' => $request->input('others')?? null,
            'level_id' => $request->input('level'), 
            'date_started' => $request->input('datestarted'),
            'date_ended' => $request->input('dateended') ?? null,
            'present' => $request->input('present')?? null,
            'status_id' => $request->input('status'),
            'venue' => $request->input('venue') ?? null,
            'trainees' => $request->input('trainees') ?? null,
            'trainees_class' => $request->input('traineesclass') ?? null,
            'quality_poor' => $request->input('qualitypoor'),
            'quality_fair' => $request->input('qualityfair'),
            'quality_satisfactory' => $request->input('qualitysatisfactory'),
            'quality_vsatisfactory' => $request->input('qualityvsatisfactory'),
            'quality_outstanding'  => $request->input('qualityoutstanding'),
            'timeliness_poor' => $request->input('timelinesspoor'),
            'timeliness_fair' => $request->input('timelinessfair'),
            'timeliness_satisfactory' => $request->input('timelinesssatisfactory'),
            'timeliness_vsatisfactory' => $request->input('timelinessvsatisfactory'),
            'timeliness_outstanding' => $request->input('timelinessoutstanding'),
            'hours' => $request->input('hours'),
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
                        'submission_id' => $extensionprogram->id,
                        'submission_type' => 'extensionprogram'
                    ]);
                }
            }
        }


        Submission::where('form_name', 'extensionprogram')
                ->where('form_id', $extensionprogram->id)
                ->update(['status' => 1]);


        return redirect()->route('hap.review.extensionprogram.show', $extensionprogram->id)->with('success', 'Form updated successfully.');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ExtensionProgram $extensionprogram)
    {
        Document::where('submission_id' ,$extensionprogram->id)
                ->where('submission_type', 'extensionprogram')
                ->where('deleted_at', NULL)->delete();
        Submission::where('form_id', $extensionprogram->id)->where('form_name', 'extensionprogram')->delete();
        $extensionprogram->delete();
        return redirect()->route('hap.review.index')->with('success_submission', 'Submission deleted successfully.');
    }

    public function removeFileInEdit(ExtensionProgram $extensionprogram, Request $request){
        Document::where('filename', $request->input('filename'))->delete();
        Storage::delete('documents/'.$request->input('filename'));
        return redirect()->route('hap.review.extensionprogram.edit', $extensionprogram)->with('success', 'Document deleted successfully.');
    }
}
