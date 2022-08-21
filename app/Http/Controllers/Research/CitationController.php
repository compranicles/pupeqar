<?php

namespace App\Http\Controllers\Research;

use App\Http\Controllers\{
    Controller,
    Maintenances\LockController,
    Reports\ReportDataController,
    StorageFileController,
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{
    DB,
    Storage,
};
use App\Models\{
    Research,
    ResearchCitation,
    ResearchComplete,
    ResearchCopyright,
    ResearchDocument,
    ResearchPresentation,
    ResearchPublication,
    ResearchUtilization,
    TemporaryFile,
    FormBuilder\ResearchField,
    FormBuilder\DropdownOption,
    FormBuilder\ResearchForm,
    Maintenance\Quarter,
    Maintenance\Department,
    Maintenance\College,
};

class CitationController extends Controller
{
    protected $storageFileController;

    public function __construct(StorageFileController $storageFileController){
        $this->storageFileController = $storageFileController;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Research $research)
    {
        $this->authorize('viewAny', ResearchCitation::class);

        $currentQuarterYear = Quarter::find(1);

        $researchcitations = ResearchCitation::where('research_code', $research->research_code)->orderBy('updated_at', 'desc')->get();

        $research = Research::where('research_code', $research->research_code)->where('user_id', auth()->id())
                    ->join('dropdown_options', 'dropdown_options.id', 'research.status')
                    ->select('research.*', 'dropdown_options.name as status_name')->first();

        $submissionStatus = [];
        $reportdata = new ReportDataController;
        foreach ($researchcitations as $citation) {
            if (LockController::isLocked($citation->id, 5))
                $submissionStatus[5][$citation->id] = 1;
            else
                $submissionStatus[5][$citation->id] = 0;
            if (empty($reportdata->getDocuments(5, $citation->id)))
                $submissionStatus[5][$citation->id] = 2;
        }

        return view('research.citation.index', compact('research', 'researchcitations',
            'currentQuarterYear', 'submissionStatus'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Research $research)
    {
        $this->authorize('create', ResearchCitation::class);

        if(ResearchForm::where('id', 5)->pluck('is_active')->first() == 0)
            return view('inactive');

        $researchFields = DB::select("CALL get_research_fields_by_form_id('5')");

        $dropdown_options = [];
        foreach($researchFields as $field){
            if($field->field_type_name == "dropdown"){
                $dropdownOptions = DropdownOption::where('dropdown_id', $field->dropdown_id)->where('is_active', 1)->get();
                $dropdown_options[$field->name] = $dropdownOptions;

            }
        }

        $research = collect($research);
        $research = $research->except(['description']);
        return view('research.citation.create', compact('researchFields', 'research', 'dropdown_options'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Research $research)
    {
        $this->authorize('create', ResearchCitation::class);
        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        if(ResearchForm::where('id', 5)->pluck('is_active')->first() == 0)
            return view('inactive');

        $currentQuarterYear = Quarter::find(1);

        $request->merge([
            'report_quarter' => $currentQuarterYear->current_quarter,
            'report_year' => $currentQuarterYear->current_year,
            'research_id' => $research->id,
        ]);

        $input = $request->except(['_token', '_method', 'document']);

        $citation = ResearchCitation::create($input);

        if($request->has('document')){

            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'RCT-'.$request->input('research_code').'-'.$this->storageFileController->abbrev($request->input('description')).'-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    ResearchDocument::create([
                        'research_code' => $request->input('research_code'),
                        'research_id' => $research->id,
                        'research_form_id' => 5,
                        'research_citation_id' => $citation->id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        \LogActivity::addToLog('Had added a research citation for "'.$research->title.'".');

        return redirect()->route('research.citation.index', $research->id)->with('success', 'Research citation has been added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Research $research, ResearchCitation $citation)
    {

        if (auth()->id() !== $research->user_id)
            abort(403);

        $this->authorize('view', ResearchCitation::class);
        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        if(ResearchForm::where('id', 5)->pluck('is_active')->first() == 0)
            return view('inactive');

        $researchFields = DB::select("CALL get_research_fields_by_form_id('5')");

        $researchDocuments = ResearchDocument::where('research_citation_id', $citation->id)->get()->toArray();

        $research= Research::where('research_code', $research->research_code)->where('user_id', auth()->id())->join('dropdown_options', 'dropdown_options.id', 'research.status')
                ->select('research.*', 'dropdown_options.name as status_name')->first();


        $values = ResearchCitation::find($citation->id);

        $values = array_merge($research->toArray(), $values->toArray());

        foreach($researchFields as $field){
            if($field->field_type_name == "dropdown"){
                $dropdownOptions = DropdownOption::where('id', $values[$field->name])->where('is_active', 1)->pluck('name')->first();
                if($dropdownOptions == null)
                    $dropdownOptions = "-";
                $values[$field->name] = $dropdownOptions;
            }
            elseif($field->field_type_name == "college"){
                if($values[$field->name] == '0'){
                    $values[$field->name] = 'N/A';
                }
                else{
                    $college = College::where('id', $values[$field->name])->pluck('name')->first();
                    $values[$field->name] = $college;
                }
            }
            elseif($field->field_type_name == "department"){
                if($values[$field->name] == '0'){
                    $values[$field->name] = 'N/A';
                }
                else{
                    $department = Department::where('id', $values[$field->name])->pluck('name')->first();
                    $values[$field->name] = $department;
                }
            }
        }
        
        return view('research.citation.show', compact('research', 'researchFields', 'values', 'researchDocuments'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Research $research, ResearchCitation $citation)
    {

        if (auth()->id() !== $research->user_id)
            abort(403);

        $this->authorize('update', ResearchCitation::class);

        if(LockController::isLocked($citation->id, 5)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited because you already submitted this accomplishment. You can edit it again in the next quarter.');
        }

        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        if(ResearchForm::where('id', 5)->pluck('is_active')->first() == 0)
            return view('inactive');

        $researchFields = DB::select("CALL get_research_fields_by_form_id('5')");

        $dropdown_options = [];
        foreach($researchFields as $field){
            if($field->field_type_name == "dropdown" || $field->field_type_name == "text"){
                $dropdownOptions = DropdownOption::where('dropdown_id', $field->dropdown_id)->where('is_active', 1)->get();
                $dropdown_options[$field->name] = $dropdownOptions;

            }
        }

        $researchDocuments = ResearchDocument::where('research_citation_id', $citation->id)->get()->toArray();

        $research= Research::where('research_code', $research->research_code)->join('dropdown_options', 'dropdown_options.id', 'research.status')
                ->select('research.*', 'dropdown_options.name as status_name')->first();


        $values = ResearchCitation::find($citation->id);

        $values = array_merge($research->toArray(), $values->toArray());

        return view('research.citation.edit', compact('research', 'researchFields', 'values', 'researchDocuments', 'dropdown_options'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Research $research, ResearchCitation $citation)
    {
        $this->authorize('update', ResearchCitation::class);
        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        if(ResearchForm::where('id', 5)->pluck('is_active')->first() == 0)
            return view('inactive');

        $input = $request->except(['_token', '_method', 'document']);

        $citation->update(['description' => '-clear']);

        $citation->update($input);

        if($request->has('document')){

            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'RCT-'.$request->input('research_code').'-'.$this->storageFileController->abbrev($request->input('description')).'-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    ResearchDocument::create([
                        'research_code' => $request->input('research_code'),
                        'research_id' => $research->id,
                        'research_form_id' => 5,
                        'research_citation_id' => $citation->id,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        \LogActivity::addToLog('Had updated a research citation of "'.$research->title.'".');

        return redirect()->route('research.citation.show', [$research->id, $citation->id])->with('success', 'Research Citation Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Research $research, ResearchCitation $citation)
    {
        $this->authorize('delete', ResearchCitation::class);
        if(LockController::isLocked($research->id, 1)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited because you already submitted this accomplishment. You can edit it again in the next quarter.');
        }
        if(LockController::isLocked($citation->id, 5)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited because you already submitted this accomplishment. You can edit it again in the next quarter.');
        }
        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        if(ResearchForm::where('id', 5)->pluck('is_active')->first() == 0)
            return view('inactive');

        $citation->delete();

        \LogActivity::addToLog('Had deleted a research citation of "'.$research->title.'".');

        return redirect()->route('research.citation.index', $research->id)->with('success', 'Research citation has been deleted.');
    }
}
