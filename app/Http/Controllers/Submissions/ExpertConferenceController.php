<?php

namespace App\Http\Controllers\Submissions;

use App\Models\Level;
use App\Models\Document;
use App\Models\Department;
use App\Models\Submission;
use App\Models\RejectReason;
use Illuminate\Http\Request;
use App\Models\TemporaryFile;
use App\Models\ExpertConference;
use App\Models\ServiceConference;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
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
        $departments = Department::orderBy('name')->get();
        $serviceconferences = ServiceConference::all();
        $levels = Level::all();

        return view('professors.submissions.expertconference.create', [
            'departments' => $departments,
            'serviceconferences' => $serviceconferences,
            'levels' => $levels,
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

        $formId = DB::table('expert_conferences')->insertGetId([
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
                        'submission_id' => $formId,
                        'submission_type' => 'expertconference'
                    ]);
                }
            }
        }

        Submission::create([
            'user_id' => Auth::id(),
            'form_id' => $formId,
            'form_name' => 'expertconference',
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
    public function show(ExpertConference $expertconference)
    {
        $submission = Submission::where('submissions.form_id', $expertconference->id)
                        ->where('submissions.form_name', 'expertconference')
                        ->join('users', 'users.id', '=', 'submissions.user_id')
                        ->select('submissions.status')->get();
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

        return view('professors.submissions.expertconference.show', [
            'expertconference' => $expertconference,
            'department' => $department,
            'serviceconference' => $serviceconference,
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
    public function edit(ExpertConference $expertconference)
    {
        $submission = Submission::where('form_id', $expertconference->id)
        ->where('form_name', 'expertconference')
        ->get();

        if($submission[0]->status != 1){
            return redirect()->route('hap.review.expertconference.show', $expertconference->id)->with('error', 'Edit Submission cannot be accessed');
        }

        $departments = Department::orderBy('name')->get();
        $serviceconferences = ServiceConference::all();
        $levels = Level::all();
        $documents = Document::where('submission_id', $expertconference->id)
                ->where('submission_type', 'expertconference')
                ->where('deleted_at', NULL)->get();

        return view('professors.submissions.expertconference.edit', [
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

        return redirect()->route('professor.submissions.expertconference.show', $expertconference->id)->with('success', 'Form updated successfully.');

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
        return redirect()->route('professor.submissions.index')->with('success_submission', 'Submission deleted successfully.');
    }

    public function removeFileInEdit(ExpertConference $expertconference, Request $request){
        Document::where('filename', $request->input('filename'))->delete();
        Storage::delete('documents/'.$request->input('filename'));
        return redirect()->route('professor.submissions.expertconference.edit', $expertconference)->with('success', 'Document deleted successfully.');
    }
}
