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
    FormBuilder\ResearchForm,
    FormBuilder\DropdownOption,
    Maintenance\Quarter,
    Maintenance\Department,
    Maintenance\College,
};

class PublicationController extends Controller
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
        $this->authorize('viewAny', ResearchPublication::class);

        $researchFields = DB::select("CALL get_research_fields_by_form_id('3')");

        $researchDocuments = ResearchDocument::where('research_code', $research->research_code)->where('research_form_id', 3)->get()->toArray();

        $values = ResearchPublication::where('research_code', $research->research_code)->first();
        if($values == null){
            return redirect()->route('research.show', $research->research_code);
        }

        $values = collect($values->toArray());
        $values = $values->except(['research_code']);
        $values = $values->toArray();

        $value = $research;
        $value->toArray();
        $value = collect($research);
        $value = $value->except(['description']);
        $value = $value->toArray();

        $value = array_merge($value, $values);

        $submissionStatus = [];
        $submitRole = "";
        $reportdata = new ReportDataController;
            if (LockController::isLocked($values['id'], 3)) {
                $submissionStatus[3][$values['id']] = 1;
                $submitRole[$values['id']] = ReportDataController::getSubmitRole($values['id'], 3);
            }
            else
                $submissionStatus[3][$values['id']] = 0;
            if (empty($reportdata->getDocuments(3, $values['id'])))
                $submissionStatus[3][$values['id']] = 2;
        
        foreach($researchFields as $field){
            if($field->field_type_name == "dropdown"){
                $dropdownOptions = DropdownOption::where('id', $value[$field->name])->where('is_active', 1)->pluck('name')->first();
                if($dropdownOptions == null)
                    $dropdownOptions = "-";
                $value[$field->name] = $dropdownOptions;
            }
            elseif($field->field_type_name == "college"){
                if($value[$field->name] == '0'){
                    $value[$field->name] = 'N/A';
                }
                else{
                    $college = College::where('id', $value[$field->name])->pluck('name')->first();
                    $value[$field->name] = $college;
                }
            }
            elseif($field->field_type_name == "department"){
                if($value[$field->name] == '0'){
                    $value[$field->name] = 'N/A';
                }
                else{
                    $department = Department::where('id', $value[$field->name])->pluck('name')->first();
                    $value[$field->name] = $department;
                }
            }
        }

        return view('research.publication.index', compact('research', 'researchFields',
            'value', 'researchDocuments', 'submissionStatus', 'submitRole'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Research $research)
    {
        $this->authorize('create', ResearchPublication::class);

        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        if(ResearchForm::where('id', 3)->pluck('is_active')->first() == 0)
            return view('inactive');

        $researchFields = DB::select("CALL get_research_fields_by_form_id('3')");

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

        $presentationChecker = ResearchPresentation::where('research_code', $research->research_code)->first();

        if($presentationChecker == null){
            $researchStatus = DropdownOption::where('dropdown_options.dropdown_id', 7)->where('id', 30)->first();
        }
        else{
            $researchStatus = DropdownOption::where('dropdown_options.dropdown_id', 7)->where('id', 31)->first();
        }

        return view('research.publication.create', compact('researchFields', 'research', 'researchStatus', 'value', 'dropdown_options'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Research $research)
    {
        $this->authorize('create', ResearchPublication::class);
        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        if(ResearchForm::where('id', 3)->pluck('is_active')->first() == 0)
            return view('inactive');

        $publish_date = date("Y-m-d", strtotime($request->input('publish_date')));
        $currentQuarterYear = Quarter::find(1);

        $request->merge([
            'publish_date' => $publish_date,
            'report_quarter' => $currentQuarterYear->current_quarter,
            'report_year' => $currentQuarterYear->current_year,
            'research_id' => $research->id,
        ]);

        $input = $request->except(['_token', '_method', 'status', 'document']);


        $presentationChecker = ResearchPresentation::where('research_code', $research->research_code)->first();

        if($presentationChecker == null){
            $researchStatus = 30;
        }
        else{
            $researchStatus = 31;
        }

        Research::where('research_code', $research->research_code)->update([
            'status' => $researchStatus
        ]);

        $publication = ResearchPublication::create($input);

        if($request->has('document')){

            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'RPUB-'.$request->input('research_code').'-'.$this->storageFileController->abbrev($request->input('description')).'-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    ResearchDocument::create([
                        'research_code' => $request->input('research_code'),
                        'research_id' => $research->id,
                        'research_form_id' => 3,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        \LogActivity::addToLog('Had marked the research "'.$research->title.'" as presented.');

        return redirect()->route('research.publication.index', $research->id)->with('success', 'Research publication has been added.');
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
    public function edit(Research $research, ResearchPublication $publication)
    {
        $this->authorize('update', ResearchPublication::class);

        if (auth()->id() !== $research->user_id)
            abort(403);

        if(LockController::isLocked($publication->id, 3)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited because you already submitted this accomplishment. You can edit it again in the next quarter.');
        }
        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        if(ResearchForm::where('id', 3)->pluck('is_active')->first() == 0)
            return view('inactive');

        $researchFields = DB::select("CALL get_research_fields_by_form_id('3')");

        $dropdown_options = [];
        foreach($researchFields as $field){
            if($field->field_type_name == "dropdown" || $field->field_type_name == "text"){
                $dropdownOptions = DropdownOption::where('dropdown_id', $field->dropdown_id)->where('is_active', 1)->get();
                $dropdown_options[$field->name] = $dropdownOptions;

            }
        }

        // $research = array_merge($research->toArray(), $publication->toArray());
        $researchDocuments = ResearchDocument::where('research_code', $research['research_code'])->where('research_form_id', 3)->get()->toArray();

        $value = $research->toArray();
        $value = collect($research);
        $value = $value->except(['description', 'status']);
        $value = $value->toArray();
        $value = array_merge($value, $publication->toArray());

        $presentationChecker = ResearchPresentation::where('research_code', $research->research_code)->first();

        if($presentationChecker == null){
            $researchStatus = DropdownOption::where('dropdown_options.dropdown_id', 7)->where('id', 30)->first();
        }
        else{
            $researchStatus = DropdownOption::where('dropdown_options.dropdown_id', 7)->where('id', 31)->first();
        }

        return view('research.publication.edit', compact('research', 'researchFields', 'researchDocuments', 'value', 'researchStatus', 'dropdown_options'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Research $research, ResearchPublication $publication)
    {
        $this->authorize('update', ResearchPublication::class);

        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        if(ResearchForm::where('id', 3)->pluck('is_active')->first() == 0)
            return view('inactive');

        $publish_date = date("Y-m-d", strtotime($request->input('publish_date')));

        $request->merge([
            'publish_date' => $publish_date,
        ]);

        $input = $request->except(['_token', '_method', 'status', 'document']);

        $publication->update(['description' => '-clear']);

        $publication->update($input);

        if($request->has('document')){

            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'RPUB-'.$request->input('research_code').'-'.$this->storageFileController->abbrev($request->input('description')).'-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    ResearchDocument::create([
                        'research_code' => $request->input('research_code'),
                        'research_id' => $research->id,
                        'research_form_id' => 3,
                        'filename' => $fileName,
                    ]);
                }
            }
        }

        \LogActivity::addToLog('Had updated the publication details of research "'.$research->title.'".');


        return redirect()->route('research.publication.index', $research->id)->with('success', 'Research publication has been updated.');
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
