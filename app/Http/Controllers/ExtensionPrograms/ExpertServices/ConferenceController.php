<?php

namespace App\Http\Controllers\ExtensionPrograms\ExpertServices;

use App\Helpers\LogActivity;
use App\Http\Controllers\{
    Controller,
    Maintenances\LockController,
    Reports\ReportDataController,
    StorageFileController,
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\{
    Employee,
    ExpertServiceConference,
    ExpertServiceConferenceDocument,
    TemporaryFile,
    FormBuilder\DropdownOption,
    FormBuilder\ExtensionProgramField,
    FormBuilder\ExtensionProgramForm,
    Maintenance\College,
    Maintenance\Department,
    Maintenance\Quarter,
};
use App\Services\CommonService;
use Exception;

class ConferenceController extends Controller
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
    public function index()
    {
        $this->authorize('viewAny', ExpertServiceConference::class);

        $currentQuarterYear = Quarter::find(1);

        $expertServicesConference = ExpertServiceConference::where('user_id', auth()->id())
                                        ->join('dropdown_options', 'dropdown_options.id', 'expert_service_conferences.nature')
                                        ->join('colleges', 'colleges.id', 'expert_service_conferences.college_id')
                                        ->select(DB::raw('expert_service_conferences.*, dropdown_options.name as nature, colleges.name as college_name'))
                                        ->orderBy('expert_service_conferences.updated_at', 'desc')
                                        ->get();

        $submissionStatus = [];
        $submitRole = "";
        $reportdata = new ReportDataController;
        foreach ($expertServicesConference as $conference) {
            if (LockController::isLocked($conference->id, 10)) {
                $submissionStatus[10][$conference->id] = 1;
                $submitRole[$conference->id] = ReportDataController::getSubmitRole($conference->id, 10);
            }
            else
                $submissionStatus[10][$conference->id] = 0;
            if (empty($reportdata->getDocuments(10, $conference->id)))
                $submissionStatus[10][$conference->id] = 2;
        }

        return view('extension-programs.expert-services.conference.index', compact('expertServicesConference',
             'currentQuarterYear', 'submissionStatus', 'submitRole'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', ExpertServiceConference::class);
        $currentQuarter = Quarter::find(1)->current_quarter;

        if(ExtensionProgramForm::where('id', 2)->pluck('is_active')->first() == 0)
            return view('inactive');
        $expertServiceConferenceFields = DB::select("CALL get_extension_program_fields_by_form_id('2')");

        $dropdown_options = [];
        foreach($expertServiceConferenceFields as $field){
            if($field->field_type_name == "dropdown" || $field->field_type_name == "text"){
                $dropdownOptions = DropdownOption::where('dropdown_id', $field->dropdown_id)->where('is_active', 1)->get();
                $dropdown_options[$field->name] = $dropdownOptions;

            }
        }

        if(session()->get('user_type') == 'Faculty Employee')
            $colleges = Employee::where('user_id', auth()->id())->where('type', 'F')->pluck('college_id')->all();
        else
            $colleges = Employee::where('user_id', auth()->id())->where('type', 'A')->pluck('college_id')->all();

        $departments = Department::whereIn('college_id', $colleges)->get();

        return view('extension-programs.expert-services.conference.create', compact('expertServiceConferenceFields', 'colleges', 'departments', 'dropdown_options', 'currentQuarter'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', ExpertServiceConference::class);

        if(ExtensionProgramForm::where('id', 2)->pluck('is_active')->first() == 0)
            return view('inactive');

        $from = date("Y-m-d", strtotime($request->input('from')));
        $to = date("Y-m-d", strtotime($request->input('to')));
        $currentQuarterYear = Quarter::find(1);

        $request->merge([
            'from' => $from,
            'to' => $to,
            'report_quarter' => $currentQuarterYear->current_quarter,
            'report_year' => $currentQuarterYear->current_year,
            'college_id' => Department::where('id', $request->input('department_id'))->pluck('college_id')->first(),
        ]);

        $request->validate([
            'to' => 'after_or_equal:from',
            'title' => 'max:500',
            'college_id' => 'required',
            'department_id' => 'required'
        ]);

        $input = $request->except(['_token', '_method', 'document']);

        $esConference = ExpertServiceConference::create($input);
        $esConference->update(['user_id' => auth()->id()]);

        LogActivity::addToLog('Had added an expert service rendered in conference "'.$request->input('title').'".');

        if($request->has('document')){
            $documents = $request->input('document');
            foreach($documents as $document){
                $fileName = $this->commonService->fileUploadHandler($document, $request->input('description'), 'ESCF-', 'expert-service-in-conference.index');
                if(is_string($fileName)) ExpertServiceConferenceDocument::create(['expert_service_conference_id' => $esConference->id, 'filename' => $fileName]);
                else return $fileName;
            }
        }

        return redirect()->route('expert-service-in-conference.index')->with('edit_esconference_success', 'Expert service rendered in conference, workshop, or training course has been added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ExpertServiceConference $expert_service_in_conference)
    {
        $this->authorize('view', ExpertServiceConference::class);

        if (auth()->id() !== $expert_service_in_conference->user_id)
            abort(403);

        if(ExtensionProgramForm::where('id', 2)->pluck('is_active')->first() == 0)
            return view('inactive');

        $expertServiceConferenceFields = DB::select("CALL get_extension_program_fields_by_form_id('2')");

        $documents = ExpertServiceConferenceDocument::where('expert_service_conference_id', $expert_service_in_conference->id)->get()->toArray();

        $values = $expert_service_in_conference->toArray();

        foreach($expertServiceConferenceFields as $field){
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

        return view('extension-programs.expert-services.conference.show', compact('expertServiceConferenceFields','expert_service_in_conference', 'documents', 'values'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(ExpertServiceConference $expert_service_in_conference)
    {
        $this->authorize('update', ExpertServiceConference::class);
        $currentQuarter = Quarter::find(1)->current_quarter;

        if (auth()->id() !== $expert_service_in_conference->user_id)
            abort(403);

        if(LockController::isLocked($expert_service_in_conference->id, 10)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited because you already submitted this accomplishment. You can edit it again in the next quarter.');
        }

        if(ExtensionProgramForm::where('id', 2)->pluck('is_active')->first() == 0)
            return view('inactive');
        $expertServiceConferenceFields = DB::select("CALL get_extension_program_fields_by_form_id('2')");

        $dropdown_options = [];
        foreach($expertServiceConferenceFields as $field){
            if($field->field_type_name == "dropdown" || $field->field_type_name == "text"){
                $dropdownOptions = DropdownOption::where('dropdown_id', $field->dropdown_id)->where('is_active', 1)->get();
                $dropdown_options[$field->name] = $dropdownOptions;

            }
        }

        $expertServiceConferenceDocuments = ExpertServiceConferenceDocument::where('expert_service_conference_id', $expert_service_in_conference->id)->get()->toArray();

        if(session()->get('user_type') == 'Faculty Employee')
            $colleges = Employee::where('user_id', auth()->id())->where('type', 'F')->pluck('college_id')->all();
        else
            $colleges = Employee::where('user_id', auth()->id())->where('type', 'A')->pluck('college_id')->all();

        $departments = Department::whereIn('college_id', $colleges)->get();

        $value = $expert_service_in_conference;
        $value->toArray();
        $value = collect($expert_service_in_conference);
        $value = $value->toArray();

        return view('extension-programs.expert-services.conference.edit', compact('expert_service_in_conference', 'expertServiceConferenceFields', 'expertServiceConferenceDocuments', 'colleges', 'value', 'departments', 'dropdown_options', 'currentQuarter'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ExpertServiceConference $expert_service_in_conference)
    {
        $this->authorize('update', ExpertServiceConference::class);
        $currentQuarterYear = Quarter::find(1);

        if(ExtensionProgramForm::where('id', 2)->pluck('is_active')->first() == 0)
            return view('inactive');

        $from = date("Y-m-d", strtotime($request->input('from')));
        $to = date("Y-m-d", strtotime($request->input('to')));

        $request->merge([
            'from' => $from,
            'to' => $to,
            'college_id' => Department::where('id', $request->input('department_id'))->pluck('college_id')->first(),
            'report_quarter' => $currentQuarterYear->current_quarter,
            'report_year' => $currentQuarterYear->current_year,
        ]);

        $request->validate([
            'to' => 'after_or_equal:from',
            'title' => 'max:500',
            'college_id' => 'required',
            'department_id' => 'required'
        ]);

        $input = $request->except(['_token', '_method', 'document']);

        $expert_service_in_conference->update(['description' => '-clear']);

        $expert_service_in_conference->update($input);

        // return $request->file('document')
        // if($request->has('document')){
        //     foreach($request->input('document') as $file){
        //         return $file;
        //     }
        // }

        return $request->input('document');
        // return $request->file(['document']);

        if(file_exists($request->file(['document']))){
            $documents = $request->file(['document']);
            foreach($documents as $file){
                return $file->getClientOriginalName();
                // $fileName = $this->commonService->fileUploadHandler($document, $request->input('description'), 'ESCF-', 'expert-service-in-conference.index');
                // if(is_string($fileName)) ExpertServiceConferenceDocument::create(['expert_service_conference_id' => $expert_service_in_conference->id, 'filename' => $fileName]);
                // else return $fileName;
            }
        }
       

        LogActivity::addToLog('Had updated the expert service rendered in conference "'.$expert_service_in_conference->title.'".');
        // return $request->input('document');
        if(file_exists($request->file(['document']))){
            $documents = $request->file(['document']);
            foreach($documents as $document){
                $fileName = $this->commonService->fileUploadHandler($document, $request->input('description'), 'ESCF-', 'expert-service-in-conference.index');
                if(is_string($fileName)) ExpertServiceConferenceDocument::create(['expert_service_conference_id' => $expert_service_in_conference->id, 'filename' => $fileName]);
                else return $fileName;
            }
        }

        return redirect()->route('expert-service-in-conference.index')->with('edit_esconference_success', 'Expert service rendered in conference, workshop, or training course has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ExpertServiceConference $expert_service_in_conference)
    {
        $this->authorize('delete', ExpertServiceConference::class);

        if(LockController::isLocked($expert_service_in_conference->id, 10)){
            return redirect()->back()->with('cannot_access', 'Cannot be edited because you already submitted this accomplishment. You can edit it again in the next quarter.');
        }


        if(ExtensionProgramForm::where('id', 2)->pluck('is_active')->first() == 0)
            return view('inactive');
        $expert_service_in_conference->delete();
        ExpertServiceConferenceDocument::where('expert_service_conference_id', $expert_service_in_conference->id)->delete();

        LogActivity::addToLog('Had deleted the expert service rendered in conference "'.$expert_service_in_conference->title.'".');

        return redirect()->route('expert-service-in-conference.index')->with('edit_esconference_success', 'Expert service rendered in conference, workshop, or training course has been deleted.');
    }

    public function removeDoc($filename){
        $this->authorize('delete', ExpertServiceConference::class);

        if(ExtensionProgramForm::where('id', 2)->pluck('is_active')->first() == 0)
            return view('inactive');
        ExpertServiceConferenceDocument::where('filename', $filename)->delete();

        LogActivity::addToLog('Had deleted a document of an expert service rendered in conference.');

        // Storage::delete('documents/'.$filename);
        return true;
    }
}
