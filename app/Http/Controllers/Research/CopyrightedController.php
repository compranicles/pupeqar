<?php

namespace App\Http\Controllers\Research;

use App\Helpers\LogActivity;
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
    ResearchCopyright,
    ResearchDocument,
    FormBuilder\DropdownOption,
    FormBuilder\ResearchForm,
    Maintenance\Quarter,
    Maintenance\College,
    Maintenance\Department,
};
use App\Services\CommonService;
use Exception;

class CopyrightedController extends Controller
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
        $this->authorize('viewAny', ResearchCopyright::class);

        $copyrightFields = DB::select("CALL get_research_fields_by_form_id('7')");

        $copyrightDocuments = ResearchDocument::where('research_code', $research->research_code)->where('research_form_id', 7)->get()->toArray();
        
        $research= Research::where('research_code', $research->research_code)->where('user_id', auth()->id())
                ->join('dropdown_options', 'dropdown_options.id', 'research.status')
                ->select('research.*', 'dropdown_options.name as status_name')->first();

        $copyrightRecord = ResearchCopyright::where('research_code', $research->research_code)->first();

        if($copyrightRecord == null){
            if($research->status >= 28)
                return redirect()->route('research.copyrighted.create', $research->id);
            else {
                $value = null;
                return view('research.copyrighted.index', compact('research', 'value'));
            }
        }

        $copyrightValues = array_merge($research->toArray(), $copyrightRecord->toArray());

        $submissionStatus[7][$copyrightValues['id']] = $this->commonService->getSubmissionStatus($copyrightValues['id'], 7)['submissionStatus'];
        $submitRole[$copyrightValues['id']] = $this->commonService->getSubmissionStatus($copyrightValues['id'], 7)['submitRole'];

        $values = $this->commonService->getDropdownValues($copyrightFields, $copyrightValues);

        $noRequisiteRecords[1] = $this->researchController->getNoRequisites($research)['presentationRecord'];
        $noRequisiteRecords[2] = $this->researchController->getNoRequisites($research)['publicationRecord'];
        $noRequisiteRecords[3] = $this->researchController->getNoRequisites($research)['copyrightRecord'];

        return view('research.copyrighted.index', compact('research', 'copyrightFields', 'values',
            'copyrightDocuments', 'submissionStatus', 'submitRole', 'noRequisiteRecords'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Research $research)
    {
        $this->authorize('create', ResearchCopyright::class);
        $currentQuarter = Quarter::find(1)->current_quarter;

        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        if(ResearchForm::where('id', 7)->pluck('is_active')->first() == 0)
            return view('inactive');

        $researchFields = DB::select("CALL get_research_fields_by_form_id('7')");

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

        return view('research.copyrighted.create', compact('researchFields', 'research', 'value', 'dropdown_options', 'currentQuarter'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Research $research)
    {
        $this->authorize('create', ResearchCopyright::class);
        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        if(ResearchForm::where('id', 7)->pluck('is_active')->first() == 0)
            return view('inactive');

        $date_parts = explode('-', $research->completion_date);
        $currentQuarterYear = Quarter::find(1);

        $request->merge([
            'report_quarter' => $currentQuarterYear->current_quarter,
            'report_year' => $currentQuarterYear->current_year,
            'research_id' => $research->id,
        ]);

        $request->validate([
            'copyright_year' => 'after_or_equal:'.$date_parts[0],
        ]);

        $input = $request->except(['_token', '_method', 'document']);

        $copyright = ResearchCopyright::create($input);

        LogActivity::addToLog('Had added a copyright for research "'.$research->title.'".');

        if(!empty($request->file(['document']))){      
            foreach($request->file(['document']) as $document){
                $fileName = $this->commonService->fileUploadHandler($document, $request->input("description"), "RCR-", 'research.copyrighted.index');
                if(is_string($fileName)) {
                    ResearchDocument::create([
                        'research_code' => $request->input('research_code'),
                        'research_id' => $research->id,
                        'research_form_id' => 7,
                        'filename' => $fileName,
                    ]);
                } else return $fileName;
            }
        }

        \LogActivity::addToLog('Had added a copyright for research "'.$research->title.'".');

        return redirect()->route('research.index')->with('success', 'Research copyright has been added.');
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
    public function edit(Research $research, ResearchCopyright $copyrighted)
    {
        $currentQuarter = Quarter::find(1)->current_quarter;
        $this->authorize('update', ResearchCopyright::class);

        if (auth()->id() !== $research->user_id)
            abort(403);

        if(LockController::isLocked($copyrighted->id, 7)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited because you already submitted this accomplishment. You can edit it again in the next quarter.');
        }

        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        if(ResearchForm::where('id', 7)->pluck('is_active')->first() == 0)
            return view('inactive');

        $researchFields = DB::select("CALL get_research_fields_by_form_id('7')");

        $dropdown_options = [];
        foreach($researchFields as $field){
            if($field->field_type_name == "dropdown" || $field->field_type_name == "text"){
                $dropdownOptions = DropdownOption::where('dropdown_id', $field->dropdown_id)->where('is_active', 1)->get();
                $dropdown_options[$field->name] = $dropdownOptions;

            }
        }

        $researchDocuments = ResearchDocument::where('research_code', $research['research_code'])->where('research_form_id', 7)->get()->toArray();

        $value = array_merge($research->toArray(), $copyrighted->toArray());

        $noRequisiteRecords[1] = $this->researchController->getNoRequisites($research)['presentationRecord'];
        $noRequisiteRecords[2] = $this->researchController->getNoRequisites($research)['publicationRecord'];
        $noRequisiteRecords[3] = $this->researchController->getNoRequisites($research)['copyrightRecord'];

        return view('research.copyrighted.edit', compact('research', 'researchFields', 'value', 'researchDocuments', 'dropdown_options', 'currentQuarter', 'noRequisiteRecords'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Research $research, ResearchCopyright $copyrighted)
    {
        $currentQuarterYear = Quarter::find(1);
        $this->authorize('update', ResearchCopyright::class);
        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        if(ResearchForm::where('id', 7)->pluck('is_active')->first() == 0)
            return view('inactive');

        $date_parts = explode('-', $research->completion_date);

        $request->merge([
            'report_quarter' => $currentQuarterYear->current_quarter,
            'report_year' => $currentQuarterYear->current_year,
        ]);
        
        $request->validate([
            'copyright_year' => 'after_or_equal:'.$date_parts[0],
        ]);

        $input = $request->except(['_token', '_method', 'document']);

        $copyrighted->update(['description' => '-clear']);

        $copyrighted->update($input);

        LogActivity::addToLog('Had updated a copyright of research "'.$research->title.'".');
        if(!empty($request->file(['document']))){      
            foreach($request->file(['document']) as $document){
                $fileName = $this->commonService->fileUploadHandler($document, $request->input("description"), "RCR-", 'research.copyrighted.index');
                if(is_string($fileName)) {
                    ResearchDocument::create([
                        'research_code' => $request->input('research_code'),
                        'research_id' => $research->id,
                        'research_form_id' => 7,
                        'filename' => $fileName,
                    ]);
                } else return $fileName;
            }
        }
        return redirect()->route('research.copyrighted.index', $research->id)->with('success', 'Research copyright has been updated.');
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
