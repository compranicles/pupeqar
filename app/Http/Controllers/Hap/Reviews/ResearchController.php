<?php

namespace App\Http\Controllers\Hap\Reviews;

use App\Models\Document;
use App\Models\Research;
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
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ResearchController extends Controller
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
    public function show(Research $research)
    {
        $submission = Submission::where('submissions.form_id', $research->id)
                    ->where('submissions.form_name', 'research')
                    ->join('users', 'users.id', '=', 'submissions.user_id')
                    ->select('submissions.status', 'users.first_name', 'users.last_name', 'users.middle_name')->get();
        $department = Department::find($research->department_id);
        $researchclass = ResearchClass::find($research->research_class_id);
        $researchcategory = ResearchCategory::find($research->research_category_id);
        $researchagenda = ResearchAgenda::find($research->research_agenda_id);
        $researchinvolve = ResearchInvolve::find($research->research_involve_id);
        $researchtype = ResearchType::find($research->research_type_id);
        $fundingtype = FundingType::find($research->funding_type_id);
        $documents = Document::where('submission_id' ,$research->id)
                        ->where('submission_type', 'research')
                        ->where('deleted_at', NULL)->get();

        return view('hap.review.research.show', [
            'submission' => $submission[0],
            'research' => $research,
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
    public function edit(Research $research)
    {
        $submission = Submission::where('submissions.form_id', $research->id)
                    ->where('submissions.form_name', 'research')
                    ->join('users', 'users.id', '=', 'submissions.user_id')
                    ->select('submissions.status', 'users.first_name', 'users.last_name', 'users.middle_name')->get();

        if($submission[0]->status != 1){
            return redirect()->route('hap.review.research.show', $research->id)->with('error', 'Edit Submission cannot be accessed');
        }

        $departments = Department::orderBy('name')->get();
        $researchclasses = ResearchClass::all();
        $researchcategories = ResearchCategory::all();
        $researchagendas = ResearchAgenda::all();
        $researchinvolves = ResearchInvolve::all();
        $researchtypes = ResearchType::all();
        $fundingtypes = FundingType::all();
        $documents = Document::where('submission_id' ,$research->id)
                    ->where('submission_type', 'research')
                    ->where('deleted_at', NULL)->get();

        return view('hap.review.research.edit', [
            'submission' => $submission[0],
            'departments' => $departments,
            'researchclasses' => $researchclasses,
            'researchcategories' => $researchcategories,
            'researchagendas' => $researchagendas,
            'researchinvolves' => $researchinvolves,
            'researchtypes' => $researchtypes,
            'fundingtypes' => $fundingtypes,
            'documents' => $documents,
            'research' => $research
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Research $research)
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
            'fundingamount' => ['required', 'numeric'],
            'fundingagency' => 'required',
            'datestarted' => 'required',
            'datetargeted' => 'required',
            'status' => 'required',
            'datecompleted' => 'required',
            'document' => 'required',
            'documentdescription' => 'required',
        ]);

        $research->update([
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
            'status' => $request->input('status'),
            'date_completed' => $request->input('datecompleted') ?? null,
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
                        'submission_id' => $research->id,
                        'submission_type' => 'research'
                    ]);
                }
            }
        }

        Submission::where('form_name', 'research')
                ->where('form_id', $research->id)
                ->update(['status' => 1]);

        return redirect()->route('hap.review.research.show', $research->id)->with('success', 'Form updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Research $research)
    {
        Document::where('submission_id' ,$research->id)
                ->where('submission_type', 'research')
                ->where('deleted_at', NULL)->delete();
        Submission::where('form_id', $research->id)->where('form_name', 'research')->delete();
        $research->delete();
        return redirect()->route('hap.review.index')->with('success_submission', 'Submission deleted successfully.');
    }

    public function removeFileInEdit(Research $research, Request $request){
        Document::where('filename', $request->input('filename'))->delete();
        Storage::delete('documents/'.$request->input('filename'));
        return redirect()->route('hap.review.research.edit', $research)->with('success', 'Document deleted successfully.');
    }
}
