<?php

namespace App\Http\Controllers\Hap\Reviews;

use App\Models\Level;
use App\Models\Document;
use App\Models\Department;
use App\Models\Submission;
use App\Models\RejectReason;
use Illuminate\Http\Request;
use App\Models\TemporaryFile;
use App\Models\ExpertConference;
use App\Models\ServiceConference;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ExpertConferenceController extends Controller
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
    public function show(ExpertConference $expertconference)
    {
        $submission = Submission::where('submissions.form_id', $expertconference->id)
                    ->where('submissions.form_name', 'expertconference')
                    ->join('users', 'users.id', '=', 'submissions.user_id')
                    ->select('submissions.status', 'users.first_name', 'users.last_name', 'users.middle_name')->get();

         //getting reason
         $reason = 'reason';
         if($submission[0]->status == 3){
             $reason = RejectReason::where('form_id', $expertconference->id)
                     ->where('form_name', 'expertconference')->first();
             
             if(is_null($reason)){
                 $reason = 'Your submission was rejected';
             }
         }

        $department = Department::find($expertconference->department_id);
        $serviceconference = ServiceConference::find($expertconference->service_conference_id);
        $level = Level::find($expertconference->level_id);
        $documents = Document::where('submission_id', $expertconference->id)
                        ->where('submission_type', 'expertconference')
                        ->where('deleted_at', NULL)->get();

        return view('hap.review.expertconference.show', [
            'submission' => $submission[0],
            'expertconference' => $expertconference,
            'department' => $department,
            'serviceconference' => $serviceconference,
            'level' => $level,
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
    public function edit(ExpertConference $expertconference)
    {
        $submission = Submission::where('submissions.form_id', $expertconference->id)
                    ->where('submissions.form_name', 'expertconference')
                    ->join('users', 'users.id', '=', 'submissions.user_id')
                    ->select('submissions.status', 'users.first_name', 'users.last_name', 'users.middle_name')->get();

        if($submission[0]->status != 1){
            return redirect()->route('hap.review.expertconference.show', $expertconference->id)->with('error', 'Edit Submission cannot be accessed');
        }
        
        $departments = Department::orderBy('name')->get();
        $serviceconferences = ServiceConference::all();
        $levels = Level::all();
        $documents = Document::where('submission_id', $expertconference->id)
                ->where('submission_type', 'expertconference')
                ->where('deleted_at', NULL)->get();

        return view('hap.review.expertconference.edit', [
            'submission' => $submission[0],
            'expertconference' => $expertconference,
            'departments' => $departments,
            'serviceconferences' => $serviceconferences,
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
    public function update(Request $request, ExpertConference $expertconference)
    {
        $request->validate([
            'department' => 'required',
            'serviceconference' => 'required',
            'conferencetitle' => 'required',
            'datestarted' => 'required',
            'level' => 'required',
            'documentdescription' => 'required'
        ]);

        if(!$request->has('present')){
            $request->validate([
                'dateended' => ['required'],
            ]);
        }

        $expertconference->update([
            'department_id' => $request->input('department'),
            'service_conference_id' => $request->input('serviceconference'),
            'conference_title' => $request->input('conferencetitle'),
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
                        'submission_id' => $expertconference->id,
                        'submission_type' => 'expertconference'
                    ]);
                }
            }
        }

        Submission::where('form_name', 'expertconference')
                ->where('form_id', $expertconference->id)
                ->update(['status' => 1]);

        return redirect()->route('hap.review.expertconference.show', $expertconference->id)->with('success', 'Form updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ExpertConference $expertconference)
    {
        Document::where('submission_id' ,$expertconference->id)
                ->where('submission_type', 'expertconference')
                ->where('deleted_at', NULL)->delete();
        Submission::where('form_id', $expertconference->id)->where('form_name', 'expertconference')->delete();
        $expertconference->delete();
        return redirect()->route('hap.review.index')->with('success_submission', 'Submission deleted successfully.');
    }

    public function removeFileInEdit(ExpertConference $expertconference, Request $request){
        Document::where('filename', $request->input('filename'))->delete();
        Storage::delete('documents/'.$request->input('filename'));
        return redirect()->route('hap.review.expertconference.edit', $expertconference)->with('success', 'Document deleted successfully.');
    }
}
