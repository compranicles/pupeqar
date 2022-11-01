<?php

namespace App\Http\Controllers\Research;

use App\Http\Controllers\Controller;
use App\Http\Controllers\StorageFileController;
use App\Http\Controllers\Maintenances\LockController;
use App\Http\Controllers\Reports\ReportDataController;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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
    FormBuilder\DropdownOption,
    FormBuilder\ResearchField,
    FormBuilder\ResearchForm,
    Maintenance\Quarter,
    Maintenance\College,
    Maintenance\Department,
};
use App\Services\CommonService;
use Exception;

class CompletedController extends Controller
{
    protected $storageFileController;
    private $commonService;
    protected $researchController;

    public function __construct(StorageFileController $storageFileController, CommonService $commonService, ResearchController $researchController){
        $this->storageFileController = $storageFileController;
        $this->commonService = $commonService;
        $this->researchController = $researchController;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Research $research)
    {
        $this->authorize('viewAny', ResearchComplete::class);

        $completionFields = DB::select("CALL get_research_fields_by_form_id('2')");

        $research= Research::where('research_code', $research->research_code)->where('user_id', auth()->id())
                ->join('dropdown_options', 'dropdown_options.id', 'research.status')
                ->select('research.*', 'dropdown_options.name as status_name')->first();
        $completionDocuments = ResearchDocument::where('research_code', $research->research_code)->where('research_form_id', 2)->get()->toArray();
        $completionRecord = ResearchComplete::where('research_code', $research->research_code)->first();
        
        if($completionRecord == null){
            if($research->status == 27)
                return redirect()->route('research.completed.create', $research->id);
            else {
                $value = null;
                return view('research.completed.index', compact('research', 'value'));
            }
        }
        
        $completionValues = array_merge(collect($completionRecord)->except(['research_code'])->toArray(), collect($research)->except(['description'])->toArray());
        
        $submissionStatus[2][$completionValues['id']] = $this->commonService->getSubmissionStatus($completionValues['id'], 2)['submissionStatus'];
        $submitRole[$completionValues['id']] = $this->commonService->getSubmissionStatus($completionValues['id'], 2)['submitRole'];
        
        $value = $this->commonService->getDropdownValues($completionFields, $completionValues);

        $noRequisiteRecords[1] = $this->researchController->getNoRequisites($research)['presentationRecord'];
        $noRequisiteRecords[2] = $this->researchController->getNoRequisites($research)['publicationRecord'];
        $noRequisiteRecords[3] = $this->researchController->getNoRequisites($research)['copyrightRecord'];

        return view('research.completed.index', compact('research', 'completionFields',
            'value', 'completionDocuments', 'submissionStatus', 'submitRole', 'noRequisiteRecords'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Research $research)
    {
        $this->authorize('create', ResearchComplete::class);
        $currentQuarter = Quarter::find(1)->current_quarter;

        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        if(ResearchForm::where('id', 2)->pluck('is_active')->first() == 0)
            return view('inactive');

        $researchFields = DB::select("CALL get_research_fields_by_form_id('2')");

        $dropdown_options = [];
        foreach($researchFields as $field){
            if($field->field_type_name == "dropdown" || $field->field_type_name == "text"){
                $dropdownOptions = DropdownOption::where('dropdown_id', $field->dropdown_id)->where('is_active', 1)->get();
                $dropdown_options[$field->name] = $dropdownOptions;

            }
        }

        $value = $research;
        $value->toArray();
        $value = collect($research);
        $value = $value->except(['description', 'status']);
        $value = $value->toArray();

        $researchStatus = DropdownOption::where('dropdown_options.dropdown_id', 7)->where('id', 28)->first();

        return view('research.completed.create', compact('research', 'researchFields', 'researchStatus', 'value', 'dropdown_options', 'currentQuarter'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Research $research)
    {
        $this->authorize('create', ResearchComplete::class);
        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        if(ResearchForm::where('id', 2)->pluck('is_active')->first() == 0)
            return view('inactive');

        $completion_date = date("Y-m-d", strtotime($request->input('completion_date')));
        $currentQuarterYear = Quarter::find(1);

        $request->merge([
            'completion_date' => $completion_date,
        ]);

        $request->validate([
            'completion_date' => 'after_or_equal:start_date|required_if:status, 28',
        ]);

        $input = $request->except(['_token', '_method', 'research_code', 'description', 'document']);
        $input = Arr::add($input, 'status', 28);
        Research::where('research_code', $research->research_code)->update($input);

        $completed = ResearchComplete::create([
            'research_code' => $research->research_code,
            'research_id' => $research->id,
            'report_quarter' => $currentQuarterYear->current_quarter,
            'report_year' => $currentQuarterYear->current_year,
        ]);

        $completed->update([
            'description' => $request->input('description'),
        ]);

        if($request->has('document')){

            try {
                $documents = $request->input('document');
                foreach($documents as $document){
                    $temporaryFile = TemporaryFile::where('folder', $document)->first();
                    if($temporaryFile){
                        $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                        $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                        $ext = $info['extension'];
                        $fileName = 'RCP-'.$request->input('research_code').'-'.$this->storageFileController->abbrev($request->input('description')).'-'.now()->timestamp.uniqid().'.'.$ext;
                        $newPath = "documents/".$fileName;
                        Storage::move($temporaryPath, $newPath);
                        Storage::deleteDirectory("documents/tmp/".$document);
                        $temporaryFile->delete();
    
                        ResearchDocument::create([
                            'research_code' => $request->input('research_code'),
                            'research_id' => $research->id,
                            'research_form_id' => 2,
                            'filename' => $fileName,
                        ]);
                    }
                }
            } catch (Exception $th) {
                return redirect()->back()->with('error', 'Request timeout, Unable to upload, Please try again!' );
            }

           
        }

        \LogActivity::addToLog('Had marked the research "'.$research->title.'" as completed.');


        return redirect()->route('research.index')->with('success', 'Research completetion has been added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Research $research, ResearchComplete $completed)
    {
        $currentQuarter = Quarter::find(1)->current_quarter;
        $this->authorize('update', ResearchComplete::class);

        if (auth()->id() !== $research->user_id)
            abort(403);

        if(LockController::isLocked($completed->id, 2)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited because you already submitted this accomplishment. You can edit it again in the next quarter.');
        }

        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        if(ResearchForm::where('id', 2)->pluck('is_active')->first() == 0)
            return view('inactive');

        $researchFields = DB::select("CALL get_research_fields_by_form_id('2')");

        $dropdown_options = [];
        foreach($researchFields as $field){
            if($field->field_type_name == "dropdown" || $field->field_type_name == "text"){
                $dropdownOptions = DropdownOption::where('dropdown_id', $field->dropdown_id)->where('is_active', 1)->get();
                $dropdown_options[$field->name] = $dropdownOptions;

            }
        }

        $researchDocuments = ResearchDocument::where('research_code', $research['research_code'])->where('research_form_id', 2)->get()->toArray();

        $value = $research->toArray();
        $value = collect($research);
        $value = $value->except(['description', 'status']);
        $value = $value->toArray();
        $value = array_merge($value, $completed->toArray());

        $researchStatus = DropdownOption::where('dropdown_options.dropdown_id', 7)->where('id', $research['status'])->first();

        $noRequisiteRecords[1] = $this->researchController->getNoRequisites($research)['presentationRecord'];
        $noRequisiteRecords[2] = $this->researchController->getNoRequisites($research)['publicationRecord'];
        $noRequisiteRecords[3] = $this->researchController->getNoRequisites($research)['copyrightRecord'];

        return view('research.completed.edit', compact('research', 'researchFields', 'researchDocuments', 'value', 'researchStatus', 'dropdown_options', 'currentQuarter', 'noRequisiteRecords'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Research $research, ResearchComplete $completed)
    {
        $currentQuarterYear = Quarter::find(1);
        $this->authorize('update', ResearchComplete::class);
        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        if(ResearchForm::where('id', 2)->pluck('is_active')->first() == 0)
            return view('inactive');

        $completion_date = date("Y-m-d", strtotime($request->input('completion_date')));

        $request->merge([
            'completion_date' => $completion_date,
            'report_quarter' => $currentQuarterYear->current_quarter,
            'report_year' => $currentQuarterYear->current_year,
        ]);

        $request->validate([
            'completion_date' => 'after_or_equal:start_date|required_if:status, 28',
        ]);

        $input = $request->except(['_token', '_method', 'research_code', 'description', 'document']);

        Research::where('research_code', $research->research_code)->update($input);

        $completed->update(['description' => '-clear']);

        $completed->update([
            'research_code' => $research->research_code,
            'description' => $request->input('description')
        ]);

        if($request->has('document')){

            try {
                $documents = $request->input('document');
                foreach($documents as $document){
                    $temporaryFile = TemporaryFile::where('folder', $document)->first();
                    if($temporaryFile){
                        $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                        $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                        $ext = $info['extension'];
                        $fileName = 'RCP-'.$request->input('research_code').'-'.$this->storageFileController->abbrev($request->input('description')).'-'.now()->timestamp.uniqid().'.'.$ext;
                        $newPath = "documents/".$fileName;
                        Storage::move($temporaryPath, $newPath);
                        Storage::deleteDirectory("documents/tmp/".$document);
                        $temporaryFile->delete();
    
                        ResearchDocument::create([
                            'research_id' => $research->id,
                            'research_code' => $request->input('research_code'),
                            'research_form_id' => 2,
                            'filename' => $fileName,
                        ]);
                    }
                }
            } catch (Exception $th) {
                return redirect()->back()->with('error', 'Request timeout, Unable to upload, Please try again!' );
            }

           
        }

        \LogActivity::addToLog('Had updated the completion details of research "'.$research->title.'".');

        return redirect()->route('research.index')->with('success', 'Research completetion has been updated.');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
