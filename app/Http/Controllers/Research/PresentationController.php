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

class PresentationController extends Controller
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
        $this->authorize('viewAny', ResearchPresentation::class);
        $presentationFields = DB::select("CALL get_research_fields_by_form_id('4')");

        $presentationDocuments = ResearchDocument::where('research_code', $research->research_code)->where('research_form_id', 4)->get()->toArray();
        $research = Research::where('research_code', $research->research_code)->where('user_id', auth()->id())
                ->join('dropdown_options', 'dropdown_options.id', 'research.status')
                ->select('research.*', 'dropdown_options.name as status_name')->first();

        $presentationRecord = ResearchPresentation::where('research_code', $research->research_code)->first();
        
        if($presentationRecord == null){
            if ($research->status >= 28)
                return redirect()->route('research.presentation.create', $research->id);
            else {
                $value = null;
                return view('research.presentation.index', compact('research', 'value'));
            }
        }
        $firstResearch = Research::where('research_code', $research->research_code)->first();

        $presentationValues = array_merge(collect($presentationRecord)->except(['research_code'])->toArray(), collect($research)->except(['description'])->toArray());

        $submissionStatus[4][$presentationValues['id']] = $this->commonService->getSubmissionStatus($presentationValues['id'], 4)['submissionStatus'];
        $submitRole[$presentationValues['id']] = $this->commonService->getSubmissionStatus($presentationValues['id'], 4)['submitRole'];

        $value = $this->commonService->getDropdownValues($presentationFields, $presentationValues);

        $noRequisiteRecords[1] = $this->researchController->getNoRequisites($research)['presentationRecord'];
        $noRequisiteRecords[2] = $this->researchController->getNoRequisites($research)['publicationRecord'];
        $noRequisiteRecords[3] = $this->researchController->getNoRequisites($research)['copyrightRecord'];

        return view('research.presentation.index', compact('research', 'presentationFields',
            'value', 'presentationDocuments', 'submissionStatus', 'submitRole', 'noRequisiteRecords'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Research $research)
    {
        $this->authorize('create', ResearchPresentation::class);
        $currentQuarter = Quarter::find(1)->current_quarter;

        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        if(ResearchForm::where('id', 4)->pluck('is_active')->first() == 0)
            return view('inactive');

        $researchFields = DB::select("CALL get_research_fields_by_form_id('4')");

        $dropdown_options = [];
        foreach($researchFields as $field){
            if($field->field_type_name == "dropdown" || $field->field_type_name == "text"){
                $dropdownOptions = DropdownOption::where('dropdown_id', $field->dropdown_id)->where('is_active', 1)->get();
                $dropdown_options[$field->name] = $dropdownOptions;

            }
        }

        // $research = $research->first()->except('description');
        // $research = except($research['description']);
            // dd($research);
        $value = $research;
        $value->toArray();
        $value = collect($research);
        $value = $value->except(['description', 'status']);
        $value = $value->toArray();

        $publicationChecker = ResearchPublication::where('research_code', $research->research_code)->first();

        if($publicationChecker == null){
            $researchStatus = DropdownOption::where('dropdown_options.dropdown_id', 7)->where('id', 29)->first();
        }
        else{
            $researchStatus = DropdownOption::where('dropdown_options.dropdown_id', 7)->where('id', 31)->first();
        }


        return view('research.presentation.create', compact('researchFields', 'research', 'researchStatus', 'value', 'dropdown_options', 'currentQuarter'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Research $research)
    {
        $this->authorize('create', ResearchPresentation::class);
        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        if(ResearchForm::where('id', 4)->pluck('is_active')->first() == 0)
            return view('inactive');

        $date_presented = date("Y-m-d", strtotime($request->input('date_presented')));
        $currentQuarterYear = Quarter::find(1);

        $request->merge([
            'date_presented' => $date_presented,
            'report_quarter' => $currentQuarterYear->current_quarter,
            'report_year' => $currentQuarterYear->current_year,
            'research_id' => $research->id,
        ]);

        $input = $request->except(['_token', '_method', 'status', 'document']);

        $publicationChecker = ResearchPublication::where('research_code', $research->research_code)->first();

        if($publicationChecker == null){
            $researchStatus = 29;
        }
        else{
            $researchStatus = 31;
        }

        Research::where('research_code', $research->research_code)->update([
            'status' => $researchStatus
        ]);


        $presentation = ResearchPresentation::create($input);

        // if($request->has('document')){
        //     try {
        //         $documents = $request->input('document');
        //         foreach($documents as $document){
        //             $temporaryFile = TemporaryFile::where('folder', $document)->first();
        //             if($temporaryFile){
        //                 $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
        //                 $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
        //                 $ext = $info['extension'];
        //                 $fileName = 'RPRE-'.$request->input('research_code').'-'.$this->storageFileController->abbrev($request->input('description')).'-'.now()->timestamp.uniqid().'.'.$ext;
        //                 $newPath = "documents/".$fileName;
        //                 Storage::move($temporaryPath, $newPath);
        //                 Storage::deleteDirectory("documents/tmp/".$document);
        //                 $temporaryFile->delete();
        //                 ResearchDocument::create([
        //                     'research_code' => $request->input('research_code'),
        //                     'research_id' => $research->id,
        //                     'research_form_id' => 4,
        //                     'filename' => $fileName,
        //                 ]);
        //             }
        //         }
        //     } catch (Exception $th) {
        //         return redirect()->back()->with('error', 'Request timeout, Unable to upload, Please try again!' );
        //     } 
        // }

        if(!empty($request->file(['document']))){      
            foreach($request->file(['document']) as $document){
                $fileName = $this->commonService->fileUploadHandler($document, $request->input("description"), "RPRE-", 'research.presentation.index');
                if(is_string($fileName)) {
                    ResearchDocument::create([
                        'research_code' => $request->input('research_code'),
                        'research_id' => $research->id,
                        'research_form_id' => 4,
                        'filename' => $fileName,
                    ]);
                } else return $fileName;
            }
        }

        \LogActivity::addToLog('Had marked the research "'.$research->title.'" as presented.');

        return redirect()->route('research.index')->with('success', 'Research presentation has been added.');
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
    public function edit( Research $research, ResearchPresentation $presentation)
    {
        $currentQuarter = Quarter::find(1)->current_quarter;
        $this->authorize('update', ResearchPresentation::class);

        if (auth()->id() !== $research->user_id)
            abort(403);

        if(LockController::isLocked($presentation->id, 4)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited because you already submitted this accomplishment. You can edit it again in the next quarter.');
        }
        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        if(ResearchForm::where('id', 4)->pluck('is_active')->first() == 0)
            return view('inactive');

        $researchFields = DB::select("CALL get_research_fields_by_form_id('4')");

        $dropdown_options = [];
        foreach($researchFields as $field){
            if($field->field_type_name == "dropdown" || $field->field_type_name == "text"){
                $dropdownOptions = DropdownOption::where('dropdown_id', $field->dropdown_id)->where('is_active', 1)->get();
                $dropdown_options[$field->name] = $dropdownOptions;

            }
        }

        // $research = array_merge($research->toArray(), $presentation->toArray());
        $researchDocuments = ResearchDocument::where('research_code', $research['research_code'])->where('research_form_id', 4)->get()->toArray();

        $value = $research;
        $value = collect($research);
        $value = $value->except(['description', 'status']);
        $value = $value->toArray();
        $value = array_merge($value, $presentation->toArray());


        $presentationChecker = ResearchPresentation::where('research_code', $research->research_code)->first();

        if($presentationChecker == null){
            $researchStatus = DropdownOption::where('dropdown_options.dropdown_id', 7)->where('id', 29)->first();
        }
        else{
            $researchStatus = DropdownOption::where('dropdown_options.dropdown_id', 7)->where('id', 31)->first();
        }

        $noRequisiteRecords[1] = $this->researchController->getNoRequisites($research)['presentationRecord'];
        $noRequisiteRecords[2] = $this->researchController->getNoRequisites($research)['publicationRecord'];
        $noRequisiteRecords[3] = $this->researchController->getNoRequisites($research)['copyrightRecord'];

        return view('research.presentation.edit', compact('research', 'researchFields', 'researchDocuments', 'value', 'researchStatus', 'dropdown_options', 'currentQuarter', 'noRequisiteRecords'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Research $research, ResearchPresentation $presentation)
    {
        $currentQuarterYear = Quarter::find(1);
        $this->authorize('update', ResearchPresentation::class);
        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        if(ResearchForm::where('id', 4)->pluck('is_active')->first() == 0)
            return view('inactive');

        $date_presented = date("Y-m-d", strtotime($request->input('date_presented')));

        $request->merge([
            'date_presented' => $date_presented,
            'report_quarter' => $currentQuarterYear->current_quarter,
            'report_year' => $currentQuarterYear->current_year,
        ]);

        $input = $request->except(['_token', '_method', 'status', 'document']);

        $presentation->update(['description' => '-clear']);

        $presentation->update($input);

        if(!empty($request->file(['document']))){      
            foreach($request->file(['document']) as $document){
                $fileName = $this->commonService->fileUploadHandler($document, $request->input("description"), "RPRE-", 'research.presentation.index');
                if(is_string($fileName)) {
                    ResearchDocument::create([
                        'research_code' => $request->input('research_code'),
                        'research_id' => $research->id,
                        'research_form_id' => 4,
                        'filename' => $fileName,
                    ]);
                } else return $fileName;
            }
        }

        \LogActivity::addToLog('Had updated the presentation details of research "'.$research->title.'".');

        return redirect()->route('research.index')->with('success', 'Research presentation has been updated.');
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
