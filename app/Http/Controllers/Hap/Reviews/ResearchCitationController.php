<?php

namespace App\Http\Controllers\Hap\Reviews;

use App\Models\Document;
use App\Models\Department;
use App\Models\Submission;
use App\Models\FundingType;
use App\Models\ResearchType;
use Illuminate\Http\Request;
use App\Models\IndexPlatform;
use App\Models\ResearchClass;
use App\Models\TemporaryFile;
use App\Models\ResearchAgenda;
use App\Models\ResearchInvolve;
use App\Models\ResearchCategory;
use App\Models\ResearchCitation;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ResearchCitationController extends Controller
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
    public function show(ResearchCitation $researchcitation)
    {
        $submission = Submission::where('submissions.form_id', $researchcitation->id)
                    ->where('submissions.form_name', 'researchcitation')
                    ->join('users', 'users.id', '=', 'submissions.user_id')
                    ->select('submissions.status', 'users.first_name', 'users.last_name', 'users.middle_name')->get();
        $department = Department::find($researchcitation->department_id);
        $researchclass = ResearchClass::find($researchcitation->research_class_id);
        $researchcategory = ResearchCategory::find($researchcitation->research_category_id);
        $researchagenda = ResearchAgenda::find($researchcitation->research_agenda_id);
        $researchinvolve = ResearchInvolve::find($researchcitation->research_involve_id);
        $researchtype = ResearchType::find($researchcitation->research_type_id);
        $fundingtype = FundingType::find($researchcitation->funding_type_id);
        $indexplatform = IndexPlatform::find($researchcitation->index_platform_id);
        $documents = Document::where('submission_id' ,$researchcitation->id)
                        ->where('submission_type', 'researchcitation')
                        ->where('deleted_at', NULL)->get();

        return view('hap.review.researchcitation.show', [
            'submission' => $submission[0],
            'research' => $researchcitation,
            'department' => $department,
            'researchclass' => $researchclass,
            'researchcategory' => $researchcategory,
            'researchagenda' => $researchagenda,
            'researchinvolve' => $researchinvolve,
            'researchtype' => $researchtype,
            'fundingtype' => $fundingtype,
            'indexplatform' => $indexplatform,
            'documents' => $documents
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(ResearchCitation $researchcitation)
    {
        $submission = Submission::where('submissions.form_id', $researchcitation->id)
                    ->where('submissions.form_name', 'researchcitation')
                    ->join('users', 'users.id', '=', 'submissions.user_id')
                    ->select('submissions.status', 'users.first_name', 'users.last_name', 'users.middle_name')->get();
        $departments = Department::orderBy('name')->get();
        $researchclasses = ResearchClass::all();
        $researchcategories = ResearchCategory::all();
        $researchagendas = ResearchAgenda::all();
        $researchinvolves = ResearchInvolve::all();
        $researchtypes = ResearchType::all();
        $fundingtypes = FundingType::all();
        $indexplatforms = IndexPlatform::all();
        $documents = Document::where('submission_id' ,$researchcitation->id)
                        ->where('submission_type', 'researchcitation')
                        ->where('deleted_at', NULL)->get();

        return view('hap.review.researchcitation.edit', [
            'submission' => $submission[0],
            'researchcitation' => $researchcitation,
            'departments' => $departments,
            'researchclasses' => $researchclasses,
            'researchcategories' => $researchcategories,
            'researchagendas' => $researchagendas,
            'researchinvolves' => $researchinvolves,
            'researchtypes' => $researchtypes,
            'fundingtypes' => $fundingtypes,
            'indexplatforms' => $indexplatforms,
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
    public function update(Request $request, ResearchCitation $researchcitation)
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
            'researchcited' => 'required',
            'indexplatform' => 'required',
            'year' => 'numeric',
            'document' => 'required',
            'documentdescription' => 'required',
        ]);

        $researchcitation->update([
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
            'research_cited' => $request->input('researchcited') ?? null,
            'article_title' => $request->input('articletitle') ?? null,
            'author_cited' => $request->input('authorcited') ?? null,
            'journal_title' => $request->input('journaltitle') ?? null,
            'volume' => $request->input('volume') ?? null,
            'issue' => $request->input('issue') ?? null,
            'page' => $request->input('page') ?? null,
            'year' => $request->input('year') ?? null,
            'publisher' => $request->input('publisher') ?? null,
            'index_platform_id' => $request->input('indexplatform'),
            'document_description' => $request->input('documentdescription') ?? null,
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
                        'submission_id' => $researchcitation->id,
                        'submission_type' => 'researchcitation'
                    ]);
                }
            }
        }

        Submission::where('form_name', 'researchcitation')
                ->where('form_id', $researchcitation->id)
                ->update(['status' => 1]);

        return redirect()->route('hap.review.researchcitation.show', $researchcitation->id)->with('success', 'Form updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ResearchCitation $researchcitation)
    {
        Document::where('submission_id' ,$researchcitation->id)
                ->where('submission_type', 'researchcitation')
                ->where('deleted_at', NULL)->delete();
        Submission::where('form_id', $researchcitation->id)->where('form_name', 'researchcitation')->delete();
        $researchcitation->delete();
        return redirect()->route('hap.review.index')->with('success_submission', 'Submission deleted successfully.');
    }

    public function removeFileInEdit(ResearchCitation $researchcitation, Request $request){
        Document::where('filename', $request->input('filename'))->delete();
        Storage::delete('documents/'.$request->input('filename'));
        return redirect()->route('hap.review.researchcitation.edit', $researchcitation)->with('success', 'Document deleted successfully.');
    }
}
