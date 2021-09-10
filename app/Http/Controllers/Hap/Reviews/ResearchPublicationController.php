<?php

namespace App\Http\Controllers\Hap\Reviews;

use App\Models\Document;
use App\Models\Department;
use App\Models\Submission;
use App\Models\FundingType;
use App\Models\ResearchType;
use Illuminate\Http\Request;
use App\Models\ResearchClass;
use App\Models\TemporaryFile;
use App\Models\ResearchAgenda;
use App\Models\ResearchInvolve;
use App\Models\ResearchCategory;
use App\Models\ResearchPublication;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ResearchPublicationController extends Controller
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
    public function show(ResearchPublication $researchpublication)
    {
        $submission = Submission::where('submissions.form_id', $researchpublication->id)
        ->where('submissions.form_name', 'researchpublication')
        ->join('users', 'users.id', '=', 'submissions.user_id')
        ->select('submissions.status', 'users.first_name', 'users.last_name', 'users.middle_name')->get();
        $department = Department::find($researchpublication->department_id);
        $researchclass = ResearchClass::find($researchpublication->research_class_id);
        $researchcategory = ResearchCategory::find($researchpublication->research_category_id);
        $researchagenda = ResearchAgenda::find($researchpublication->research_agenda_id);
        $researchinvolve = ResearchInvolve::find($researchpublication->research_involve_id);
        $researchtype = ResearchType::find($researchpublication->research_type_id);
        $fundingtype = FundingType::find($researchpublication->funding_type_id);
        $documents = Document::where('submission_id' ,$researchpublication->id)
                        ->where('submission_type', 'researchpublication')
                        ->where('deleted_at', NULL)->get();

        return view('hap.review.researchpublication.show', [
            'submission' => $submission[0],
            'research' => $researchpublication,
            'department' => $department,
            'researchclass' => $researchclass,
            'researchcategory' => $researchcategory,
            'researchagenda' => $researchagenda,
            'researchinvolve' => $researchinvolve,
            'researchtype' => $researchtype,
            'fundingtype' => $fundingtype,
            'documents' => $documents
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(ResearchPublication $researchpublication)
    {
        $submission = Submission::where('submissions.form_id', $researchpublication->id)
        ->where('submissions.form_name', 'researchpublication')
        ->join('users', 'users.id', '=', 'submissions.user_id')
        ->select('submissions.status', 'users.first_name', 'users.last_name', 'users.middle_name')->get();

        if($submission[0]->status != 1){
            return redirect()->route('hap.review.researchpublication.show', $researchpublication->id)->with('error', 'Edit Submission cannot be accessed');
        }

        $departments = Department::orderBy('name')->get();
        $researchclasses = ResearchClass::all();
        $researchcategories = ResearchCategory::all();
        $researchagendas = ResearchAgenda::all();
        $researchinvolves = ResearchInvolve::all();
        $researchtypes = ResearchType::all();
        $fundingtypes = FundingType::all();
        $documents = Document::where('submission_id' ,$researchpublication->id)
                ->where('submission_type', 'researchpublication')
                ->where('deleted_at', NULL)->get();

        return view('hap.review.researchpublication.edit', [
            'submission' => $submission[0],
            'researchpublication' => $researchpublication,
            'departments' => $departments,
            'researchclasses' => $researchclasses,
            'researchcategories' => $researchcategories,
            'researchagendas' => $researchagendas,
            'researchinvolves' => $researchinvolves,
            'researchtypes' => $researchtypes,
            'fundingtypes' => $fundingtypes,
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
    public function update(Request $request, ResearchPublication $researchpublication)
    {
        $request->validate([
            'department' => 'required',
            'researchclass' => 'required',
            'researchcategory' => 'required',
            'researchagenda' => 'required',
            'title' => 'required',
            'researchers' => 'required',
            'researchinvolve' => 'required',
            'researchtype' => 'required',
            'keywords' => 'required',
            'fundingtype' => 'required',
            'fundingamount' => 'numeric',
            'fundingagency' => 'required',
            'datestarted' => 'required',
            'datetargeted' => 'required',
            'datecompleted' => 'required',
            'journalname' => 'required',
            'document' => 'required',
            'documentdescription' => 'required',
        ]);

        $researchpublication->update([
            'department_id' => $request->input('department'),
            'research_class_id' => $request->input('researchclass'),
            'research_category_id' => $request->input('researchcategory'),
            'research_agenda_id'  => $request->input('researchagenda'),
            'title'  => $request->input('title'),
            'researchers'  => $request->input('researchers'),
            'research_involve_id'  => $request->input('researchinvolve'),
            'research_type_id'  => $request->input('researchtype'),
            'keywords' => $request->input('keywords'),
            'funding_type_id' => $request->input('fundingtype'),
            'funding_amount' => $request->input('fundingamount'),
            'funding_agency' => $request->input('fundingagency'),
            'date_started' => $request->input('datestarted'),
            'date_targeted' => $request->input('datetargeted'),
            'date_completed' => $request->input('datecompleted') ?? null,
            'journal_name'  => $request->input('journalname') ?? null,
            'page' => $request->input('page') ?? null,
            'volume' => $request->input('volume') ?? null,
            'issue' => $request->input('issue') ?? null,
            'indexing_platform' => $request->input('indexingplatform') ?? null,
            'date_published' => $request->input('datepublished') ?? null,
            'publisher' => $request->input('publisher') ?? null,
            'editor' => $request->input('editor') ?? null,
            'isbn' => $request->input('isbn') ?? null,
            'level' => $request->input('level') ?? null,
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
                        'submission_id' => $researchpublication->id,
                        'submission_type' => 'researchpublication'
                    ]);
                }
            }
        }

        Submission::where('form_name', 'researchpublication')
                ->where('form_id', $researchpublication->id)
                ->update(['status' => 1]);

        return redirect()->route('hap.review.researchpublication.show', $researchpublication->id)->with('success', 'Form updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ResearchPublication $researchpublication)
    {
        Document::where('submission_id' ,$researchpublication->id)
                ->where('submission_type', 'researchpublication')
                ->where('deleted_at', NULL)->delete();
        Submission::where('form_id', $researchpublication->id)->where('form_name', 'researchpublication')->delete();
        $researchpublication->delete();
        return redirect()->route('hap.review.index')->with('success_submission', 'Submission deleted successfully.');
    }

    public function removeFileInEdit(ResearchPublication $researchpublication, Request $request){
        Document::where('filename', $request->input('filename'))->delete();
        Storage::delete('documents/'.$request->input('filename'));
        return redirect()->route('hap.review.researchpublication.edit', $researchpublication)->with('success', 'Document deleted successfully.');
    }
}
