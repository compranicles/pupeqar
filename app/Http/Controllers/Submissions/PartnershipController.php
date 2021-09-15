<?php

namespace App\Http\Controllers\Submissions;

use App\Models\Level;
use App\Models\Document;
use App\Models\Department;
use App\Models\Submission;
use App\Models\Partnership;
use App\Models\PartnerType;
use App\Models\CollabNature;
use App\Models\RejectReason;
use Illuminate\Http\Request;
use App\Models\CollabDeliver;
use App\Models\TemporaryFile;
use App\Models\TargetBeneficiary;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PartnershipController extends Controller
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
        $partnertypes = PartnerType::all();
        $collabnatures = CollabNature::all();
        $collabdelivers = CollabDeliver::all();
        $targetbeneficiaries = TargetBeneficiary::all();
        $levels = Level::all();

        return view('professors.submissions.partnership.create', [
            'departments' => $departments,
            'partnertypes' => $partnertypes,
            'collabnatures' => $collabnatures,
            'collabdelivers' => $collabdelivers,
            'targetbeneficiaries' => $targetbeneficiaries,
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
            'title' => 'required',
            'partnertype' => 'required',
            'collabnature' => 'required',
            'collabdeliver' => 'required',
            'targetbeneficiary' => 'required',
            'level' => 'required',
            'datestarted' =>  'required',
            'contactname' => 'required',
            'contactnumber' => 'required',
            'contactaddress' => 'required',
            'documentdescription' => 'required'
        ]);
        if(!$request->has('present')){
            $request->validate([
                'dateended' => ['required'],
            ]);
        }

        $formId = DB::table('partnerships')->insertGetId([
            'department_id' => $request->input('department'),
            'title' => $request->input('title'),
            'partner_type_id' => $request->input('partnertype'),
            'collab_nature_id' => $request->input('collabnature'),
            'collab_deliver_id' => $request->input('collabdeliver'),
            'target_beneficiary_id' => $request->input('targetbeneficiary'),
            'level_id' => $request->input('level'),
            'date_started' => $request->input('datestarted'),
            'date_ended' => $request->input('dateended') ?? null,
            'present' => $request->input('present') ?? null,
            'contact_name' => $request->input('contactname'),
            'contact_number' => $request->input('contactnumber'),
            'contact_address' => $request->input('contactaddress'),
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
                        'submission_type' => 'partnership'
                    ]);
                }
            }
        }

        Submission::create([
            'user_id' => Auth::id(),
            'form_id' => $formId,
            'form_name' => 'partnership',
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
    public function show(Partnership $partnership)
    {
        $department = Department::find($partnership->department_id);
        $partnertype = PartnerType::find($partnership->partner_type_id);
        $collabnature = CollabNature::find($partnership->collab_nature_id);
        $collabdeliver = CollabDeliver::find($partnership->collab_deliver_id);
        $targetbeneficiary = TargetBeneficiary::find($partnership->target_beneficiary_id);
        $level = Level::find($partnership->level_id);
        $documents = Document::where('submission_id', $partnership->id)
                        ->where('submission_type', 'partnership')
                        ->where('deleted_at', NULL)->get();
        $submission = Submission::where('submissions.form_id', $partnership->id)
                        ->where('submissions.form_name', 'partnership')
                        ->join('users', 'users.id', '=', 'submissions.user_id')
                        ->select('submissions.status')->get();
        //getting reason
        $reason = 'reason';
        if($submission[0]->status == 3){
            $reason = RejectReason::where('form_id', $partnership->id)
                    ->where('form_name', 'partnership')->latest()->first();
            
            if(is_null($reason)){
                $reason = 'Your submission was rejected';
            }
        }

        return view('professors.submissions.partnership.show', [
            'partnership' => $partnership,
            'department' => $department,
            'partnertype' => $partnertype,
            'collabnature' => $collabnature,
            'collabdeliver' => $collabdeliver,
            'targetbeneficiary' => $targetbeneficiary,
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
    public function edit(Partnership $partnership)
    {
        $submission = Submission::where('submissions.form_id', $partnership->id)
        ->where('submissions.form_name', 'partnership')
        ->get();

        if($submission[0]->status != 1){
            return redirect()->route('professor.submissions.partnership.show', $partnership->id)->with('error', 'Edit Submission cannot be accessed');
        }

        $departments = Department::orderBy('name')->get();
        $partnertypes = PartnerType::all();
        $collabnatures = CollabNature::all();
        $collabdelivers = CollabDeliver::all();
        $targetbeneficiaries = TargetBeneficiary::all();
        $levels = Level::all();
        $documents = Document::where('submission_id', $partnership->id)
                        ->where('submission_type', 'partnership')
                        ->where('deleted_at', NULL)->get();

        return view('professors.submissions.partnership.edit', [
            'partnership' => $partnership,
            'departments' => $departments,
            'partnertypes' => $partnertypes,
            'collabnatures' => $collabnatures,
            'collabdelivers' => $collabdelivers,
            'targetbeneficiaries' => $targetbeneficiaries,
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
    public function update(Request $request, Partnership $partnership)
    {
        $request->validate([
            'department' => 'required',
            'title' => 'required',
            'partnertype' => 'required',
            'collabnature' => 'required',
            'collabdeliver' => 'required',
            'targetbeneficiary' => 'required',
            'level' => 'required',
            'datestarted' =>  'required',
            'contactname' => 'required',
            'contactnumber' => 'required',
            'contactaddress' => 'required',
            'documentdescription' => 'required'
        ]);
        if(!$request->has('present')){
            $request->validate([
                'dateended' => ['required'],
            ]);
        }

        $partnership->update([
            'department_id' => $request->input('department'),
            'title' => $request->input('title'),
            'partner_type_id' => $request->input('partnertype'),
            'collab_nature_id' => $request->input('collabnature'),
            'collab_deliver_id' => $request->input('collabdeliver'),
            'target_beneficiary_id' => $request->input('targetbeneficiary'),
            'level_id' => $request->input('level'),
            'date_started' => $request->input('datestarted'),
            'date_ended' => $request->input('dateended') ?? null,
            'present' => $request->input('present') ?? null,
            'contact_name' => $request->input('contactname'),
            'contact_number' => $request->input('contactnumber'),
            'contact_address' => $request->input('contactaddress'),
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
                        'submission_id' => $partnership->id,
                        'submission_type' => 'partnership'
                    ]);
                }
            }
        }

        Submission::where('form_name', 'partnership')
                ->where('form_id', $partnership->id)
                ->update(['status' => 1]);

        return redirect()->route('professor.submissions.partnership.show', $partnership->id)->with('success', 'Form updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Partnership $partnership)
    {
        Document::where('submission_id' ,$partnership->id)
                ->where('submission_type', 'partnership')
                ->where('deleted_at', NULL)->delete();
        Submission::where('form_id', $partnership->id)->where('form_name', 'partnership')->delete();
        $partnership->delete();
        return redirect()->route('professor.submissions.index')->with('success_submission', 'Submission deleted successfully.');
    }

    public function removeFileInEdit(Partnership $partnership, Request $request){
        Document::where('filename', $request->input('filename'))->delete();
        Storage::delete('documents/'.$request->input('filename'));

        $submission = Submission::where('form_id', $partnership->id)
        ->where('form_name', 'partnership')
        ->get();
        
        if($submission[0]->status != 1){
            return redirect()->route('professor.partnership.resubmit', $partnership->id)->with('success', 'Document deleted successfully.');
        }
        
        return redirect()->route('professor.submissions.partnership.edit', $partnership)->with('success', 'Document deleted successfully.');
    }

    public function resubmit(Partnership $partnership){
        $departments = Department::orderBy('name')->get();
        $partnertypes = PartnerType::all();
        $collabnatures = CollabNature::all();
        $collabdelivers = CollabDeliver::all();
        $targetbeneficiaries = TargetBeneficiary::all();
        $levels = Level::all();
        $documents = Document::where('submission_id', $partnership->id)
                        ->where('submission_type', 'partnership')
                        ->where('deleted_at', NULL)->get();

        return view('professors.submissions.partnership.edit', [
            'partnership' => $partnership,
            'departments' => $departments,
            'partnertypes' => $partnertypes,
            'collabnatures' => $collabnatures,
            'collabdelivers' => $collabdelivers,
            'targetbeneficiaries' => $targetbeneficiaries,
            'levels' => $levels,
            'documents' => $documents
        ]);
    }
}
