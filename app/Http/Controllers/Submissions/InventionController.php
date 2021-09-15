<?php

namespace App\Http\Controllers\Submissions;

use App\Models\Document;
use App\Models\Invention;
use App\Models\Department;
use App\Models\Submission;
use App\Models\FundingType;
use App\Models\RejectReason;
use Illuminate\Http\Request;
use App\Models\TemporaryFile;
use App\Models\InventionClass;
use App\Models\InventionStatus;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class InventionController extends Controller
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
        $inventionclasses = InventionClass::all();
        $inventionstatuses = InventionStatus::all();
        $fundingtypes = FundingType::all();

        return view('professors.submissions.invention.create', [
            'departments' => $departments,
            'inventionclasses' => $inventionclasses,
            'inventionstatuses' => $inventionstatuses,
            'fundingtypes' => $fundingtypes,
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
            'inventiontitle' => 'required',
            'inventionclass' => 'required',
            'datestarted' => 'required',
            'inventionnature' => 'required',
            'inventionstatus' => 'required',
            'fundingtype' => 'required',
            'fundingamount'=> 'numeric',
            'documentdescription' => 'required'
        ]);

        if(!$request->has('present')){
            $request->validate([
                'dateended' => ['required'],
            ]);
        }

        $formId = DB::table('inventions')->insertGetId([
            'department_id' => $request->input('department'),
            'invention_title'  => $request->input('inventiontitle'),
            'invention_class_id' => $request->input('inventionclass'),
            'collaborator' => $request->input('collaborator'),
            'date_started' => $request->input('datestarted'),
            'date_ended' => $request->input('dateended') ?? null,
            'present' => $request->input('present') ?? null,
            'invention_nature' => $request->input('inventionnature'),
            'invention_status_id' => $request->input('inventionstatus'),
            'funding_agency' => $request->input('fundingagency') ?? null,
            'funding_type_id' => $request->input('fundingtype'),
            'funding_amount' => $request->input('fundingamount') ?? null,
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
                        'submission_type' => 'invention'
                    ]);
                }
            }
        }

        Submission::create([
            'user_id' => Auth::id(),
            'form_id' => $formId,
            'form_name' => 'invention',
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
    public function show(Invention $invention)
    {
        $submission = Submission::where('submissions.form_id', $invention->id)
                        ->where('submissions.form_name', 'invention')
                        ->join('users', 'users.id', '=', 'submissions.user_id')
                        ->select('submissions.status')->get();
                        
         //getting reason
         $reason = 'reason';
         if($submission[0]->status == 3){
             $reason = RejectReason::where('form_id', $invention->id)
                     ->where('form_name', 'invention')->latest()->first();
             
             if(is_null($reason)){
                 $reason = 'Your submission was rejected';
             }
         }
 

        $department = Department::find($invention->department_id);
        $inventionclass = InventionClass::find($invention->invention_class_id);
        $inventionstatus = InventionStatus::find($invention->invention_status_id);
        $fundingtype = FundingType::find($invention->funding_type_id);
        $documents = Document::where('submission_id', $invention->id)
                        ->where('submission_type', 'invention')
                        ->where('deleted_at', NULL)->get();

        return view('professors.submissions.invention.show', [
            'invention' => $invention,
            'department' => $department,
            'inventionclass' => $inventionclass,
            'inventionstatus' => $inventionstatus,
            'fundingtype' => $fundingtype,
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
    public function edit(Invention $invention)
    {

        $submission = Submission::where('submissions.form_id', $invention->id)
                    ->where('submissions.form_name', 'invention')
                    ->get();

        if($submission[0]->status != 1){
            return redirect()->route('professor.submissions.invention.show', $invention->id)->with('error', 'Edit Submission cannot be accessed');
        }


        $departments = Department::orderBy('name')->get();
        $inventionclasses = InventionClass::all();
        $inventionstatuses = InventionStatus::all();
        $fundingtypes = FundingType::all();
        $documents = Document::where('submission_id', $invention->id)
                        ->where('submission_type', 'invention')
                        ->where('deleted_at', NULL)->get();
        
        return view('professors.submissions.invention.edit', [
            'invention' => $invention,
            'departments' => $departments,
            'inventionclasses' => $inventionclasses,
            'inventionstatuses' => $inventionstatuses,
            'fundingtypes' => $fundingtypes,
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
    public function update(Request $request, Invention $invention)
    {
        $request->validate([
            'department' => 'required',
            'inventiontitle' => 'required',
            'inventionclass' => 'required',
            'datestarted' => 'required',
            'inventionnature' => 'required',
            'inventionstatus' => 'required',
            'fundingtype' => 'required',
            'fundingamount'=> 'numeric',
            'documentdescription' => 'required'
        ]);

        if(!$request->has('present')){
            $request->validate([
                'dateended' => ['required'],
            ]);
        }

        $invention->update([
            'department_id' => $request->input('department'),
            'invention_title'  => $request->input('inventiontitle'),
            'invention_class_id' => $request->input('inventionclass'),
            'collaborator' => $request->input('collaborator'),
            'date_started' => $request->input('datestarted'),
            'date_ended' => $request->input('dateended') ?? null,
            'present' => $request->input('present') ?? null,
            'invention_nature' => $request->input('inventionnature'),
            'invention_status_id' => $request->input('inventionstatus'),
            'funding_agency' => $request->input('fundingagency') ?? null,
            'funding_type_id' => $request->input('fundingtype'),
            'funding_amount' => $request->input('fundingamount') ?? null,
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
                        'submission_id' => $invention->id,
                        'submission_type' => 'invention'
                    ]);
                }
            }
        }

        Submission::where('form_name', 'invention')
                ->where('form_id', $invention->id)
                ->update(['status' => 1]);

        return redirect()->route('professor.submissions.invention.show', $invention->id)->with('success', 'Form updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invention $invention)
    {
        Document::where('submission_id' ,$invention->id)
                ->where('submission_type', 'invention')
                ->where('deleted_at', NULL)->delete();
        Submission::where('form_id', $invention->id)->where('form_name', 'invention')->delete();
        $invention->delete();
        return redirect()->route('professor.submissions.index')->with('success_submission', 'Submission deleted successfully.');
    }

    public function removeFileInEdit(Invention $invention, Request $request){
        Document::where('filename', $request->input('filename'))->delete();
        Storage::delete('documents/'.$request->input('filename'));

        $submission = Submission::where('form_id', $invention->id)
        ->where('form_name', 'invention')
        ->get();
        
        if($submission[0]->status != 1){
            return redirect()->route('professor.invention.resubmit', $invention->id)->with('success', 'Document deleted successfully.');
        }


        return redirect()->route('professor.submissions.invention.edit', $invention)->with('success', 'Document deleted successfully.');
    }

    public function resubmit(Invention $invention){
        $departments = Department::orderBy('name')->get();
        $inventionclasses = InventionClass::all();
        $inventionstatuses = InventionStatus::all();
        $fundingtypes = FundingType::all();
        $documents = Document::where('submission_id', $invention->id)
                        ->where('submission_type', 'invention')
                        ->where('deleted_at', NULL)->get();
        
        return view('professors.submissions.invention.edit', [
            'invention' => $invention,
            'departments' => $departments,
            'inventionclasses' => $inventionclasses,
            'inventionstatuses' => $inventionstatuses,
            'fundingtypes' => $fundingtypes,
            'documents' => $documents,
        ]);
    }
}
