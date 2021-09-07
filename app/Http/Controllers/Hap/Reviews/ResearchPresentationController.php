<?php

namespace App\Http\Controllers\Hap\Reviews;

use App\Models\Document;
use App\Models\Department;
use App\Models\Submission;
use App\Models\FundingType;
use App\Models\ResearchType;
use Illuminate\Http\Request;
use App\Models\ResearchClass;
use App\Models\ResearchLevel;
use App\Models\TemporaryFile;
use App\Models\ResearchAgenda;
use App\Models\ResearchInvolve;
use App\Models\ResearchCategory;
use App\Http\Controllers\Controller;
use App\Models\ResearchPresentation;
use Illuminate\Support\Facades\Storage;

class ResearchPresentationController extends Controller
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
    public function show(ResearchPresentation $researchpresentation)
    {
        $department = Department::find($researchpresentation->department_id);
        $researchclass = ResearchClass::find($researchpresentation->research_class_id);
        $researchcategory = ResearchCategory::find($researchpresentation->research_category_id);
        $researchagenda = ResearchAgenda::find($researchpresentation->research_agenda_id);
        $researchinvolve = ResearchInvolve::find($researchpresentation->research_involve_id);
        $researchtype = ResearchType::find($researchpresentation->research_type_id);
        $fundingtype = FundingType::find($researchpresentation->funding_type_id);
        $researchlevel = ResearchLevel::find($researchpresentation->research_level_id);
        $documents = Document::where('submission_id' ,$researchpresentation->id)
                        ->where('submission_type', 'researchpresentation')
                        ->where('deleted_at', NULL)->get();

        return view('hap.review.researchpresentation.show', [
            'research' => $researchpresentation,
            'department' => $department,
            'researchclass' => $researchclass,
            'researchcategory' => $researchcategory,
            'researchagenda' => $researchagenda,
            'researchinvolve' => $researchinvolve,
            'researchtype' => $researchtype,
            'fundingtype' => $fundingtype,
            'researchlevel' => $researchlevel,
            'documents' => $documents
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(ResearchPresentation $researchpresentation)
    {
        $departments = Department::orderBy('name')->get();
        $researchclasses = ResearchClass::all();
        $researchcategories = ResearchCategory::all();
        $researchagendas = ResearchAgenda::all();
        $researchinvolves = ResearchInvolve::all();
        $researchtypes = ResearchType::all();
        $fundingtypes = FundingType::all();
        $researchlevels = ResearchLevel::all();
        $documents = Document::where('submission_id' ,$researchpresentation->id)
                        ->where('submission_type', 'researchpresentation')
                        ->where('deleted_at', NULL)->get();

        return view('hap.review.researchpresentation.edit', [
            'researchpresentation' => $researchpresentation,
            'departments' => $departments,
            'researchclasses' => $researchclasses,
            'researchcategories' => $researchcategories,
            'researchagendas' => $researchagendas,
            'researchinvolves' => $researchinvolves,
            'researchtypes' => $researchtypes,
            'fundingtypes' => $fundingtypes,
            'researchlevels' => $researchlevels,
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
    public function update(Request $request, ResearchPresentation $researchpresentation)
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
            'document' => 'required', 
            'researchlevel' => 'required',
            'documentdescription' => 'required',
        ]);

        $researchpresentation->update([
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
            'conference_title' => $request->input('conferencetitle') ?? null,
            'organizer' => $request->input('organizer') ?? null,
            'venue' => $request->input('venue') ?? null,
            'date_presented' => $request->input('datepresented') ?? null,
            'research_level_id' => $request->input('researchlevel'),
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
                        'submission_id' => $researchpresentation->id,
                        'submission_type' => 'researchpresentation'
                    ]);
                }
            }
        }

        Submission::where('form_name', 'researchpresentation')
                ->where('form_id', $researchpresentation->id)
                ->update(['status' => 1]);

        return redirect()->route('hap.review.researchpresentation.show', $researchpresentation->id)->with('success', 'Form updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ResearchPresentation $researchpresentation)
    {
        Document::where('submission_id' ,$researchpresentation->id)
                ->where('submission_type', 'researchpresentation')
                ->where('deleted_at', NULL)->delete();
        Submission::where('form_id', $researchpresentation->id)->where('form_name', 'researchpresentation')->delete();
        $researchpresentation->delete();
        return redirect()->route('hap.review.index')->with('success_submission', 'Submission deleted successfully.');
    }

    public function removeFileInEdit(ResearchPresentation $researchpresentation, Request $request){
        Document::where('filename', $request->input('filename'))->delete();
        Storage::delete('documents/'.$request->input('filename'));
        return redirect()->route('hap.review.researchpresentation.edit', $researchpresentation)->with('success', 'Document deleted successfully.');
    }
}
