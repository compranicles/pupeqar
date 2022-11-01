<?php

namespace App\Http\Controllers\Research;

use App\Helpers\LogActivity;
use App\Http\Controllers\{
    Controller,
    Maintenances\LockController,
    StorageFileController,
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{
    DB,
    Storage,
};
use App\Models\{
    Research,
    ResearchDocument,
    ResearchUtilization,
    TemporaryFile,
    FormBuilder\DropdownOption,
    FormBuilder\ResearchForm,
    Maintenance\Quarter,
    Maintenance\Department,
    Maintenance\College,
};
use App\Services\CommonService;
use Exception;

class UtilizationController extends Controller
{
    protected $storageFileController;
    private $commonService;

    public function __construct(StorageFileController $storageFileController, CommonService $commonService){
        $this->storageFileController = $storageFileController;
        $this->commonService = $commonService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Research $research)
    {
        $this->authorize('viewAny', ResearchUtilization::class);;

        $currentQuarterYear = Quarter::find(1);

        $utilizationRecords = ResearchUtilization::where('research_code', $research->research_code)->orderBy('updated_at', 'desc')->get();

        $research= Research::where('research_code', $research->research_code)->where('user_id', auth()->id())
                ->join('dropdown_options', 'dropdown_options.id', 'research.status')
                ->select('research.*', 'dropdown_options.name as status_name')->first();

        $submissionStatus = array();
        $submitRole = array();
        foreach ($utilizationRecords as $utilization) {
            $submissionStatus[6][$utilization->id] = $this->commonService->getSubmissionStatus($utilization->id, 6)['submissionStatus'];
            $submitRole[$utilization->id] = $this->commonService->getSubmissionStatus($utilization->id, 6)['submitRole'];
        }

        return view('research.utilization.index', compact('research', 'utilizationRecords',
            'currentQuarterYear', 'submissionStatus', 'submitRole'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Research $research)
    {
        $this->authorize('create', ResearchUtilization::class);
        $currentQuarter = Quarter::find(1)->current_quarter;

        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        if(ResearchForm::where('id', 6)->pluck('is_active')->first() == 0)
            return view('inactive');

        $researchFields = DB::select("CALL get_research_fields_by_form_id('6')");
        $research = collect($research);
        $research = $research->except(['description']);
        return view('research.utilization.create', compact('researchFields', 'research', 'currentQuarter'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Research $research)
    {
        $this->authorize('create', ResearchUtilization::class);
        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        if(ResearchForm::where('id', 6)->pluck('is_active')->first() == 0)
            return view('inactive');

        $currentQuarterYear = Quarter::find(1);

        $request->merge([
            'report_quarter' => $currentQuarterYear->current_quarter,
            'report_year' => $currentQuarterYear->current_year,
            'research_id' => $research->id,
        ]);

        $input = $request->except(['_token', '_method', 'document']);

        $utilization = ResearchUtilization::create($input);

        $string = str_replace(' ', '-', $request->input('description')); // Replaces all spaces with hyphens.
        $description =  preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.

        if(!empty($request->file(['document']))){      
            foreach($request->file(['document']) as $document){
                $fileName = $this->commonService->fileUploadHandler($document, $request->input("description"), "RCR-", 'research.utilization.index');
                if(is_string($fileName)) {
                    ResearchDocument::create([
                        'research_code' => $request->input('research_code'),
                        'research_id' => $research->id,
                        'research_form_id' => 6,
                        'research_utilization_id' => $utilization->id,
                        'filename' => $fileName,
                    ]);
                } else return $fileName;
            }
        }

        \LogActivity::addToLog('Had added a research utilization for "'.$research->title.'".');

        return redirect()->route('research.index')->with('success', 'Research utilization has been added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Research $research, ResearchUtilization $utilization)
    {
        $this->authorize('view', ResearchUtilization::class);

        if (auth()->id() !== $research->user_id)
            abort(403);

        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        if(ResearchForm::where('id', 6)->pluck('is_active')->first() == 0)
            return view('inactive');

        $researchFields = DB::select("CALL get_research_fields_by_form_id('6')");

        $researchDocuments = ResearchDocument::where('research_utilization_id', $utilization->id)->get()->toArray();

        $research= Research::where('research_code', $research->research_code)->where('user_id', auth()->id())->join('dropdown_options', 'dropdown_options.id', 'research.status')
                ->select('research.*', 'dropdown_options.name as status_name')->first();


        $values = ResearchUtilization::find($utilization->id);

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
        $firstResearch = Research::where('research_code', $research->research_code)->first();

        return view('research.utilization.show', compact('research', 'researchFields', 'values', 'researchDocuments', 'firstResearch'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Research $research, ResearchUtilization $utilization)
    {
        $currentQuarter = Quarter::find(1)->current_quarter;
        $this->authorize('update', ResearchUtilization::class);

        if (auth()->id() !== $research->user_id)
            abort(403);

        if(LockController::isLocked($utilization->id, 6)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited because you already submitted this accomplishment. You can edit it again in the next quarter.');
        }
        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        if(ResearchForm::where('id', 6)->pluck('is_active')->first() == 0)
            return view('inactive');

        $researchFields = DB::select("CALL get_research_fields_by_form_id('6')");

        $researchDocuments = ResearchDocument::where('research_utilization_id', $utilization->id)->get()->toArray();

        $research= Research::where('research_code', $research->research_code)->join('dropdown_options', 'dropdown_options.id', 'research.status')
                ->select('research.*', 'dropdown_options.name as status_name')->first();


        $values = ResearchUtilization::find($utilization->id);

        $values = array_merge($research->toArray(), $values->toArray());

        return view('research.utilization.edit', compact('research', 'researchFields', 'values', 'researchDocuments', 'currentQuarter'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Research $research, ResearchUtilization $utilization)
    {
        $currentQuarterYear = Quarter::find(1);
        $this->authorize('update', ResearchUtilization::class);
        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        if(ResearchForm::where('id', 6)->pluck('is_active')->first() == 0)
            return view('inactive');

        $request->merge([
            'report_quarter' => $currentQuarterYear->current_quarter,
            'report_year' => $currentQuarterYear->current_year,
        ]);

        $input = $request->except(['_token', '_method', 'document']);

        $utilization->update(['description' => '-clear']);

        $utilization->update($input);

        $string = str_replace(' ', '-', $utilization->description); // Replaces all spaces with hyphens.
        $description =  preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.

        // if($request->has('document')){

        //     try {
        //         $documents = $request->input('document');
        //         foreach($documents as $document){
        //             $temporaryFile = TemporaryFile::where('folder', $document)->first();
        //             if($temporaryFile){
        //                 $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
        //                 $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
        //                 $ext = $info['extension'];
        //                 $fileName = 'RU-'.$request->input('research_code').'-'.$description.'-'.now()->timestamp.uniqid().'.'.$ext;
        //                 $newPath = "documents/".$fileName;
        //                 Storage::move($temporaryPath, $newPath);
        //                 Storage::deleteDirectory("documents/tmp/".$document);
        //                 $temporaryFile->delete();

        //                 ResearchDocument::create([
        //                     'research_code' => $request->input('research_code'),
        //                     'research_id' => $research->id,
        //                     'research_form_id' => 6,
        //                     'research_utilization_id' => $utilization->id,
        //                     'filename' => $fileName,
        //                 ]);
        //             }
        //         }
        //     }  catch (Exception $th) {
        //         return redirect()->back()->with('error', 'Request timeout, Unable to upload, Please try again!' );
        //     }     
        // }

        LogActivity::addToLog('Had updated a research utilization of "'.$research->title.'".');

        if(!empty($request->file(['document']))){      
            foreach($request->file(['document']) as $document){
                $fileName = $this->commonService->fileUploadHandler($document, $request->input("description"), "RU-", 'research.utilization.index');
                if(is_string($fileName)) {
                    ResearchDocument::create([
                        'research_code' => $request->input('research_code'),
                        'research_id' => $research->id,
                        'research_form_id' => 6,
                        'research_utilization_id' => $utilization->id,
                        'filename' => $fileName,
                    ]);
                } else return $fileName;
            }
        }

        return redirect()->route('research.utilization.show', [$research->id, $utilization->id])->with('success', 'Research Utilization has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Research $research, ResearchUtilization $utilization)
    {
        $this->authorize('delete', ResearchUtilization::class);
        if(LockController::isLocked($research->id, 1)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited because you already submitted this accomplishment. You can edit it again in the next quarter.');
        }
        if(LockController::isLocked($utilization->id, 6)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited because you already submitted this accomplishment. You can edit it again in the next quarter.');
        }
        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        if(ResearchForm::where('id', 6)->pluck('is_active')->first() == 0)
            return view('inactive');

        $utilization->delete();

        LogActivity::addToLog('Had deleted a research utilization of "'.$research->title.'".');

        return redirect()->route('research.utilization.index', $research->id)->with('success', 'Research utilization has been deleted.');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showAll($researchId)
    {
        $this->authorize('viewAny', ResearchUtilization::class);;

        $currentQuarterYear = Quarter::find(1);
        $research = Research::find($researchId);

        $utilizationRecords = ResearchUtilization::where('research_code', $research->research_code)->orderBy('updated_at', 'desc')->get();

        $research= Research::where('research_code', $research->research_code)->where('user_id', auth()->id())
                ->join('dropdown_options', 'dropdown_options.id', 'research.status')
                ->select('research.*', 'dropdown_options.name as status_name')->first();

        $submissionStatus = array();
        $submitRole = array();
        foreach ($utilizationRecords as $utilization) {
            $submissionStatus[6][$utilization->id] = $this->commonService->getSubmissionStatus($utilization->id, 6)['submissionStatus'];
            $submitRole[$utilization->id] = $this->commonService->getSubmissionStatus($utilization->id, 6)['submitRole'];
        }

        return view('research.utilization.show-all', compact('research', 'utilizationRecords',
            'currentQuarterYear', 'submissionStatus', 'submitRole'));
    }
}
