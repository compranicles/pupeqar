<?php

namespace App\Http\Controllers\Hap\Reviews;

use App\Models\Document;
use App\Models\AccreLevel;
use App\Models\Department;
use App\Models\Submission;
use App\Models\StudyStatus;
use App\Models\SupportType;
use App\Models\RejectReason;
use Illuminate\Http\Request;
use App\Models\TemporaryFile;
use App\Models\OngoingAdvanced;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class OngoingAdvancedController extends Controller
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
    public function show(OngoingAdvanced $ongoingadvanced)
    {
        $submission = Submission::where('submissions.form_id', $ongoingadvanced->id)
                    ->where('submissions.form_name', 'ongoingadvanced')
                    ->join('users', 'users.id', '=', 'submissions.user_id')
                    ->select('submissions.status', 'users.first_name', 'users.last_name', 'users.middle_name')->get();

        //getting reason
        $reason = 'reason';
        if($submission[0]->status == 3){
            $reason = RejectReason::where('form_id', $ongoingadvanced->id)
                    ->where('form_name', 'ongoingadvanced')->first();
            
            if(is_null($reason)){
                $reason = 'Your submission was rejected';
            }
        }
        
        $department = Department::find($ongoingadvanced->department_id);
        $accrelevel = AccreLevel::find($ongoingadvanced->accre_level_id);
        $supporttype = SupportType::find($ongoingadvanced->support_type_id);
        $studystatus = StudyStatus::find($ongoingadvanced->study_status_id);
        $documents = Document::where('submission_id' ,$ongoingadvanced->id)
                        ->where('submission_type', 'ongoingadvanced')
                        ->where('deleted_at', NULL)->get();

        return view('hap.review.ongoingadvanced.show', [
            'submission' => $submission[0],
            'ongoingadvanced' => $ongoingadvanced,
            'department' => $department,
            'accrelevel' => $accrelevel,
            'supporttype' => $supporttype,
            'studystatus' => $studystatus,
            'documents' => $documents,
            'reason' => $reason
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(OngoingAdvanced $ongoingadvanced)
    {
        $submission = Submission::where('submissions.form_id', $ongoingadvanced->id)
                    ->where('submissions.form_name', 'ongoingadvanced')
                    ->join('users', 'users.id', '=', 'submissions.user_id')
                    ->select('submissions.status', 'users.first_name', 'users.last_name', 'users.middle_name')->get();

        if($submission[0]->status != 1){
            return redirect()->route('hap.review.ongoingadvanced.show', $ongoingadvanced->id)->with('error', 'Edit Submission cannot be accessed');
        }

        $departments = Department::orderBy('name')->get();
        $accrelevels = AccreLevel::all();
        $supporttypes = SupportType::all();
        $studystatuses = StudyStatus::all();
        $documents = Document::where('submission_id' ,$ongoingadvanced->id)
                        ->where('submission_type', 'ongoingadvanced')
                        ->where('deleted_at', NULL)->get();
        return view('hap.review.ongoingadvanced.edit', [
            'submission' => $submission[0],
            'ongoingadvanced' => $ongoingadvanced,
            'departments' => $departments,
            'accrelevels' => $accrelevels,
            'supporttypes' => $supporttypes,
            'studystatuses' => $studystatuses,
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
    public function update(Request $request, OngoingAdvanced $ongoingadvanced)
    {
        $request->validate([
            'department' => ['required'],
            'degree' => ['required'],
            'school' => ['required'],
            'accrelevel' => ['required'],
            'supporttype' => ['required'],
            'sponsor' => ['required'],
            'amount' => ['required', 'numeric'],
            'date_started' => ['required'],
            'studystatus' => ['required'],
            'unitsearned' => ['numeric'],
            'unitsenrolled' => ['numeric'],
            'document' => ['required'],
            'documentdescription' => ['required']
        ]);

        if(!$request->has('present')){
            $request->validate([
                'date_ended' => ['required'],
            ]);
        }

        //Update the row
        $ongoingadvanced->update([
            'department_id' => $request->input('department'),
            'degree' => $request->input('degree'),
            'school' => $request->input('school'),
            'accre_level_id' => $request->input('accrelevel') ?? null,
            'support_type_id' => $request->input('supporttype') ?? null,
            'sponsor' => $request->input('sponsor'),
            'amount' => $request->input('amount'),
            'date_started' => $request->input('date_started'),
            'date_ended' => $request->input('date_ended')?? null,
            'present' => $request->input('present'),
            'study_status_id' => $request->input('studystatus') ?? null,
            'units_earned' => $request->input('unitsearned'),
            'units_enrolled' => $request->input('unitsenrolled'),
            'document_description' => $request->input('documentdescription')
        ]);

        //Save the documents uploaded using ongoingadvanced variable
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
                        'submission_id' => $ongoingadvanced->id,
                        'submission_type' => 'ongoingadvanced'
                    ]);
                }
            }
        }

        Submission::where('form_name', 'ongoingadvanced')
                ->where('form_id', $ongoingadvanced->id)
                ->update(['status' => 1]);

        return redirect()->route('hap.review.ongoingadvanced.show', $ongoingadvanced->id)->with('success', 'Form updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(OngoingAdvanced $ongoingadvanced)
    {
        Document::where('submission_id' ,$ongoingadvanced->id)
                ->where('submission_type', 'ongoingadvanced')
                ->where('deleted_at', NULL)->delete();
        Submission::where('form_id', $ongoingadvanced->id)->where('form_name', 'ongoingadvanced')->delete();
        $ongoingadvanced->delete();
        return redirect()->route('hap.review.index')->with('success_submission', 'Submission deleted successfully.');
    }


    public function removeFileInEdit(OngoingAdvanced $ongoingadvanced, Request $request){
        Document::where('filename', $request->input('filename'))->delete();
        Storage::delete('documents/'.$request->input('filename'));
        return redirect()->route('hap.review.ongoingadvanced.edit', $ongoingadvanced)->with('success', 'Document deleted successfully.');
    }
}
