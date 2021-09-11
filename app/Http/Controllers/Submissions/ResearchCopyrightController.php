<?php

namespace App\Http\Controllers\Submissions;

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
use App\Models\ResearchCopyright;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ResearchCopyrightController extends Controller
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
        $researchclasses = ResearchClass::all();
        $researchcategories = ResearchCategory::all();
        $researchagendas = ResearchAgenda::all();
        $researchinvolves = ResearchInvolve::all();
        $researchtypes = ResearchType::all();
        $fundingtypes = FundingType::all();
        $indexplatforms = IndexPlatform::all();

        return view('professors.submissions.researchcopyright.create', [
            'departments' => $departments,
            'researchclasses' => $researchclasses,
            'researchcategories' => $researchcategories,
            'researchagendas' => $researchagendas,
            'researchinvolves' => $researchinvolves,
            'researchtypes' => $researchtypes,
            'fundingtypes' => $fundingtypes,
            'indexplatforms' => $indexplatforms,
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
            'isbn' => 'required',
            'copyrightagency' => 'required',
            'year' => ['numeric', 'required'],
            'indexplatform' => 'required',
            'document' => 'required',
            'documentdescription' => 'required',
        ]);

        $formId = DB::table('research_copyrights')->insertGetID([
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
            'isbn'  => $request->input('isbn') ?? null,
            'copyright_agency'  => $request->input('copyrightagency') ?? null,
            'year'  => $request->input('year') ?? null,
            'index_platform_id'  => $request->input('indexplatform') ?? null,
            'document_description'  => $request->input('documentdescription') ?? null,
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
                        'submission_type' => 'researchcopyright'
                    ]);
                }
            }
        }

        Submission::create([
            'user_id' => Auth::id(),
            'form_id' => $formId,
            'form_name' => 'researchcopyright',
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
    public function show(ResearchCopyright $researchcopyright)
    {
        $department = Department::find($researchcopyright->department_id);
        $researchclass = ResearchClass::find($researchcopyright->research_class_id);
        $researchcategory = ResearchCategory::find($researchcopyright->research_category_id);
        $researchagenda = ResearchAgenda::find($researchcopyright->research_agenda_id);
        $researchinvolve = ResearchInvolve::find($researchcopyright->research_involve_id);
        $researchtype = ResearchType::find($researchcopyright->research_type_id);
        $fundingtype = FundingType::find($researchcopyright->funding_type_id);
        $indexplatform = IndexPlatform::find($researchcopyright->index_platform_id);
        $documents = Document::where('submission_id' ,$researchcopyright->id)
                        ->where('submission_type', 'researchcopyright')
                        ->where('deleted_at', NULL)->get();
        $submission = Submission::where('submissions.form_id', $researchcopyright->id)
                        ->where('submissions.form_name', 'researchcopyright')
                        ->join('users', 'users.id', '=', 'submissions.user_id')
                        ->select('submissions.status')->get();
        
        return view('professors.submissions.researchcopyright.show', [
            'research' => $researchcopyright,
            'department' => $department,
            'researchclass' => $researchclass,
            'researchcategory' => $researchcategory,
            'researchagenda' => $researchagenda,
            'researchinvolve' => $researchinvolve,
            'researchtype' => $researchtype,
            'fundingtype' => $fundingtype,
            'indexplatform' => $indexplatform,
            'documents' => $documents,
            'submission' => $submission[0]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(ResearchCopyright $researchcopyright)
    {
        $departments = Department::orderBy('name')->get();
        $researchclasses = ResearchClass::all();
        $researchcategories = ResearchCategory::all();
        $researchagendas = ResearchAgenda::all();
        $researchinvolves = ResearchInvolve::all();
        $researchtypes = ResearchType::all();
        $fundingtypes = FundingType::all();
        $indexplatforms = IndexPlatform::all();
        $documents = Document::where('submission_id' ,$researchcopyright->id)
                        ->where('submission_type', 'researchcopyright')
                        ->where('deleted_at', NULL)->get();
        
        return view('professors.submissions.researchcopyright.edit', [
            'researchcopyright' => $researchcopyright,
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
    public function update(Request $request, ResearchCopyright $researchcopyright)
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
            'isbn' => 'required',
            'copyrightagency' => 'required',
            'year' => ['numeric', 'required'],
            'indexplatform' => 'required',
            'document' => 'required',
            'documentdescription' => 'required',
        ]);

        $researchcopyright->update([
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
            'isbn'  => $request->input('isbn') ?? null,
            'copyright_agency'  => $request->input('copyrightagency') ?? null,
            'year'  => $request->input('year') ?? null,
            'index_platform_id'  => $request->input('indexplatform') ?? null,
            'document_description'  => $request->input('documentdescription') ?? null,
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
                        'submission_id' => $researchcopyright->id,
                        'submission_type' => 'researchcopyright'
                    ]);
                }
            }
        }

        Submission::where('form_name', 'researchcopyright')
                ->where('form_id', $researchcopyright->id)
                ->update(['status' => 1]);

        return redirect()->route('professor.submissions.researchcopyright.show', $researchcopyright->id)->with('success', 'Form updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ResearchCopyright $researchcopyright)
    {
        Document::where('submission_id' ,$researchcopyright->id)
                ->where('submission_type', 'researchcopyright')
                ->where('deleted_at', NULL)->delete();
        Submission::where('form_id', $researchcopyright->id)->where('form_name', 'researchcopyright')->delete();
        $researchcopyright->delete();
        return redirect()->route('professor.submissions.index')->with('success_submission', 'Submission deleted successfully.');
    }

    public function removeFileInEdit(ResearchCopyright $researchcopyright, Request $request){
        Document::where('filename', $request->input('filename'))->delete();
        Storage::delete('documents/'.$request->input('filename'));
        return redirect()->route('professor.submissions.researchcopyright.edit', $researchcopyright)->with('success', 'Document deleted successfully.');
    }
}
