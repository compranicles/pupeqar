<?php

namespace App\Http\Controllers\Submissions;

use App\Models\Document;
use App\Models\Department;
use App\Models\Submission;
use App\Models\EngageNature;
use App\Models\RejectReason;
use Illuminate\Http\Request;
use App\Models\TemporaryFile;
use App\Models\FacultyInvolve;
use Illuminate\Support\Facades\DB;
use App\Models\FacultyInterCountry;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FacultyInterCountryController extends Controller
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
        $engagementnatures = EngageNature::all();
        $facultyinvolvements = FacultyInvolve::all();

        return view('professors.submissions.facultyintercountry.create', [
            'departments' => $departments,
            'engagementnatures' => $engagementnatures,
            'facultyinvolvements' => $facultyinvolvements
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
            'engagementnature' => 'required',
            'facultyinvolvement' => 'required',
            'hostname' => 'required',
            'hostaddress' => 'required',
            'datestarted' => 'required',
            'dateended' => 'required',
            'documentdescription' => 'required',
        ]);

        $formId = DB::table('faculty_inter_countries')->insertGetId([
            'department_id' => $request->input('department'),
            'engagement_nature_id'  => $request->input('engagementnature'),
            'faculty_involvement_id'  => $request->input('facultyinvolvement'),
            'host_name' => $request->input('hostname'),
            'host_address' => $request->input('hostaddress'),
            'host_type' => $request->input('hosttype'),
            'date_started' => $request->input('datestarted'),
            'date_ended' => $request->input('dateended'),
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
                        'submission_type' => 'facultyintercountry'
                    ]);
                }
            }
        }
        
        Submission::create([
            'user_id' => Auth::id(),
            'form_id' => $formId,
            'form_name' => 'facultyintercountry',
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
    public function show(FacultyInterCountry $facultyintercountry)
    {
        $submission = Submission::where('submissions.form_id', $facultyintercountry->id)
                        ->where('submissions.form_name', 'facultyintercountry')
                        ->join('users', 'users.id', '=', 'submissions.user_id')
                        ->select('submissions.status')->get();
         //getting reason
         $reason = 'reason';
         if($submission[0]->status == 3){
             $reason = RejectReason::where('form_id', $facultyintercountry->id)
                     ->where('form_name', 'facultyintercountry')->first();
             
             if(is_null($reason)){
                 $reason = 'Your submission was rejected';
             }
         }
        $department = Department::find($facultyintercountry->department_id);
        $engagementnature = EngageNature::find($facultyintercountry->engagement_nature_id);
        $facultyinvolvement = FacultyInvolve::find($facultyintercountry->faculty_involvement_id);
        $documents = Document::where('submission_id', $facultyintercountry->id)
                        ->where('submission_type', 'facultyintercountry')
                        ->where('deleted_at', NULL)->get();


        return view('professors.submissions.facultyintercountry.show', [
            'facultyintercountry' => $facultyintercountry,
            'department' => $department,
            'engagementnature' => $engagementnature,
            'facultyinvolvement' => $facultyinvolvement,
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
    public function edit(FacultyInterCountry $facultyintercountry)
    {
        $submission = Submission::where('submissions.form_id', $facultyintercountry->id)
                    ->where('submissions.form_name', 'facultyintercountry')
                    ->get();

        if($submission[0]->status != 1){
            return redirect()->route('hap.review.facultyintercountry.show', $facultyintercountry->id)->with('error', 'Edit Submission cannot be accessed');
        }

        $departments = Department::orderBy('name')->get();
        $engagementnatures = EngageNature::all();
        $facultyinvolvements = FacultyInvolve::all();
        $documents = Document::where('submission_id', $facultyintercountry->id)
            ->where('submission_type', 'facultyintercountry')
            ->where('deleted_at', NULL)->get();

        return view('professors.submissions.facultyintercountry.edit', [
            'facultyintercountry' => $facultyintercountry,
            'departments' => $departments,
            'engagementnatures' => $engagementnatures,
            'facultyinvolvements' => $facultyinvolvements,
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
    public function update(Request $request, FacultyInterCountry $facultyintercountry)
    {
        $request->validate([
            'department' => 'required',
            'engagementnature' => 'required',
            'facultyinvolvement' => 'required',
            'hostname' => 'required',
            'hostaddress' => 'required',
            'datestarted' => 'required',
            'dateended' => 'required',
            'documentdescription' => 'required',
        ]);

        $facultyintercountry->update([
            'department_id' => $request->input('department'),
            'engagement_nature_id'  => $request->input('engagementnature'),
            'faculty_involvement_id'  => $request->input('facultyinvolvement'),
            'host_name' => $request->input('hostname'),
            'host_address' => $request->input('hostaddress'),
            'host_type' => $request->input('hosttype'),
            'date_started' => $request->input('datestarted'),
            'date_ended' => $request->input('dateended'),
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
                        'submission_id' => $facultyintercountry->id,
                        'submission_type' => 'facultyintercountry'
                    ]);
                }
            }
        }

        Submission::where('form_name', 'facultyintercountry')
                ->where('form_id', $facultyintercountry->id)
                ->update(['status' => 1]);


        return redirect()->route('professor.submissions.facultyintercountry.show', $facultyintercountry->id)->with('success', 'Form updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(FacultyInterCountry $facultyintercountry)
    {
        Document::where('submission_id' ,$facultyintercountry->id)
                ->where('submission_type', 'facultyintercountry')
                ->where('deleted_at', NULL)->delete();
        Submission::where('form_id', $facultyintercountry->id)->where('form_name', 'facultyintercountry')->delete();
        $facultyintercountry->delete();
        return redirect()->route('professor.submissions.index')->with('success_submission', 'Submission deleted successfully.');
    }

    public function removeFileInEdit(FacultyInterCountry $facultyintercountry, Request $request){
        Document::where('filename', $request->input('filename'))->delete();
        Storage::delete('documents/'.$request->input('filename'));
        return redirect()->route('professor.submissions.facultyintercountry.edit', $facultyintercountry)->with('success', 'Document deleted successfully.');
    }
}
